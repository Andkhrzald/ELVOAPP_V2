<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\ActivityLog;
use Illuminate\Http\Request;

class OrderController extends Controller
{
    public function index(Request $request)
    {
        $status = $request->query('status', 'pending');
        $search = $request->query('search');

        $query = Order::with(['user', 'items.product'])
            ->where('status', $status);

        // Search by order number, name, or phone
        if ($search) {
            $query->where(function($q) use ($search) {
                $q->where('order_number', 'like', '%' . $search . '%')
                  ->orWhereHas('user', function($qu) use ($search) {
                      $qu->where('name', 'like', '%' . $search . '%')
                         ->orWhere('phone', 'like', '%' . $search . '%');
                  });
            });
        }

        $orders = $query->latest()->get();

        $statusCounts = [
            'pending'       => Order::where('status', 'pending')->count(),
            'proses'        => Order::where('status', 'proses')->count(),
            'dikirim'       => Order::where('status', 'dikirim')->count(),
            'selesai'       => Order::where('status', 'selesai')->count(),
            'minta_batal'   => Order::where('status', 'minta_batal')->count(),
            'batal'         => Order::where('status', 'batal')->count(),
            'minta_refund'  => Order::where('status', 'minta_refund')->count(),
            'refund'        => Order::where('status', 'refund')->count(),
        ];

        // Statistik relevan (bukan total pendapatan)
        $totalOrders = Order::count();
        $needAction = $statusCounts['pending'] + $statusCounts['minta_batal'] + $statusCounts['minta_refund'];

        return view('admin.pesanan-masuk', compact('orders', 'status', 'statusCounts', 'totalOrders', 'needAction'));
    }

    // ── ACCEPT: pending → proses ──
    public function accept($id)
    {
        $order = Order::findOrFail($id);
        if ($order->status !== 'pending') {
            return redirect()->back()->with('error', 'Pesanan ini tidak bisa dikonfirmasi.');
        }
        $order->update(['status' => 'proses']);
        ActivityLog::create([
            'action' => 'order_confirmed',
            'description' => 'Pesanan #' . $order->order_number . ' dikonfirmasi → proses packing',
            'model_type' => 'Order', 'model_id' => $order->id,
        ]);
        return redirect()->back()->with('success', '✅ Pesanan #' . $order->order_number . ' dikonfirmasi.');
    }

    // ── SHIP: proses → dikirim ──
    public function ship(Request $request, $id)
    {
        $request->validate(['no_resi' => 'required|string|max:50']);
        $order = Order::findOrFail($id);
        if ($order->status !== 'proses') {
            return redirect()->back()->with('error', 'Pesanan ini tidak bisa dikirim.');
        }
        $order->update([
            'status' => 'dikirim',
            'no_resi' => $request->input('no_resi'),
            'shipping_method' => $request->input('shipping_method', $order->shipping_method),
        ]);
        ActivityLog::create([
            'action' => 'order_shipped',
            'description' => 'Pesanan #' . $order->order_number . ' dikirim (Resi: ' . $request->input('no_resi') . ')',
            'model_type' => 'Order', 'model_id' => $order->id,
        ]);
        return redirect()->back()->with('success', '🚚 Pesanan #' . $order->order_number . ' dikirim! Resi: ' . $request->input('no_resi'));
    }

    // ── COMPLETE: dikirim → selesai ──
    public function complete($id)
    {
        $order = Order::findOrFail($id);
        if ($order->status !== 'dikirim') {
            return redirect()->back()->with('error', 'Pesanan belum dikirim.');
        }
        $order->update(['status' => 'selesai']);
        ActivityLog::create([
            'action' => 'order_completed',
            'description' => 'Pesanan #' . $order->order_number . ' selesai & diterima pelanggan',
            'model_type' => 'Order', 'model_id' => $order->id,
        ]);
        return redirect()->back()->with('success', '🎉 Pesanan #' . $order->order_number . ' selesai!');
    }

    // ── CONFIRM CANCEL: minta_batal → batal ──
    public function confirmCancel($id)
    {
        $order = Order::findOrFail($id);
        if ($order->status !== 'minta_batal') {
            return redirect()->back()->with('error', 'Pesanan ini tidak dalam status permintaan batal.');
        }
        $order->update(['status' => 'batal']);
        ActivityLog::create([
            'action' => 'cancel_confirmed',
            'description' => 'Pembatalan pesanan #' . $order->order_number . ' dikonfirmasi admin. Alasan customer: ' . $order->cancel_reason,
            'model_type' => 'Order', 'model_id' => $order->id,
        ]);
        return redirect()->back()->with('success', '✅ Pembatalan #' . $order->order_number . ' dikonfirmasi.');
    }

    // ── REJECT CANCEL: minta_batal → rollback previous_status ──
    public function rejectCancel($id)
    {
        $order = Order::findOrFail($id);
        if ($order->status !== 'minta_batal') {
            return redirect()->back()->with('error', 'Pesanan ini tidak dalam status permintaan batal.');
        }
        $rollback = $order->previous_status ?? 'pending';
        $order->update(['status' => $rollback, 'cancel_reason' => null, 'previous_status' => null]);
        ActivityLog::create([
            'action' => 'cancel_rejected',
            'description' => 'Pembatalan pesanan #' . $order->order_number . ' ditolak admin. Status dikembalikan ke ' . $rollback,
            'model_type' => 'Order', 'model_id' => $order->id,
        ]);
        return redirect()->back()->with('success', '❌ Pembatalan #' . $order->order_number . ' ditolak. Status kembali ke ' . ucfirst($rollback));
    }

    // ── CONFIRM REFUND: minta_refund → refund ──
    public function confirmRefund($id)
    {
        $order = Order::findOrFail($id);
        if ($order->status !== 'minta_refund') {
            return redirect()->back()->with('error', 'Pesanan ini tidak dalam status permintaan refund.');
        }
        $order->update(['status' => 'refund']);
        ActivityLog::create([
            'action' => 'refund_confirmed',
            'description' => 'Refund pesanan #' . $order->order_number . ' dikonfirmasi. Alasan customer: ' . $order->refund_reason,
            'model_type' => 'Order', 'model_id' => $order->id,
        ]);
        return redirect()->back()->with('success', '💰 Refund #' . $order->order_number . ' dikonfirmasi.');
    }

    // ── REJECT REFUND: minta_refund → rollback ke dikirim ──
    public function rejectRefund($id)
    {
        $order = Order::findOrFail($id);
        if ($order->status !== 'minta_refund') {
            return redirect()->back()->with('error', 'Pesanan ini tidak dalam status permintaan refund.');
        }
        $order->update(['status' => 'dikirim', 'refund_reason' => null, 'previous_status' => null]);
        ActivityLog::create([
            'action' => 'refund_rejected',
            'description' => 'Refund pesanan #' . $order->order_number . ' ditolak admin. Status kembali ke Dikirim.',
            'model_type' => 'Order', 'model_id' => $order->id,
        ]);
        return redirect()->back()->with('success', '❌ Refund #' . $order->order_number . ' ditolak. Status kembali ke Dikirim.');
    }
}