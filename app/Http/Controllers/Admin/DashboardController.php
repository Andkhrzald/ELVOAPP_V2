<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Product;
use App\Models\User;
use App\Models\Review;
use App\Models\ActivityLog;
use Illuminate\Http\Request;
use Carbon\Carbon;

class DashboardController extends Controller
{
    public function index()
    {
        $totalRevenue = Order::whereIn('status', ['selesai', 'dikirim'])->sum('total_price');
        $activeOrders = Order::whereIn('status', ['pending', 'proses'])->count();
        $totalProducts = Product::count();
        $newCustomers = User::where('role', 'customer')
            ->where('created_at', '>=', now()->subDays(7))
            ->count();

        $chartData = [];
        $chartLabels = [];

        for ($i = 6; $i >= 0; $i--) {
            $date = now()->subDays($i);
            $chartLabels[] = $date->translatedFormat('D');

            $dailySales = Order::whereIn('status', ['selesai', 'dikirim', 'proses'])
                ->whereDate('created_at', $date->toDateString())
                ->sum('total_price');

            $chartData[] = $dailySales;
        }

        $activities = ActivityLog::with('user')
            ->latest()
            ->take(5)
            ->get();

        return view('admin.dashboard', compact(
            'totalRevenue',
            'activeOrders',
            'totalProducts',
            'newCustomers',
            'chartLabels',
            'chartData',
            'activities'
        ));
    }

    public function analytics()
    {
        return view('admin.analytics');
    }

