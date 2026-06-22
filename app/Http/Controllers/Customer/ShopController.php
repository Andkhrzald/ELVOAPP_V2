<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use App\Models\Product;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\ActivityLog;
use App\Models\StockMutation;
use App\Models\ProductVariant;
use App\Models\ReviewImage;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;
use App\Models\Wishlist;
use App\Models\Setting;
use App\Models\Review;
use Barryvdh\DomPDF\Facade\Pdf;

class ShopController extends Controller
{
    public function index(Request $request)
    {
        $query = Product::with(['category.parent', 'reviews'])->where('stock', '>', 0)->where('is_active', true);

        if ($request->filled('category')) {
            $query->whereHas('category', fn($q) => $q->where('slug', $request->category));
        }

        if ($request->filled('search')) {
            $query->where('name', 'like', '%' . $request->search . '%');
        }

        $sort = $request->get('sort', 'latest');
        $query = match($sort) {
            'price_low'  => $query->orderBy('price', 'asc'),
            'price_high' => $query->orderBy('price', 'desc'),
            'name'       => $query->orderBy('name', 'asc'),
            default      => $query->latest(),
        };

        $products = $query->get();
        $categories = \App\Models\Category::parents()->orderBy('name')->get();

        $wishlistIds = Auth::check()
            ? Wishlist::where('user_id', Auth::id())->pluck('product_id')->toArray()
            : [];

        return view('customer.shop', compact('products', 'categories', 'wishlistIds'));
    }

    public function detail($slug)
    {
        $product = Product::with([
            'category.parent',
            'images' => fn($q) => $q->orderBy('sort_order'),
            'activeVariants',
            'reviews' => fn($q) => $q->with(['user', 'images'])->latest(),
        ])
        ->where('slug', $slug)
        ->where('is_active', true)
        ->firstOrFail();

        $avgRating = $product->reviews->avg('rating') ?? 0;
        $totalReviews = $product->reviews->count();
        $totalSold = OrderItem::where('product_id', $product->id)
            ->whereHas('order', fn($q) => $q->whereIn('status', ['selesai', 'dikirim']))
            ->sum('quantity');

        $ratingDistribution = [];
        for ($i = 5; $i >= 1; $i--) {
            $ratingDistribution[$i] = $product->reviews->where('rating', $i)->count();
        }

        $wishlistIds = Auth::check()
            ? Wishlist::where('user_id', Auth::id())->pluck('product_id')->toArray()
            : [];

        $relatedProducts = Product::with('category')
            ->where('category_id', $product->category_id)
            ->where('id', '!=', $product->id)
            ->where('is_active', true)
            ->where('stock', '>', 0)
            ->latest()
            ->take(8)
            ->get();

        return view('customer.product-detail', compact(
            'product', 'avgRating', 'totalReviews', 'totalSold',
            'ratingDistribution', 'wishlistIds', 'relatedProducts'
        ));
    }

    public function getVariants($id)
    {
        $product = Product::findOrFail($id);
        $variants = $product->activeVariants()->get();

        $sizes = $variants->pluck('size')->unique()->filter()->values();
        $colors = $variants->pluck('color')->unique()->filter()->values();

        $colorData = [];
        foreach ($colors as $color) {
            $first = $variants->where('color', $color)->first();
            $totalStock = $variants->where('color', $color)->sum('stock');
            $colorData[] = [
                'color' => $color,
                'color_hex' => $first->color_hex ?? '#000000',
                'stock' => $totalStock,
                'image' => $first->image,
            ];
        }

        return response()->json([
            'variants' => $variants,
            'sizes' => $sizes,
            'colors' => $colorData,
        ]);
    }

    public function checkVariantStock($id)
    {
        $variant = ProductVariant::findOrFail($id);
        return response()->json([
            'in_stock' => $variant->stock > 0,
            'stock' => $variant->stock,
            'price' => $variant->price ?? $variant->product->price,
            'image' => $variant->image,
        ]);
    }

    public function checkout()
    {
        $qrisPath = Setting::getValue('payment_qris', '');
        return view('customer.checkout', compact('qrisPath'));
    }

