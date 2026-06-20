<?php

namespace App\Exports;

use App\Models\Order;

class TransactionExport
{
    public static function export()
    {
        $orders = Order::with('user', 'items')->latest()->get();

        $rows = [
            ['RIWAYAT TRANSAKSI ELVO', '', '', '', '', '', ''],
            ['', '', '', '', '', '', ''],
            ['Order #', 'Tanggal', 'Customer', 'Total', 'Status', 'Pembayaran', 'Item'],
        ];

        $statusLabels = [
            'pending' => 'Pending', 'proses' => 'Diproses', 'dikirim' => 'Dikirim',
            'selesai' => 'Selesai', 'minta_batal' => 'Minta Batal', 'batal' => 'Batal',
            'minta_refund' => 'Minta Refund', 'refund' => 'Refund',
        ];

        foreach ($orders as $o) {
            $rows[] = [
                $o->order_number,
                $o->created_at->format('d/m/Y H:i'),
                $o->user->name ?? 'Guest',
                $o->total_price,
                $statusLabels[$o->status] ?? $o->status,
                $o->payment_method ?? '-',
                $o->items->sum('quantity') . ' item',
            ];
        }

        return ExportHelper::exportXlsx('Riwayat_Transaksi_' . date('Ymd'),
            ['Order #', 'Tanggal', 'Customer', 'Total', 'Status', 'Pembayaran', 'Item'],
            $rows
        );
    }
}
