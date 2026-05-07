<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\ActivityLog;
use Illuminate\Http\Request;

class OrderController extends Controller
{
    /**
     * Tampilkan halaman manajemen pesanan dengan filter status
     */
    public function index(Request $request)
    {
        $status = $request->query('status', 'pending');

        // Query real dari database — filter berdasarkan status
        $orders = Order::with(['user', 'items.product'])
            ->where('status', $status)
            ->latest()
            ->get();

        // Hitung jumlah per status untuk badge counter
        $statusCounts = [
            'pending'  => Order::where('status', 'pending')->count(),
            'proses'   => Order::where('status', 'proses')->count(),
            'dikirim'  => Order::where('status', 'dikirim')->count(),
            'selesai'  => Order::where('status', 'selesai')->count(),
            'batal'    => Order::where('status', 'batal')->count(),
        ];

        // Data riwayat (pesanan yang sudah dikirim/selesai) untuk statistik pendapatan
        $completedOrders = Order::whereIn('status', ['dikirim', 'selesai'])->get();
        $totalRevenue = $completedOrders->sum('total_price');

        return view('admin.pesanan-masuk', compact(
            'orders', 
            'status', 
            'statusCounts', 
            'totalRevenue'
        ));
    }

    /**
     * ACCEPT — Terima pesanan (pending → proses)
     * Admin mengkonfirmasi pesanan dan memulai proses packing
     */
    public function accept($id)
    {
        $order = Order::findOrFail($id);

        // Guard: hanya pending yang bisa di-accept
        if ($order->status !== 'pending') {
            return redirect()->back()->with('error', 'Pesanan ini tidak bisa dikonfirmasi karena statusnya bukan pending.');
        }

        $order->update(['status' => 'proses']);

        ActivityLog::create([
            'user_id'     => null,
            'action'      => 'order_confirmed',
            'description' => 'Pesanan #' . $order->order_number . ' dikonfirmasi & masuk proses packing',
            'model_type'  => 'Order',
            'model_id'    => $order->id,
        ]);

        return redirect()->back()->with('success', '✅ Pesanan #' . $order->order_number . ' berhasil dikonfirmasi. Status: PROSES');
    }

    /**
     * SHIP — Kirim pesanan (proses → dikirim)
     * Admin memasukkan nomor resi dan kurir pengiriman
     */
    public function ship(Request $request, $id)
    {
        $request->validate([
            'no_resi'         => 'required|string|max:50',
            'shipping_method' => 'nullable|string|max:100',
        ]);

        $order = Order::findOrFail($id);

        // Guard: hanya proses yang bisa di-ship
        if ($order->status !== 'proses') {
            return redirect()->back()->with('error', 'Pesanan ini tidak bisa dikirim karena statusnya bukan diproses.');
        }

        $updateData = [
            'status'  => 'dikirim',
            'no_resi' => $request->input('no_resi'),
        ];

        // Update shipping method jika diisi
        if ($request->filled('shipping_method')) {
            $updateData['shipping_method'] = $request->input('shipping_method');
        }

        $order->update($updateData);

        ActivityLog::create([
            'user_id'     => null,
            'action'      => 'order_shipped',
            'description' => 'Pesanan #' . $order->order_number . ' dikirim via ' . ($order->shipping_method ?? 'Kurir') . ' (Resi: ' . $request->input('no_resi') . ')',
            'model_type'  => 'Order',
            'model_id'    => $order->id,
        ]);

        return redirect()->back()->with('success', '🚚 Pesanan #' . $order->order_number . ' berhasil dikirim! Resi: ' . $request->input('no_resi'));
    }

    /**
     * COMPLETE — Tandai selesai (dikirim → selesai)
     * Pesanan sudah sampai ke pelanggan
     */
    public function complete($id)
    {
        $order = Order::findOrFail($id);

        // Guard: hanya dikirim yang bisa di-complete
        if ($order->status !== 'dikirim') {
            return redirect()->back()->with('error', 'Pesanan ini tidak bisa ditandai selesai karena belum dikirim.');
        }

        $order->update(['status' => 'selesai']);

        ActivityLog::create([
            'user_id'     => null,
            'action'      => 'order_completed',
            'description' => 'Pesanan #' . $order->order_number . ' telah selesai & diterima pelanggan',
            'model_type'  => 'Order',
            'model_id'    => $order->id,
        ]);

        return redirect()->back()->with('success', '🎉 Pesanan #' . $order->order_number . ' selesai! Pesanan diterima pelanggan.');
    }

    /**
     * CANCEL — Batalkan pesanan (any → batal)
     * Dengan alasan pembatalan
     */
    public function cancel(Request $request, $id)
    {
        $request->validate([
            'cancel_reason' => 'required|string|max:500',
        ]);

        $order = Order::findOrFail($id);

        // Guard: selesai tidak bisa dibatalkan
        if ($order->status === 'selesai') {
            return redirect()->back()->with('error', 'Pesanan yang sudah selesai tidak bisa dibatalkan.');
        }

        if ($order->status === 'batal') {
            return redirect()->back()->with('error', 'Pesanan ini sudah dibatalkan sebelumnya.');
        }

        $previousStatus = $order->status;
        $order->update([
            'status' => 'batal',
            'notes'  => 'Dibatalkan dari status ' . $previousStatus . '. Alasan: ' . $request->input('cancel_reason'),
        ]);

        ActivityLog::create([
            'user_id'     => null,
            'action'      => 'order_cancelled',
            'description' => 'Pesanan #' . $order->order_number . ' dibatalkan. Alasan: ' . $request->input('cancel_reason'),
            'model_type'  => 'Order',
            'model_id'    => $order->id,
        ]);

        return redirect()->back()->with('success', '❌ Pesanan #' . $order->order_number . ' dibatalkan.');
    }
}