    public function getAnalyticsData(Request $request)
    {
        try {
            $range = $request->get('range', '30');

            if ($range === 'custom') {
                $startDate = $request->get('start_date');
                $endDate = $request->get('end_date');
                if (!$startDate || !$endDate) {
                    return response()->json(['error' => 'Tanggal start dan end harus diisi'], 400);
                }
                $start = Carbon::parse($startDate)->startOfDay();
                $end = Carbon::parse($endDate)->endOfDay();
            } else {
                $days = match ((int) $range) {
                    7 => 7,
                    30 => 30,
                    90 => 90,
                    365 => 365,
                    default => 30,
                };
                $end = now()->endOfDay();
                $start = now()->subDays($days - 1)->startOfDay();
            }

            if ($start->gt($end)) {
                return response()->json(['error' => 'Tanggal start tidak boleh lebih besar dari end'], 400);
            }

            $diffDays = (int) $start->diffInDays($end) + 1;

            if ($diffDays <= 31) {
                $labelFormat = 'd M';
            } elseif ($diffDays <= 92) {
                $labelFormat = 'M d';
            } else {
                $labelFormat = 'M Y';
            }

            $revenueStatuses = ['selesai', 'dikirim', 'proses'];

            // ============ 1. Summary ============
            $totalRevenue = (int) Order::whereIn('status', $revenueStatuses)
                ->whereBetween('created_at', [$start, $end])->sum('total_price');

            $totalOrders = Order::whereBetween('created_at', [$start, $end])->count();
            $completedOrders = Order::where('status', 'selesai')
                ->whereBetween('created_at', [$start, $end])->count();
            $pendingOrders = Order::whereIn('status', ['pending', 'proses'])
                ->whereBetween('created_at', [$start, $end])->count();

            $aov = $totalOrders > 0 ? (int) round($totalRevenue / $totalOrders) : 0;

            $newCustomers = User::where('role', 'customer')
                ->whereBetween('created_at', [$start, $end])->count();

            $avgRating = Review::avg('rating');
            $lowStockCount = Product::where('stock', '<', 5)->where('is_active', true)->count();

            // ============ 2. Comparison with previous period ============
            $prevPeriodDays = $diffDays;
            $prevStart = (clone $start)->subDays($prevPeriodDays);
            $prevEnd = (clone $start)->subSecond();

            $prevRevenue = (int) Order::whereIn('status', $revenueStatuses)
                ->whereBetween('created_at', [$prevStart, $prevEnd])->sum('total_price');

            $prevOrders = Order::whereBetween('created_at', [$prevStart, $prevEnd])->count();
            $prevAov = $prevOrders > 0 ? (int) round($prevRevenue / $prevOrders) : 0;
            $prevCustomers = User::where('role', 'customer')
                ->whereBetween('created_at', [$prevStart, $prevEnd])->count();

            $calcGrowth = fn ($current, $previous) => $previous > 0 ? round((($current - $previous) / $previous) * 100, 1) : 0;

            // ============ 3. Revenue & Orders Trend ============
            $period = new \DatePeriod($start, new \DateInterval('P1D'), (clone $end)->addDay());

            $revenueByDate = Order::whereIn('status', $revenueStatuses)
                ->whereBetween('created_at', [$start, $end])
                ->selectRaw('DATE(created_at) as date, SUM(total_price) as revenue')
                ->groupBy('date')->pluck('revenue', 'date');

            $ordersByDate = Order::whereBetween('created_at', [$start, $end])
                ->selectRaw('DATE(created_at) as date, COUNT(*) as count')
                ->groupBy('date')->pluck('count', 'date');

            $orderCountByDate = $ordersByDate;

            $revenueTrendLabels = [];
            $revenueTrendData = [];
            $ordersTrendData = [];
            $aovTrendLabels = [];
            $aovTrendData = [];
            $customerTrendLabels = [];
            $customerTrendData = [];

            $customersByDate = User::where('role', 'customer')
                ->whereBetween('created_at', [$start, $end])
                ->selectRaw('DATE(created_at) as date, COUNT(*) as count')
                ->groupBy('date')->pluck('count', 'date');

            foreach ($period as $date) {
                $c = $date instanceof \Carbon\Carbon ? $date : \Carbon\Carbon::instance($date);
                $key = $c->format('Y-m-d');
                $fmt = $c->translatedFormat($labelFormat);
                $rev = (int) ($revenueByDate[$key] ?? 0);
                $ord = (int) ($ordersByDate[$key] ?? 0);

                $revenueTrendLabels[] = $fmt;
                $revenueTrendData[] = $rev;
                $ordersTrendData[] = $ord;
                $aovTrendLabels[] = $fmt;
                $aovTrendData[] = $ord > 0 ? (int) round($rev / $ord) : 0;
                $customerTrendLabels[] = $fmt;
                $customerTrendData[] = (int) ($customersByDate[$key] ?? 0);
            }

            // ============ 4. Top Products ============
            $topProducts = OrderItem::selectRaw('product_name, SUM(quantity) as total_qty, SUM(subtotal) as total_revenue')
                ->whereHas('order', fn ($q) => $q->whereIn('status', $revenueStatuses))
                ->whereBetween('created_at', [$start, $end])
                ->groupBy('product_name')->orderByDesc('total_revenue')->take(5)
                ->get()->map(fn ($item) => [
                    'name' => $item->product_name,
                    'revenue' => (int) $item->total_revenue,
                    'qty' => (int) $item->total_qty,
                    'percentage' => $totalRevenue > 0 ? round(($item->total_revenue / $totalRevenue) * 100, 1) : 0,
                ]);

            // ============ 5. Category Sales ============
            $categorySales = OrderItem::selectRaw('categories.name as category, SUM(order_items.subtotal) as total_revenue')
                ->join('products', 'order_items.product_id', '=', 'products.id')
                ->join('categories', 'products.category_id', '=', 'categories.id')
                ->whereHas('order', fn ($q) => $q->whereIn('status', $revenueStatuses))
                ->whereBetween('order_items.created_at', [$start, $end])
                ->groupBy('categories.name')
                ->get()->map(fn ($item) => [
                    'category' => $item->category,
                    'revenue' => (int) $item->total_revenue,
                    'percentage' => $totalRevenue > 0 ? round(($item->total_revenue / $totalRevenue) * 100, 1) : 0,
                ]);

            // ============ 6. Order Status Distribution ============
            $statusColors = [
                'pending' => '#f97316', 'proses' => '#8b5cf6', 'dikirim' => '#6366f1',
                'selesai' => '#22c55e', 'minta_batal' => '#f59e0b', 'batal' => '#ef4444',
                'minta_refund' => '#f59e0b', 'refund' => '#ec4899',
            ];
            $statusLabels = [
                'pending' => 'Pending', 'proses' => 'Diproses', 'dikirim' => 'Dikirim',
                'selesai' => 'Selesai', 'minta_batal' => 'Minta Batal', 'batal' => 'Batal',
                'minta_refund' => 'Minta Refund', 'refund' => 'Refund',
            ];

            $orderStatuses = Order::selectRaw('status, COUNT(*) as count')
                ->whereBetween('created_at', [$start, $end])
                ->groupBy('status')->get()->map(fn ($item) => [
                    'status' => $statusLabels[$item->status] ?? $item->status,
                    'count' => (int) $item->count,
                    'color' => $statusColors[$item->status] ?? '#6b7280',
                ])->sortByDesc('count')->values();

            // ============ 7. Payment Methods ============
            $paymentMethods = Order::selectRaw("COALESCE(NULLIF(payment_method, ''), 'Lainnya') as method, SUM(total_price) as total")
                ->whereIn('status', $revenueStatuses)
                ->whereBetween('created_at', [$start, $end])
                ->groupBy('method')->orderByDesc('total')
                ->get()->map(fn ($item) => [
                    'method' => $item->method,
                    'total' => (int) $item->total,
                    'percentage' => $totalRevenue > 0 ? round(($item->total / $totalRevenue) * 100, 1) : 0,
                ]);

            // ============ 8. Low Stock Products ============
            $lowStock = Product::where('stock', '<', 5)
                ->where('is_active', true)->orderBy('stock')
                ->get(['id', 'name', 'stock', 'image'])->toArray();

            // ============ 9. Rating Distribution ============
            $ratingDist = [];
            $ratingCounts = Review::selectRaw('rating, COUNT(*) as count')
                ->groupBy('rating')->orderBy('rating')->pluck('count', 'rating');

            for ($r = 5; $r >= 1; $r--) {
                $ratingDist[] = ['rating' => $r, 'count' => (int) ($ratingCounts[$r] ?? 0)];
            }

            return response()->json([
                'summary' => [
                    'totalRevenue' => $totalRevenue,
                    'totalOrders' => $totalOrders,
                    'aov' => $aov,
                    'newCustomers' => $newCustomers,
                    'completedOrders' => $completedOrders,
                    'pendingOrders' => $pendingOrders,
                    'avgRating' => $avgRating ? round($avgRating, 1) : 0,
                    'lowStockCount' => $lowStockCount,
                ],
                'comparison' => [
                    'prevRevenue' => $prevRevenue,
                    'revenueGrowth' => $calcGrowth($totalRevenue, $prevRevenue),
                    'prevOrders' => $prevOrders,
                    'orderGrowth' => $calcGrowth($totalOrders, $prevOrders),
                    'prevAov' => $prevAov,
                    'aovGrowth' => $calcGrowth($aov, $prevAov),
                    'prevCustomers' => $prevCustomers,
                    'customerGrowth' => $calcGrowth($newCustomers, $prevCustomers),
                ],
                'revenueTrend' => [
                    'labels' => $revenueTrendLabels,
                    'revenue' => $revenueTrendData,
                    'orders' => $ordersTrendData,
                ],
                'topProducts' => $topProducts,
                'categorySales' => $categorySales,
                'orderStatus' => $orderStatuses,
                'paymentMethods' => $paymentMethods,
                'aovTrend' => [
                    'labels' => $aovTrendLabels,
                    'data' => $aovTrendData,
                ],
                'customerTrend' => [
                    'labels' => $customerTrendLabels,
                    'data' => $customerTrendData,
                ],
                'lowStock' => $lowStock,
                'ratingDistribution' => $ratingDist,
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'error' => 'Server error: ' . $e->getMessage(),
                'file' => basename($e->getFile()),
                'line' => $e->getLine(),
            ], 500);
        }
    }
}