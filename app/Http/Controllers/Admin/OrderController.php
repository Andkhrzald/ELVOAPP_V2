<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Product;
use App\Models\ActivityLog;
use App\Models\StockMutation;
use App\Models\Setting;
use Barryvdh\DomPDF\Facade\Pdf;
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

        $orders = $query->latest()->paginate(10)->withQueryString();

        // Single query for all status counts
        $statusCounts = Order::selectRaw('status, COUNT(*) as count')
            ->groupBy('status')
            ->pluck('count', 'status')
            ->toArray();

        // Ensure all statuses exist in the array
        $allStatuses = ['pending', 'proses', 'dikirim', 'selesai', 'minta_batal', 'batal', 'minta_refund', 'refund'];
        foreach ($allStatuses as $s) {
            $statusCounts[$s] = $statusCounts[$s] ?? 0;
        }

        // Statistik relevan (bukan total pendapatan)
        $totalOrders = array_sum($statusCounts);
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

        // Return stock
        foreach ($order->items as $item) {
            $product = Product::find($item->product_id);
            if ($product) {
                $product->increment('stock', $item->quantity);
                StockMutation::log($product, 'cancel', $item->quantity, 'Pembatalan pesanan #' . $order->order_number);
            }
        }

        ActivityLog::create([
            'action' => 'cancel_confirmed',
            'description' => 'Pembatalan pesanan #' . $order->order_number . ' dikonfirmasi admin. Alasan customer: ' . $order->cancel_reason,
            'model_type' => 'Order', 'model_id' => $order->id,
        ]);
        return redirect()->back()->with('success', '✅ Pembatalan #' . $order->order_number . ' dikonfirmasi. Stok dikembalikan.');
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

    public function invoice($id)
    {
        $order = Order::with('items', 'user')->findOrFail($id);
        $store = Setting::getGroup('store');
        $subtotal = $order->items->sum('subtotal');
        $taxRate = (float) (Setting::getValue('tax_rate', '0'));
        $taxAmount = ($subtotal * $taxRate) / 100;

        $pdf = Pdf::loadView('admin.invoice', compact('order', 'store', 'subtotal', 'taxRate', 'taxAmount'));
        return $pdf->download('INV-' . $order->order_number . '.pdf');
    }
}