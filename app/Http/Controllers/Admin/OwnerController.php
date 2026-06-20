<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\ActivityLog;
use App\Models\Order;
use App\Models\Product;
use App\Models\OrderItem;
use App\Models\Setting;
use App\Models\StockMutation;
use App\Models\Review;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Carbon\Carbon;

class OwnerController extends Controller
{
    public function index()
    {
        $totalAdmins  = User::where('role', 'admin')->count();
        $totalOwners  = User::where('role', 'owner')->count();
        $totalOrders  = Order::count();
        $totalRevenue = Order::whereIn('status', ['selesai', 'dikirim'])->sum('total_price');
        $totalCustomers = User::where('role', 'customer')->count();
        $totalProducts  = Product::count();
        $lowStockCount  = Product::where('stock', '<', 5)->where('is_active', true)->count();

        // Revenue chart (7 hari)
        $revenueChart = [];
        $orderChart = [];
        $chartLabels = [];
        for ($i = 6; $i >= 0; $i--) {
            $date = now()->subDays($i);
            $chartLabels[] = $date->translatedFormat('D');
            $revenueChart[] = (int) Order::whereIn('status', ['selesai', 'dikirim', 'proses'])
                ->whereDate('created_at', $date->toDateString())
                ->sum('total_price');
            $orderChart[] = Order::whereDate('created_at', $date->toDateString())->count();
        }

        // Order status distribution
        $statusCounts = Order::selectRaw("
            SUM(CASE WHEN status IN ('pending','proses') THEN 1 ELSE 0 END) as active,
            SUM(CASE WHEN status = 'selesai' THEN 1 ELSE 0 END) as completed,
            SUM(CASE WHEN status = 'dikirim' THEN 1 ELSE 0 END) as shipped,
            SUM(CASE WHEN status = 'batal' THEN 1 ELSE 0 END) as cancelled
        ")->first();

        // Low stock products
        $lowStockProducts = Product::where('stock', '<', 5)->where('is_active', true)
            ->orderBy('stock')->limit(6)->get(['id', 'name', 'stock']);

        // Revenue by period (bulan ini vs bulan lalu)
        $revenueThisMonth = (int) Order::whereIn('status', ['selesai', 'dikirim'])
            ->whereMonth('created_at', now()->month)
            ->whereYear('created_at', now()->year)
            ->sum('total_price');
        $revenueLastMonth = (int) Order::whereIn('status', ['selesai', 'dikirim'])
            ->whereMonth('created_at', now()->subMonth()->month)
            ->whereYear('created_at', now()->subMonth()->year)
            ->sum('total_price');
        $revenueGrowth = $revenueLastMonth > 0
            ? round((($revenueThisMonth - $revenueLastMonth) / $revenueLastMonth) * 100, 1)
            : 0;

        $activities = ActivityLog::with('user')->latest()->take(15)->get();

        return view('admin.owner.dashboard', [
            'totalAdmins'      => $totalAdmins,
            'totalOwners'      => $totalOwners,
            'totalOrders'      => $totalOrders,
            'totalRevenue'     => $totalRevenue,
            'totalCustomers'   => $totalCustomers,
            'totalProducts'    => $totalProducts,
            'lowStockCount'    => $lowStockCount,
            'chartLabels'      => $chartLabels,
            'revenueChart'     => $revenueChart,
            'orderChart'       => $orderChart,
            'activeOrders'     => (int) ($statusCounts->active ?? 0),
            'completedOrders'  => (int) ($statusCounts->completed ?? 0),
            'shippedOrders'    => (int) ($statusCounts->shipped ?? 0),
            'cancelledOrders'  => (int) ($statusCounts->cancelled ?? 0),
            'lowStockProducts' => $lowStockProducts,
            'revenueThisMonth' => $revenueThisMonth,
            'revenueLastMonth' => $revenueLastMonth,
            'revenueGrowth'    => $revenueGrowth,
            'activities'       => $activities,
        ]);
    }

    public function manageAdmins()
    {
        $admins = User::whereIn('role', ['admin', 'owner'])->latest()->paginate(10);
        return view('admin.owner.manage-admins', compact('admins'));
    }

    public function storeAdmin(Request $request)
    {
        $request->validate([
            'name'     => 'required|string|max:255',
            'email'    => 'required|email|unique:users,email',
            'password' => 'required|min:8',
            'role'     => 'required|in:admin,owner',
        ]);

        User::create([
            'name'     => $request->name,
            'email'    => $request->email,
            'password' => Hash::make($request->password),
            'role'     => $request->role,
            'phone'    => $request->phone ?? '',
            'address'  => $request->address ?? '',
        ]);

        ActivityLog::create([
            'user_id'     => auth()->id(),
            'action'      => 'admin_created',
            'description' => 'Owner membuat akun ' . $request->role . ': ' . $request->name . ' (' . $request->email . ')',
            'model_type'  => 'User',
            'model_id'    => User::latest()->first()->id,
        ]);

        return redirect()->route('admin.owner.manage-admins')
            ->with('success', 'Akun ' . $request->role . ' berhasil dibuat!');
    }

    public function destroyAdmin($id)
    {
        $user = User::findOrFail($id);

        if ($user->id === auth()->id()) {
            return redirect()->back()->with('error', 'Tidak bisa menghapus akun sendiri.');
        }

        $name  = $user->name;
        $email = $user->email;
        $role  = $user->role;
        $user->delete();

        ActivityLog::create([
            'user_id'     => auth()->id(),
            'action'      => 'admin_deleted',
            'description' => 'Owner menghapus akun ' . $role . ': ' . $name . ' (' . $email . ')',
            'model_type'  => 'User',
        ]);

        return redirect()->route('admin.owner.manage-admins')
            ->with('success', 'Akun ' . $name . ' berhasil dihapus.');
    }

    public function auditLog()
    {
        $activities = ActivityLog::with('user')->latest()->paginate(50);
        return view('admin.owner.audit-log', compact('activities'));
    }

    // ============ PENGATURAN TOKO ============

    public function settings()
    {
        $settings = Setting::getGroup('store');
        return view('admin.owner.settings', compact('settings'));
    }

    public function updateSettings(Request $request)
    {
        $request->validate([
            'store_name'     => 'required|string|max:255',
            'store_address'  => 'required|string',
            'store_phone'    => 'required|string|max:20',
            'store_email'    => 'required|email|max:255',
            'shipping_cost'  => 'required|numeric|min:0',
            'tax_rate'       => 'required|numeric|min:0|max:100',
            'payment_methods'=> 'required|string',
        ]);

        foreach ($request->only(['store_name', 'store_address', 'store_phone', 'store_email', 'shipping_cost', 'tax_rate', 'payment_methods']) as $key => $value) {
            Setting::setValue($key, $value, 'store');
        }

        ActivityLog::create([
            'user_id'     => auth()->id(),
            'action'      => 'settings_updated',
            'description' => 'Owner memperbarui pengaturan toko',
            'model_type'  => 'Setting',
        ]);

        return redirect()->route('admin.owner.settings')->with('success', 'Pengaturan toko berhasil disimpan!');
    }

    // ============ LAPORAN KEUANGAN ============

    public function financialReports(Request $request)
    {
        $range = $request->get('range', '30');
        $startDate = $request->get('start_date');
        $endDate = $request->get('end_date');

        if ($range === 'custom' && $startDate && $endDate) {
            $start = Carbon::parse($startDate)->startOfDay();
            $end = Carbon::parse($endDate)->endOfDay();
        } else {
            $days = match ((int) $range) { 7 => 7, 30 => 30, 90 => 90, 365 => 365, default => 30 };
            $end = now()->endOfDay();
            $start = now()->subDays($days - 1)->startOfDay();
        }

        $diffDays = (int) $start->diffInDays($end) + 1;
        $labelFormat = $diffDays <= 31 ? 'd M' : ($diffDays <= 92 ? 'M d' : 'M Y');
        $revenueStatuses = ['selesai', 'dikirim', 'proses'];

        // Summary
        $totalRevenue = (int) Order::whereIn('status', $revenueStatuses)
            ->whereBetween('created_at', [$start, $end])->sum('total_price');
        $totalOrders = Order::whereBetween('created_at', [$start, $end])->count();
        $completedOrders = Order::where('status', 'selesai')
            ->whereBetween('created_at', [$start, $end])->count();
        $aov = $totalOrders > 0 ? (int) round($totalRevenue / $totalOrders) : 0;

        // Previous period comparison
        $prevStart = (clone $start)->subDays($diffDays);
        $prevEnd = (clone $start)->subSecond();
        $prevRevenue = (int) Order::whereIn('status', $revenueStatuses)
            ->whereBetween('created_at', [$prevStart, $prevEnd])->sum('total_price');
        $prevOrders = Order::whereBetween('created_at', [$prevStart, $prevEnd])->count();
        $prevAov = $prevOrders > 0 ? (int) round($prevRevenue / $prevOrders) : 0;

        $revGrowth = $prevRevenue > 0 ? round((($totalRevenue - $prevRevenue) / $prevRevenue) * 100, 1) : 0;
        $ordGrowth = $prevOrders > 0 ? round((($totalOrders - $prevOrders) / $prevOrders) * 100, 1) : 0;
        $aovGrowth = $prevAov > 0 ? round((($aov - $prevAov) / $prevAov) * 100, 1) : 0;

        // Revenue by date
        $period = new \DatePeriod($start, new \DateInterval('P1D'), (clone $end)->addDay());
        $revenueByDate = Order::whereIn('status', $revenueStatuses)
            ->whereBetween('created_at', [$start, $end])
            ->selectRaw('DATE(created_at) as date, SUM(total_price) as revenue')
            ->groupBy('date')->pluck('revenue', 'date');
        $ordersByDate = Order::whereBetween('created_at', [$start, $end])
            ->selectRaw('DATE(created_at) as date, COUNT(*) as count')
            ->groupBy('date')->pluck('count', 'date');

        $chartLabels = [];
        $chartRevenue = [];
        $chartOrders = [];
        foreach ($period as $date) {
            $c = $date instanceof Carbon ? $date : Carbon::instance($date);
            $key = $c->format('Y-m-d');
            $chartLabels[] = $c->translatedFormat($labelFormat);
            $chartRevenue[] = (int) ($revenueByDate[$key] ?? 0);
            $chartOrders[] = (int) ($ordersByDate[$key] ?? 0);
        }

        // Order status distribution
        $orderStatuses = Order::selectRaw('status, COUNT(*) as count')
            ->whereBetween('created_at', [$start, $end])
            ->groupBy('status')->get();

        // Payment methods
        $paymentMethods = Order::selectRaw("COALESCE(NULLIF(payment_method, ''), 'Lainnya') as method, SUM(total_price) as total")
            ->whereIn('status', $revenueStatuses)
            ->whereBetween('created_at', [$start, $end])
            ->groupBy('method')->orderByDesc('total')->get();

        // Revenue by category
        $categoryRevenue = OrderItem::selectRaw('categories.name as category, SUM(order_items.subtotal) as total')
            ->join('products', 'order_items.product_id', '=', 'products.id')
            ->join('categories', 'products.category_id', '=', 'categories.id')
            ->whereHas('order', fn($q) => $q->whereIn('status', $revenueStatuses))
            ->whereBetween('order_items.created_at', [$start, $end])
            ->groupBy('categories.name')->orderByDesc('total')->get();

        return view('admin.owner.financial-reports', compact(
            'totalRevenue', 'totalOrders', 'completedOrders', 'aov',
            'prevRevenue', 'prevOrders', 'prevAov',
            'revGrowth', 'ordGrowth', 'aovGrowth',
            'chartLabels', 'chartRevenue', 'chartOrders',
            'orderStatuses', 'paymentMethods', 'categoryRevenue',
            'start', 'end', 'range',
        ));
    }

    // ============ LAPORAN PRODUK ============

    public function productReports(Request $request)
    {
        $range = $request->get('range', '30');
        $startDate = $request->get('start_date');
        $endDate = $request->get('end_date');

        if ($range === 'custom' && $startDate && $endDate) {
            $start = Carbon::parse($startDate)->startOfDay();
            $end = Carbon::parse($endDate)->endOfDay();
        } else {
            $days = match ((int) $range) { 7 => 7, 30 => 30, 90 => 90, 365 => 365, default => 30 };
            $end = now()->endOfDay();
            $start = now()->subDays($days - 1)->startOfDay();
        }

        $revenueStatuses = ['selesai', 'dikirim', 'proses'];
        $totalRevenue = (int) Order::whereIn('status', $revenueStatuses)
            ->whereBetween('created_at', [$start, $end])->sum('total_price');

        // Best sellers
        $bestSellers = OrderItem::selectRaw('
                product_name, SUM(quantity) as total_qty,
                SUM(subtotal) as total_revenue, COUNT(DISTINCT order_id) as total_orders
            ')
            ->whereHas('order', fn($q) => $q->whereIn('status', $revenueStatuses))
            ->whereBetween('created_at', [$start, $end])
            ->groupBy('product_name')->orderByDesc('total_qty')->limit(15)->get()
            ->map(fn($i) => [
                'name'     => $i->product_name,
                'qty'      => (int) $i->total_qty,
                'revenue'  => (int) $i->total_revenue,
                'orders'   => (int) $i->total_orders,
                'pct'      => $totalRevenue > 0 ? round(($i->total_revenue / $totalRevenue) * 100, 1) : 0,
            ]);

        // Low stock
        $lowStock = Product::where('stock', '<', 5)->where('is_active', true)
            ->orderBy('stock')->get(['id', 'name', 'stock', 'price', 'image']);

        // Top categories
        $categoryStats = OrderItem::selectRaw('
                categories.name, SUM(order_items.quantity) as total_qty,
                SUM(order_items.subtotal) as total_revenue
            ')
            ->join('products', 'order_items.product_id', '=', 'products.id')
            ->join('categories', 'products.category_id', '=', 'categories.id')
            ->whereHas('order', fn($q) => $q->whereIn('status', $revenueStatuses))
            ->whereBetween('order_items.created_at', [$start, $end])
            ->groupBy('categories.name')->orderByDesc('total_revenue')->get();

        return view('admin.owner.product-reports', compact(
            'bestSellers', 'lowStock', 'categoryStats', 'totalRevenue',
            'start', 'end', 'range',
        ));
    }

    // ============ RIWAYAT STOK ============

    public function stockHistory(Request $request)
    {
        $query = StockMutation::with('product', 'user');

        if ($request->filled('product_id')) {
            $query->where('product_id', $request->product_id);
        }
        if ($request->filled('type')) {
            $query->where('type', $request->type);
        }
        if ($request->filled('date_from')) {
            $query->whereDate('created_at', '>=', $request->date_from);
        }
        if ($request->filled('date_to')) {
            $query->whereDate('created_at', '<=', $request->date_to);
        }

        $mutations = $query->latest()->paginate(30)->withQueryString();
        $products = Product::orderBy('name')->get(['id', 'name']);

        return view('admin.owner.stock-history', compact('mutations', 'products'));
    }
}
