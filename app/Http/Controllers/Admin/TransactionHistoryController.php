<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class TransactionHistoryController extends Controller
{
    public function index()
    {
        // Data Dummy agar tidak error
        $history = [
            [
                'id' => '99210',
                'name' => 'Budi Setiawan',
                'date' => '20 Okt 2023',
                'total' => '450.000',
                'status' => 'selesai'
            ],
            [
                'id' => '99211',
                'name' => 'Siti Aminah',
                'date' => '21 Okt 2023',
                'total' => '1.200.000',
                'status' => 'proses'
            ],
            [
                'id' => '99212',
                'name' => 'Rehan Pratama',
                'date' => '22 Okt 2023',
                'total' => '75.000',
                'status' => 'batal'
            ]
        ];

        // Pastikan variabel $history dikirim lewat compact
        return view('admin.transaksi', compact('history'));
    }
}