<?php

namespace App\Exports;

use App\Models\User;
use App\Models\Order;

class CustomerExport
{
    public static function export()
    {
        $customers = User::where('role', 'customer')->latest()->get();

        $rows = [
            ['DATA PELANGGAN ELVO', '', '', '', '', ''],
            ['', '', '', '', '', ''],
            ['Nama', 'Email', 'No. HP', 'Total Order', 'Total Belanja', 'Bergabung'],
        ];

        foreach ($customers as $c) {
            $totalOrders = Order::where('user_id', $c->id)->count();
            $totalSpent = (int) Order::where('user_id', $c->id)
                ->whereIn('status', ['selesai', 'dikirim'])
                ->sum('total_price');

            $rows[] = [
                $c->name,
                $c->email,
                $c->phone ?? '-',
                $totalOrders,
                $totalSpent,
                $c->created_at->format('d/m/Y'),
            ];
        }

        return ExportHelper::exportXlsx('Data_Pelanggan_' . date('Ymd'),
            ['Nama', 'Email', 'No. HP', 'Total Order', 'Total Belanja', 'Bergabung'],
            $rows
        );
    }
}
