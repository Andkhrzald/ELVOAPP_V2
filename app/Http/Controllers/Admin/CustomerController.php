<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class CustomerController extends Controller
{
    public function index(Request $request)
    {
        $query = User::where('role', '!=', 'admin')
            ->withCount('orders')
            ->withSum('orders as total_spent', 'total_price');

        // Search
        if ($request->filled('search')) {
            $s = $request->search;
            $query->where(function ($q) use ($s) {
                $q->where('name', 'like', "%{$s}%")
                  ->orWhere('email', 'like', "%{$s}%")
                  ->orWhere('phone', 'like', "%{$s}%");
            });
        }

        // Sort
        $sort = $request->get('sort', 'latest');
        $query = match($sort) {
            'name'       => $query->orderBy('name'),
            'orders'     => $query->orderByDesc('orders_count'),
            'spent'      => $query->orderByDesc('total_spent'),
            default      => $query->latest(),
        };

        $customers = $query->paginate(15);

        // Stats
        $totalCustomers = User::where('role', '!=', 'admin')->count();
        $activeCustomers = User::where('role', '!=', 'admin')
            ->whereHas('orders', fn($q) => $q->where('status', 'selesai'))
            ->count();
        $totalRevenue = Order::where('status', 'selesai')->sum('total_price');

        return view('admin.pelanggan', compact('customers', 'totalCustomers', 'activeCustomers', 'totalRevenue'));
    }

    public function show($id)
    {
        $customer = User::withCount('orders')
            ->withSum('orders as total_spent', 'total_price')
            ->findOrFail($id);

        $orders = Order::with('items')
            ->where('user_id', $id)
            ->latest()
            ->get();

        return view('admin.pelanggan-detail', compact('customer', 'orders'));
    }
}
