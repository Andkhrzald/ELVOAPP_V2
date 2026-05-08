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
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;

class ShopController extends Controller
{
    public function index(Request $request)
    {
        $query = Product::with(['category', 'reviews'])->where('stock', '>', 0);

        // Filter by category
        if ($request->filled('category')) {
            $query->whereHas('category', fn($q) => $q->where('slug', $request->category));
        }

        // Search by name
        if ($request->filled('search')) {
            $query->where('name', 'like', '%' . $request->search . '%');
        }

        // Sort
        $sort = $request->get('sort', 'latest');
        $query = match($sort) {
            'price_low'  => $query->orderBy('price', 'asc'),
            'price_high' => $query->orderBy('price', 'desc'),
            'name'       => $query->orderBy('name', 'asc'),
            default      => $query->latest(),
        };

        $products = $query->get();
        $categories = \App\Models\Category::orderBy('name')->get();

        return view('customer.shop', compact('products', 'categories'));
    }

    public function checkout()
    {
        return view('customer.checkout');
    }

    /**
     * PROSES CHECKOUT — Simpan Order ke Database
     */
    public function processCheckout(Request $request)
    {
        // 1. Validasi
        $request->validate([
            'name'           => 'required|string|max:255',
            'phone'          => 'required|string|max:20',
            'address'        => 'required|string|max:1000',
            'payment_method' => 'required|string',
            'cart'           => 'required|string', // JSON string dari localStorage
        ]);

        // 2. Decode cart
        $cartItems = json_decode($request->input('cart'), true);
        if (empty($cartItems)) {
            return redirect()->back()->with('error', 'Keranjang belanja kosong.');
        }

        // 3. Hitung total & validasi stok
        $totalPrice = 0;
        $validatedItems = [];

        foreach ($cartItems as $item) {
            $product = Product::find($item['id']);
            if (!$product || $product->stock < $item['qty']) {
                return redirect()->back()->with('error', 'Stok ' . ($product->name ?? 'produk') . ' tidak cukup.');
            }
            $subtotal = $product->price * $item['qty'];
            $totalPrice += $subtotal;
            $validatedItems[] = [
                'product' => $product,
                'qty'     => $item['qty'],
                'subtotal' => $subtotal,
            ];
        }

        $shippingCost = 20000; // Ongkir flat
        $grandTotal = $totalPrice + $shippingCost;

        // 4. Generate order number: INV-YYYYMMDD-XXX
        $today = now()->format('Ymd');
        $lastOrder = Order::where('order_number', 'like', "INV-{$today}-%")->orderBy('id', 'desc')->first();
        $seq = $lastOrder ? (intval(substr($lastOrder->order_number, -3)) + 1) : 1;
        $orderNumber = "INV-{$today}-" . str_pad($seq, 3, '0', STR_PAD_LEFT);

        // 5. Update user info jika login
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

        // 6. Simpan ke database (transaction untuk keamanan)
        DB::transaction(function () use ($orderNumber, $userId, $grandTotal, $shippingCost, $request, $validatedItems, &$order) {
            // Buat Order
            $order = Order::create([
                'user_id'         => $userId,
                'order_number'    => $orderNumber,
                'total_price'     => $grandTotal,
                'status'          => 'pending',
                'payment_method'  => $request->input('payment_method'),
                'shipping_method' => 'Standard Shipping',
                'shipping_cost'   => $shippingCost,
            ]);

            // Buat OrderItems + kurangi stok
            foreach ($validatedItems as $item) {
                OrderItem::create([
                    'order_id'     => $order->id,
                    'product_id'   => $item['product']->id,
                    'product_name' => $item['product']->name,
                    'quantity'     => $item['qty'],
                    'price'        => $item['product']->price,
                    'subtotal'     => $item['subtotal'],
                ]);
                $item['product']->decrement('stock', $item['qty']);
            }

            // Activity Log
            ActivityLog::create([
                'user_id'     => $userId,
                'action'      => 'order_created',
                'description' => 'Pesanan baru #' . $orderNumber . ' dari ' . ($request->input('name')),
                'model_type'  => 'Order',
                'model_id'    => $order->id,
            ]);
        });

        // 7. Redirect ke success dengan order number
        return redirect()->route('checkout.success')->with('order_number', $orderNumber);
    }

    public function success()
    {
        return view('customer.success');
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
            if (Auth::user()->role === 'admin') {
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

    // --- PESANAN SAYA (hanya pesanan aktif) ---
    public function history()
    {
        $activeStatuses = ['pending', 'proses', 'dikirim', 'minta_batal', 'minta_refund'];
        $orders = Order::with('items')
            ->where('user_id', Auth::id())
            ->whereIn('status', $activeStatuses)
            ->latest()
            ->get();
        return view('customer.history', compact('orders'));
    }

    // --- RIWAYAT TRANSAKSI (pesanan final: selesai, batal, refund) ---
    public function riwayat()
    {
        $finalStatuses = ['selesai', 'batal', 'refund'];
        $orders = Order::with('items')
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
        ]);

        // Pastikan order milik user & status selesai
        $order = Order::where('id', $request->order_id)
            ->where('user_id', Auth::id())
            ->where('status', 'selesai')
            ->firstOrFail();

        // Cek sudah pernah review belum
        $exists = \App\Models\Review::where('user_id', Auth::id())
            ->where('product_id', $request->product_id)
            ->where('order_id', $request->order_id)
            ->exists();

        if ($exists) {
            return redirect()->back()->with('error', 'Kamu sudah memberikan review untuk produk ini.');
        }

        \App\Models\Review::create([
            'user_id'    => Auth::id(),
            'product_id' => $request->product_id,
            'order_id'   => $request->order_id,
            'rating'     => $request->rating,
            'comment'    => $request->comment,
        ]);

        return redirect()->back()->with('success', '⭐ Review berhasil dikirim! Terima kasih.');
    }
}