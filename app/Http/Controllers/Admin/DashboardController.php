<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index()
    {
        // Bayangkan ini data dari database
        $data = [
            'total_barang' => 120,
            'total_penjualan' => 'Rp 5.200.000',
            'pesanan_baru' => 8
        ];

        return view('admin.dashboard', compact('data'));
    }
}