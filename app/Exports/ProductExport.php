<?php

namespace App\Exports;

use App\Models\Product;

class ProductExport
{
    public static function export()
    {
        $products = Product::with('category')->latest()->get();

        $rows = [
            ['KATALOG PRODUK ELVO', '', '', '', '', '', ''],
            ['', '', '', '', '', '', ''],
            ['SKU', 'Nama Produk', 'Kategori', 'Harga', 'Stok', 'Status', 'Terjual'],
        ];

        foreach ($products as $p) {
            $soldQty = $p->orderItems()->whereHas('order', fn($q) => $q->whereIn('status', ['selesai', 'dikirim']))->sum('quantity');
            $rows[] = [
                '#ELV-' . str_pad($p->id, 4, '0', STR_PAD_LEFT),
                $p->name,
                $p->category->name ?? '-',
                $p->price,
                $p->stock,
                $p->is_active ? 'Aktif' : 'Disembunyikan',
                $soldQty,
            ];
        }

        return ExportHelper::exportXlsx('Katalog_Produk_' . date('Ymd'),
            ['SKU', 'Nama Produk', 'Kategori', 'Harga', 'Stok', 'Status', 'Terjual'],
            $rows
        );
    }
}
