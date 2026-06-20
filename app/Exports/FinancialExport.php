<?php

namespace App\Exports;

use App\Models\Order;
use App\Models\OrderItem;
use Carbon\Carbon;
use Illuminate\Http\Request;

class FinancialExport
{
    public static function export(Request $request)
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

        $totalRevenue = (int) Order::whereIn('status', $revenueStatuses)->whereBetween('created_at', [$start, $end])->sum('total_price');
        $totalOrders = Order::whereBetween('created_at', [$start, $end])->count();
        $completedOrders = Order::where('status', 'selesai')->whereBetween('created_at', [$start, $end])->count();
        $aov = $totalOrders > 0 ? round($totalRevenue / $totalOrders) : 0;

        // Daily breakdown
        $period = new \DatePeriod($start, new \DateInterval('P1D'), (clone $end)->addDay());
        $revenueByDate = Order::whereIn('status', $revenueStatuses)
            ->whereBetween('created_at', [$start, $end])
            ->selectRaw('DATE(created_at) as date, SUM(total_price) as revenue, COUNT(*) as orders')
            ->groupBy('date')->pluck('revenue', 'date');
        $ordersByDate = Order::whereBetween('created_at', [$start, $end])
            ->selectRaw('DATE(created_at) as date, COUNT(*) as count')
            ->groupBy('date')->pluck('count', 'date');

        // Top products
        $topProducts = OrderItem::selectRaw('product_name, SUM(quantity) as total_qty, SUM(subtotal) as total_revenue')
            ->whereHas('order', fn($q) => $q->whereIn('status', $revenueStatuses))
            ->whereBetween('created_at', [$start, $end])
            ->groupBy('product_name')->orderByDesc('total_revenue')->limit(10)->get();

        $rows = [
            ['LAPORAN KEUANGAN', $start->format('d/m/Y') . ' - ' . $end->format('d/m/Y'), '', ''],
            ['', '', '', ''],
            ['RINGKASAN', '', '', ''],
            ['Total Revenue', 'Rp ' . number_format($totalRevenue, 0, ',', '.'), '', ''],
            ['Total Pesanan', $totalOrders, '', ''],
            ['Pesanan Selesai', $completedOrders, '', ''],
            ['AOV', 'Rp ' . number_format($aov, 0, ',', '.'), '', ''],
            ['', '', '', ''],
            ['REVENUE HARIAN', '', '', ''],
            ['Tanggal', 'Revenue', 'Pesanan', ''],
        ];

        foreach ($period as $date) {
            $c = $date instanceof Carbon ? $date : Carbon::instance($date);
            $key = $c->format('Y-m-d');
            $rows[] = [
                $c->format('d/m/Y'),
                $revenueByDate[$key] ?? 0,
                $ordersByDate[$key] ?? 0,
                '',
            ];
        }

        $rows[] = ['', '', '', ''];
        $rows[] = ['TOP 10 PRODUK', '', '', ''];
        $rows[] = ['Produk', 'Terjual', 'Revenue', ''];

        foreach ($topProducts as $p) {
            $rows[] = [$p->product_name, (int) $p->total_qty, (int) $p->total_revenue, ''];
        }

        $filename = 'Laporan_Keuangan_' . $start->format('Ymd') . '_' . $end->format('Ymd');

        return ExportHelper::exportXlsx($filename,
            ['Laporan Keuangan', '', '', ''],
            $rows
        );
    }
}
