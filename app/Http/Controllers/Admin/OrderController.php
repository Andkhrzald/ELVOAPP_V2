<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class OrderController extends Controller
{
    public function index()
    {
        $order = (object) [
            'id' => 1,
            'invoice' => 'TRX-99211',
            'customer_name' => 'Siti Aminah',
            'total' => 1200000,
            'created_at' => now(),
        ];

        return view('admin.pesanan-masuk', compact('order'));
    }

    // TAMBAHKAN FUNGSI INI AGAR TOMBOL "PROSES" JALAN
    public function confirmShipping(Request $request, $id)
    {
        return redirect()->back()->with('success', 'Pesanan #' . $id . ' Berhasil dikirim dengan Resi: ' . $request->resi);
    }
}