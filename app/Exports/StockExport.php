<?php

namespace App\Exports;

use App\Models\StockMutation;

class StockExport
{
    public static function export()
    {
        $mutations = StockMutation::with('product', 'user')->latest()->get();

        $typeLabels = ['in' => 'Masuk', 'out' => 'Keluar', 'adjustment' => 'Adjustment', 'order' => 'Pesanan', 'cancel' => 'Batal'];

        $rows = [
            ['RIWAYAT STOK ELVO', '', '', '', '', '', '', ''],
            ['', '', '', '', '', '', '', ''],
            ['Waktu', 'Produk', 'Tipe', 'Qty', 'Stok Awal', 'Stok Akhir', 'User', 'Keterangan'],
        ];

        foreach ($mutations as $m) {
            $rows[] = [
                $m->created_at->format('d/m/Y H:i'),
                $m->product->name ?? '-',
                $typeLabels[$m->type] ?? $m->type,
                ($m->type === 'in' || $m->type === 'cancel' ? '+' : '-') . $m->qty,
                $m->old_stock,
                $m->new_stock,
                $m->user->name ?? 'System',
                $m->note ?? '-',
            ];
        }

        return ExportHelper::exportXlsx('Riwayat_Stok_' . date('Ymd'),
            ['Waktu', 'Produk', 'Tipe', 'Qty', 'Stok Awal', 'Stok Akhir', 'User', 'Keterangan'],
            $rows
        );
    }
}
