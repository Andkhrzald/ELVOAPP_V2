<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Invoice #{{ $order->order_number }}</title>
    <style>
        @page { margin: 20mm; }
        body { font-family: 'DejaVu Sans', sans-serif; font-size: 10px; color: #333; }
        .header { text-align: center; margin-bottom: 20px; border-bottom: 2px solid #4F46E5; padding-bottom: 15px; }
        .header h1 { font-size: 22px; color: #4F46E5; margin: 0 0 3px; }
        .header p { margin: 2px 0; color: #666; font-size: 9px; }
        .invoice-title { text-align: center; margin: 15px 0; }
        .invoice-title h2 { font-size: 16px; color: #333; margin: 0; letter-spacing: 2px; text-transform: uppercase; }
        .info-grid { display: flex; justify-content: space-between; margin: 15px 0; }
        .info-box { width: 48%; }
        .info-box h4 { font-size: 9px; color: #4F46E5; margin: 0 0 5px; text-transform: uppercase; letter-spacing: 1px; }
        .info-box p { margin: 1px 0; font-size: 9px; color: #555; }
        table { width: 100%; border-collapse: collapse; margin: 15px 0; }
        th { background: #4F46E5; color: white; padding: 7px 8px; text-align: left; font-size: 8px; text-transform: uppercase; letter-spacing: 1px; }
        td { padding: 6px 8px; border-bottom: 1px solid #eee; font-size: 9px; }
        .text-right { text-align: right; }
        .text-center { text-align: center; }
        .total-table { width: 280px; margin-left: auto; }
        .total-table td { padding: 4px 8px; border: none; font-size: 9px; }
        .total-table .grand-total td { font-weight: bold; font-size: 12px; color: #4F46E5; border-top: 2px solid #4F46E5; padding-top: 6px; }
        .footer { margin-top: 30px; padding-top: 15px; border-top: 1px solid #ddd; text-align: center; font-size: 8px; color: #999; }
        .status-badge { display: inline-block; padding: 2px 8px; border-radius: 3px; font-size: 8px; font-weight: bold; }
        .status-completed { background: #d1fae5; color: #065f46; }
        .status-other { background: #fef3c7; color: #92400e; }
        .thank-you { text-align: center; margin-top: 20px; font-size: 12px; color: #4F46E5; font-style: italic; }
    </style>
</head>
<body>
    <div class="header">
        <h1>{{ $store['store_name'] ?? 'ELVO Store' }}</h1>
        <p>{{ $store['store_address'] ?? '' }}</p>
        <p>Telp: {{ $store['store_phone'] ?? '' }} | Email: {{ $store['store_email'] ?? '' }}</p>
    </div>

    <div class="invoice-title">
        <h2>INVOICE</h2>
    </div>

    <div class="info-grid">
        <div class="info-box">
            <h4>Kepada</h4>
            <p><strong>{{ $order->user->name ?? 'Guest' }}</strong></p>
            <p>{{ $order->user->email ?? '-' }}</p>
            <p>{{ $order->user->phone ?? '-' }}</p>
            <p>{{ $order->user->address ?? '-' }}</p>
        </div>
        <div class="info-box" style="text-align: right;">
            <h4>Detail Pesanan</h4>
            <p><strong>No. Invoice:</strong> INV-{{ $order->order_number }}</p>
            <p><strong>Tanggal:</strong> {{ $order->created_at->format('d/m/Y H:i') }}</p>
            <p><strong>Status:</strong> 
                <span class="status-badge {{ $order->status === 'selesai' ? 'status-completed' : 'status-other' }}">
                    {{ strtoupper($order->status) }}
                </span>
            </p>
            <p><strong>Pembayaran:</strong> {{ $order->payment_method ?? '-' }}</p>
        </div>
    </div>

    <table>
        <thead>
            <tr>
                <th style="width: 60%;">Produk</th>
                <th class="text-center">Qty</th>
                <th class="text-right">Harga</th>
                <th class="text-right">Subtotal</th>
            </tr>
        </thead>
        <tbody>
            @foreach($order->items as $item)
            <tr>
                <td>{{ $item->product_name }}</td>
                <td class="text-center">{{ $item->quantity }}</td>
                <td class="text-right">Rp {{ number_format($item->price, 0, ',', '.') }}</td>
                <td class="text-right">Rp {{ number_format($item->subtotal, 0, ',', '.') }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>

    <table class="total-table">
        <tr>
            <td style="text-align: left;">Subtotal</td>
            <td class="text-right">Rp {{ number_format($subtotal, 0, ',', '.') }}</td>
        </tr>
        @if($taxRate > 0)
        <tr>
            <td style="text-align: left;">Pajak ({{ $taxRate }}%)</td>
            <td class="text-right">Rp {{ number_format($taxAmount, 0, ',', '.') }}</td>
        </tr>
        @endif
        <tr>
            <td style="text-align: left;">Ongkos Kirim</td>
            <td class="text-right">Rp {{ number_format($order->shipping_cost ?? 0, 0, ',', '.') }}</td>
        </tr>
        <tr class="grand-total">
            <td style="text-align: left;">Total</td>
            <td class="text-right">Rp {{ number_format($order->total_price, 0, ',', '.') }}</td>
        </tr>
    </table>

    @if($order->no_resi)
    <div style="margin-top: 10px; padding: 8px; background: #f5f5f5; border-radius: 4px; font-size: 9px;">
        <strong>No. Resi:</strong> {{ $order->no_resi }} | 
        <strong>Kurir:</strong> {{ $order->shipping_method ?? 'Standard' }}
    </div>
    @endif

    @if($order->notes)
    <div style="margin-top: 10px; font-size: 9px; color: #666;">
        <strong>Catatan:</strong> {{ $order->notes }}
    </div>
    @endif

    <div class="thank-you">Terima kasih telah berbelanja di {{ $store['store_name'] ?? 'ELVO Store' }}!</div>

    <div class="footer">
        <p>Invoice ini dibuat otomatis oleh sistem ELVO — {{ date('d/m/Y H:i') }}</p>
        <p>{{ $store['store_name'] ?? 'ELVO Store' }} | {{ $store['store_email'] ?? '' }}</p>
    </div>
</body>
</html>