    public function processCheckout(Request $request)
    {
        $request->validate([
            'name'           => 'required|string|max:255',
            'phone'          => 'required|string|max:20',
            'address'        => 'required|string|max:1000',
            'landmark'       => 'nullable|string|max:255',
            'payment_method' => 'required|string',
            'cart'           => 'required|string',
        ]);

        $cartItems = json_decode($request->input('cart'), true);
        if (empty($cartItems)) {
            return redirect()->back()->with('error', 'Keranjang belanja kosong.');
        }

        $totalPrice = 0;
        $validatedItems = [];

        foreach ($cartItems as $item) {
            $product = Product::find($item['id']);
            if (!$product || !$product->is_active) {
                return redirect()->back()->with('error', 'Produk tidak tersedia.');
            }

            $variantId = $item['variant_id'] ?? null;
            $variantLabel = null;

            if ($variantId) {
                $variant = ProductVariant::find($variantId);
                if (!$variant || !$variant->is_active || $variant->stock < $item['qty']) {
                    return redirect()->back()->with('error', 'Stok varian ' . ($variant->displayLabel() ?? 'produk') . ' tidak cukup.');
                }
                $price = $variant->price ?? $product->price;
                $variantLabel = $variant->displayLabel();
            } else {
                if ($product->hasVariants()) {
                    return redirect()->back()->with('error', 'Silakan pilih varian untuk ' . $product->name);
                }
                if ($product->stock < $item['qty']) {
                    return redirect()->back()->with('error', 'Stok ' . $product->name . ' tidak cukup.');
                }
                $price = $product->price;
            }

            $subtotal = $price * $item['qty'];
            $totalPrice += $subtotal;
            $validatedItems[] = [
                'product'       => $product,
                'variant_id'    => $variantId,
                'variant_label' => $variantLabel,
                'qty'           => $item['qty'],
                'price'         => $price,
                'subtotal'      => $subtotal,
            ];
        }

        $shippingCost = (int) (Setting::getValue('shipping_cost', '20000'));
        $grandTotal = $totalPrice + $shippingCost;

        $today = now()->format('Ymd');
        $lastOrder = Order::where('order_number', 'like', "INV-{$today}-%")->orderBy('id', 'desc')->first();
        $seq = $lastOrder ? (intval(substr($lastOrder->order_number, -3)) + 1) : 1;
        $orderNumber = "INV-{$today}-" . str_pad($seq, 3, '0', STR_PAD_LEFT);

        if (Auth::check()) {
            $user = Auth::user();
            $user->update([
                'phone'   => $request->input('phone'),
                'address' => $request->input('address'),
            ]);
            $userId = $user->id;
        } else {
            $userId = null;
        }

        $bankPrefixes = ['BCA' => '88008', 'BRI' => '88009', 'BNI' => '88010', 'Mandiri' => '88011'];
        $selectedBank = null;
        $vaNumber = null;
        if ($request->payment_method === 'bank_transfer' && $request->filled('selected_bank')) {
            $selectedBank = $request->selected_bank;
            $prefix = $bankPrefixes[$selectedBank] ?? '88000';
            $vaNumber = $prefix . str_pad((Order::max('id') ?? 0) + 1, 6, '0', STR_PAD_LEFT);
        }

        DB::transaction(function () use ($orderNumber, $userId, $grandTotal, $shippingCost, $request, $validatedItems, $vaNumber, $selectedBank, &$order) {
            $orderNotes = '';
            if ($request->filled('landmark')) {
                $orderNotes = 'Patokan: ' . $request->landmark;
            }

            $vaExpiresAt = now()->addHours(24);

            $order = Order::create([
                'user_id'         => $userId,
                'order_number'    => $orderNumber,
                'total_price'     => $grandTotal,
                'status'          => 'pending',
                'payment_method'  => $request->input('payment_method'),
                'selected_bank'   => $selectedBank,
                'va_number'       => $vaNumber,
                'va_expires_at'   => $vaExpiresAt,
                'shipping_method' => 'Standard Shipping',
                'shipping_cost'   => $shippingCost,
                'notes'           => $orderNotes,
            ]);

            foreach ($validatedItems as $item) {
                $productName = $item['product']->name;
                if ($item['variant_label']) {
                    $productName .= " ({$item['variant_label']})";
                }

                OrderItem::create([
                    'order_id'      => $order->id,
                    'product_id'    => $item['product']->id,
                    'variant_id'    => $item['variant_id'],
                    'variant_label' => $item['variant_label'],
                    'product_name'  => $productName,
                    'quantity'      => $item['qty'],
                    'price'         => $item['price'],
                    'subtotal'      => $item['subtotal'],
                ]);

                if ($item['variant_id']) {
                    ProductVariant::find($item['variant_id'])->decrement('stock', $item['qty']);
                    $prod = $item['product'];
                    $prod->decrement('stock', $item['qty']);
                    StockMutation::log($prod, 'order', $item['qty'], 'Pesanan #' . $orderNumber . ' (' . $item['variant_label'] . ')');
                } else {
                    $item['product']->decrement('stock', $item['qty']);
                    StockMutation::log($item['product'], 'order', $item['qty'], 'Pesanan #' . $orderNumber);
                }
            }

            ActivityLog::create([
                'user_id'     => $userId,
                'action'      => 'order_created',
                'description' => 'Pesanan baru #' . $orderNumber . ' dari ' . ($request->input('name')),
                'model_type'  => 'Order',
                'model_id'    => $order->id,
            ]);
        });

        return redirect()->route('payment.page', $order->id);
    }

