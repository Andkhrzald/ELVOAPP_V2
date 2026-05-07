<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;

class TransactionHistoryController extends Controller
{
    public function index()
    {
        // Query real — semua order dengan relasi user, urutkan dari terbaru
        $history = Order::with('user')
            ->latest()
            ->get();

        // Statistik untuk card di atas tabel
        $totalTransactions = Order::count();
        $monthlyRevenue = Order::whereIn('status', ['selesai', 'dikirim'])
            ->whereMonth('created_at', now()->month)
            ->whereYear('created_at', now()->year)
            ->sum('total_price');
        $pendingCount = Order::whereIn('status', ['pending', 'proses'])->count();

        return view('admin.transaksi', compact(
            'history',
            'totalTransactions',
            'monthlyRevenue',
            'pendingCount'
        ));
    }
}