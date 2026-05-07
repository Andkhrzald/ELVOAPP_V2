<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\Product;
use App\Models\User;
use App\Models\ActivityLog;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index()
    {
        // ============================================
        // STATISTIK CARD — Data Real dari Database
        // ============================================

        // Total Pendapatan (hanya dari order yang selesai/dikirim)
        $totalRevenue = Order::whereIn('status', ['selesai', 'dikirim'])->sum('total_price');

        // Pesanan Aktif (status pending + proses)
        $activeOrders = Order::whereIn('status', ['pending', 'proses'])->count();

        // Total Produk
        $totalProducts = Product::count();

        // Pelanggan Baru (7 hari terakhir)
        $newCustomers = User::where('role', 'customer')
            ->where('created_at', '>=', now()->subDays(7))
            ->count();

        // ============================================
        // CHART DATA — Penjualan 7 Hari Terakhir
        // ============================================
        $chartData = [];
        $chartLabels = [];

        for ($i = 6; $i >= 0; $i--) {
            $date = now()->subDays($i);
            $chartLabels[] = $date->translatedFormat('D'); // Sen, Sel, Rab, dll

            $dailySales = Order::whereIn('status', ['selesai', 'dikirim', 'proses'])
                ->whereDate('created_at', $date->toDateString())
                ->sum('total_price');

            $chartData[] = $dailySales;
        }

        // ============================================
        // AKTIVITAS TERKINI — 5 Log Terakhir
        // ============================================
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
}