    public function payment($orderId)
    {
        $order = Order::with('items')->where('id', $orderId)
            ->where('user_id', Auth::id())
            ->firstOrFail();

        return view('customer.payment', compact('order'));
    }

    public function checkPaymentStatus($orderId)
    {
        $order = Order::where('id', $orderId)
            ->where('user_id', Auth::id())
            ->firstOrFail();

        $expired = false;
        if ($order->status === 'pending' && $order->va_expires_at && now()->gt($order->va_expires_at)) {
            $order->update([
                'status' => 'batal',
                'cancel_reason' => 'VA expired',
            ]);
            foreach ($order->items as $item) {
                $product = Product::find($item->product_id);
                if ($product) {
                    $product->increment('stock', $item->quantity);
                    if ($item->variant_id) {
                        ProductVariant::find($item->variant_id)->increment('stock', $item->quantity);
                    }
                    StockMutation::log($product, 'expired', $item->quantity, 'VA expired #' . $order->order_number);
                }
            }
            ActivityLog::create([
                'user_id' => Auth::id(),
                'action' => 'order_expired',
                'description' => 'Pesanan #' . $order->order_number . ' expired (VA 24 jam)',
                'model_type' => 'Order', 'model_id' => $order->id,
            ]);
            $expired = true;
        }

        return response()->json([
            'status'  => $order->status,
            'success' => $order->status === 'proses',
            'expired' => $expired,
        ]);
    }

    public function forceComplete($orderId)
    {
        $order = Order::where('id', $orderId)
            ->where('user_id', Auth::id())
            ->firstOrFail();

        if ($order->status !== 'pending') {
            return redirect()->back()->with('error', 'Pesanan ini sudah diproses.');
        }

        $order->update(['status' => 'proses']);

        ActivityLog::create([
            'user_id' => Auth::id(),
            'action' => 'payment_force_completed',
            'description' => 'Pembayaran #' . $order->order_number . ' diselesaikan (bypass demo)',
            'model_type' => 'Order', 'model_id' => $order->id,
        ]);

        return redirect()->route('checkout.success', ['order' => $order->id]);
    }

    public function success(Request $request)
    {
        $order = null;
        if ($request->has('order')) {
            $order = Order::with('items')->where('id', $request->order)
                ->where('user_id', Auth::id())
                ->first();
        }
        return view('customer.success', compact('order'));
    }

    public function invoice($id)
    {
        $order = Order::with(['items.product', 'user'])->where('id', $id)
            ->where('user_id', Auth::id())
            ->firstOrFail();

        $pdf = Pdf::loadView('admin.invoice', compact('order'));
        return $pdf->download('INVOICE-' . $order->order_number . '.pdf');
    }

    // --- AUTH ---
    public function login() { return view('customer.auth.login'); }
    public function register() { return view('customer.auth.register'); }

    public function storeRegister(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8',
        ]);
        User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
        ]);
        return redirect()->route('login')->with('success', 'Registration successful!');
    }

    public function postLogin(Request $request)
    {
        $credentials = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required'],
        ]);
        if (Auth::attempt($credentials)) {
            $request->session()->regenerate();
            if (in_array(Auth::user()->role, ['admin', 'owner'])) {
                return redirect()->intended('/admin/dashboard');
            }
            return redirect()->intended('/');
        }
        return back()->withErrors(['email' => 'Invalid credentials.']);
    }

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect('/');
    }

    // --- WISHLIST ---
    public function wishlist()
    {
        $items = Wishlist::with('product')
            ->where('user_id', Auth::id())
            ->latest()
            ->get();
        return view('customer.wishlist', compact('items'));
    }

    public function toggleWishlist(Request $request)
    {
        if (!Auth::check()) {
            return response()->json(['error' => 'Login dulu'], 401);
        }
        $request->validate(['product_id' => 'required|exists:products,id']);
        $existing = Wishlist::where('user_id', Auth::id())
            ->where('product_id', $request->product_id)->first();

        if ($existing) {
            $existing->delete();
            return response()->json(['status' => 'removed']);
        }

        $count = Wishlist::where('user_id', Auth::id())->count();
        if ($count >= 20) {
            return response()->json(['error' => 'Wishlist penuh (max 20)'], 422);
        }

        Wishlist::create([
            'user_id' => Auth::id(),
            'product_id' => $request->product_id,
        ]);
        return response()->json(['status' => 'added']);
    }

    public function moveToCart($id)
    {
        $item = Wishlist::where('id', $id)->where('user_id', Auth::id())->firstOrFail();
        $item->delete();
        return redirect()->back()->with('success', 'Produk dipindahkan ke keranjang');
    }

    // --- UPLOAD BUKTI BAYAR ---
    public function uploadProof(Request $request, $id)
    {
        $request->validate(['payment_proof' => 'required|image|max:2048']);
        $order = Order::where('id', $id)->where('user_id', Auth::id())->firstOrFail();
        $path = $request->file('payment_proof')->store('payment-proofs', 'public');
        $order->update(['payment_proof' => $path]);
        return redirect()->back()->with('success', 'Bukti bayar terkirim. Menunggu konfirmasi admin.');
    }

    // --- PESANAN SAYA ---
    public function history()
    {
        $activeStatuses = ['pending', 'proses', 'dikirim', 'minta_batal', 'minta_refund'];
        $orders = Order::with('items.product')
            ->where('user_id', Auth::id())
            ->whereIn('status', $activeStatuses)
            ->latest()
            ->get();
        return view('customer.history', compact('orders'));
    }

    // --- RIWAYAT TRANSAKSI ---
    public function riwayat()
    {
        $finalStatuses = ['selesai', 'batal', 'refund'];
        $orders = Order::with('items.product')
            ->where('user_id', Auth::id())
            ->whereIn('status', $finalStatuses)
            ->latest()
            ->get();
        return view('customer.riwayat', compact('orders'));
    }

    public function requestCancel(Request $request, $id)
    {
        $request->validate(['cancel_reason' => 'required|string|max:500']);
        $order = Order::where('id', $id)->where('user_id', Auth::id())->firstOrFail();
        if (!in_array($order->status, ['pending', 'proses'])) {
            return redirect()->back()->with('error', 'Pesanan ini tidak bisa dibatalkan.');
        }
        $order->update([
            'previous_status' => $order->status,
            'status' => 'minta_batal',
            'cancel_reason' => $request->input('cancel_reason'),
        ]);
        ActivityLog::create([
            'user_id' => Auth::id(), 'action' => 'cancel_requested',
            'description' => 'Customer ' . Auth::user()->name . ' mengajukan pembatalan #' . $order->order_number,
            'model_type' => 'Order', 'model_id' => $order->id,
        ]);
        return redirect()->back()->with('success', 'Permintaan pembatalan dikirim. Menunggu konfirmasi admin.');
    }

    public function requestRefund(Request $request, $id)
    {
        $request->validate(['refund_reason' => 'required|string|max:500']);
        $order = Order::where('id', $id)->where('user_id', Auth::id())->firstOrFail();
        if ($order->status !== 'dikirim') {
            return redirect()->back()->with('error', 'Refund hanya untuk pesanan yang sudah dikirim.');
        }
        $order->update([
            'previous_status' => $order->status,
            'status' => 'minta_refund',
            'refund_reason' => $request->input('refund_reason'),
        ]);
        ActivityLog::create([
            'user_id' => Auth::id(), 'action' => 'refund_requested',
            'description' => 'Customer ' . Auth::user()->name . ' mengajukan refund #' . $order->order_number,
            'model_type' => 'Order', 'model_id' => $order->id,
        ]);
        return redirect()->back()->with('success', 'Permintaan refund dikirim. Menunggu konfirmasi admin.');
    }

    // --- STORE REVIEW ---
    public function storeReview(Request $request)
    {
        $request->validate([
            'order_id'   => 'required|exists:orders,id',
            'product_id' => 'required|exists:products,id',
            'rating'     => 'required|integer|min:1|max:5',
            'comment'    => 'nullable|string|max:1000',
            'images'     => 'nullable|array|max:5',
            'images.*'   => 'image|mimes:jpeg,png,jpg,webp|max:2048',
        ]);

        $order = Order::where('id', $request->order_id)
            ->where('user_id', Auth::id())
            ->where('status', 'selesai')
            ->firstOrFail();

        $exists = Review::where('user_id', Auth::id())
            ->where('product_id', $request->product_id)
            ->where('order_id', $request->order_id)
            ->exists();

        if ($exists) {
            return redirect()->back()->with('error', 'Kamu sudah memberikan review untuk produk ini.');
        }

        DB::transaction(function () use ($request) {
            $review = Review::create([
                'user_id'    => Auth::id(),
                'product_id' => $request->product_id,
                'order_id'   => $request->order_id,
                'rating'     => $request->rating,
                'comment'    => $request->comment,
            ]);

            if ($request->hasFile('images')) {
                foreach ($request->file('images') as $img) {
                    $path = $img->store('reviews', 'uploads');
                    ReviewImage::create([
                        'review_id' => $review->id,
                        'image'     => $path,
                    ]);
                }
            }
        });

        return redirect()->back()->with('success', '⭐ Review + foto berhasil dikirim! Terima kasih.');
    }
}
