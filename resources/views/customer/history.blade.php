@extends('layouts.customer')

@section('content')
<div class="min-h-screen text-white pt-28 pb-20 px-6">
    <div class="max-w-7xl mx-auto">

        {{-- Header --}}
        <div class="flex items-center justify-between mb-8" data-aos="fade-down">
            <div>
                <h1 class="text-2xl md:text-3xl font-black italic uppercase tracking-tighter">Pesanan Saya</h1>
                <p class="text-[9px] text-gray-500 uppercase tracking-[0.5em] font-bold mt-1">Active Orders</p>
            </div>
            <a href="{{ route('shop.index') }}" class="text-[9px] font-bold text-elvo-primary hover:text-white uppercase tracking-widest transition-colors flex items-center gap-1.5">
                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"/></svg>
                Belanja Lagi
            </a>
        </div>

        {{-- Toast Notifications --}}
        @if(session('success'))
        <div class="mb-5 p-3 bg-green-500/10 border border-green-500/20 rounded-xl text-[9px] text-green-400 font-bold uppercase tracking-widest flex items-center gap-2 animate-fade-up">
            <svg class="w-3.5 h-3.5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
            {{ session('success') }}
        </div>
        @endif
        @if(session('error'))
        <div class="mb-5 p-3 bg-red-500/10 border border-red-500/20 rounded-xl text-[9px] text-red-400 font-bold uppercase tracking-widest flex items-center gap-2 animate-fade-up">
            <svg class="w-3.5 h-3.5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
            {{ session('error') }}
        </div>
        @endif

        @if($orders->isEmpty())
        <div class="text-center py-20 border border-white/[0.06] rounded-2xl bg-white/[0.02]" data-aos="zoom-in">
            <svg class="w-16 h-16 mx-auto text-gray-800 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"/></svg>
            <p class="text-gray-500 text-[9px] uppercase tracking-widest font-bold mb-1">Belum Ada Pesanan Aktif</p>
            <p class="text-gray-700 text-[8px] uppercase tracking-widest mb-6">Yuk mulai belanja!</p>
            <a href="{{ route('shop.index') }}" class="inline-block px-6 py-2.5 bg-white text-black text-[9px] font-black uppercase tracking-widest rounded-full hover:bg-gray-200 transition">Mulai Belanja</a>
        </div>
        @else
        <div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-3 gap-4">
            @foreach($orders as $order)
            @php
            $statusMap = [
                'pending' => ['label' => 'Menunggu Bayar', 'color' => 'orange', 'icon' => '⏳'],
                'proses' => ['label' => 'Diproses', 'color' => 'blue', 'icon' => '🔧'],
                'dikirim' => ['label' => 'Dikirim', 'color' => 'purple', 'icon' => '🚚'],
                'selesai' => ['label' => 'Selesai', 'color' => 'green', 'icon' => '✅'],
                'minta_batal' => ['label' => 'Minta Batal', 'color' => 'yellow', 'icon' => '⏳'],
                'batal' => ['label' => 'Dibatalkan', 'color' => 'red', 'icon' => '❌'],
                'minta_refund' => ['label' => 'Minta Refund', 'color' => 'amber', 'icon' => '⏳'],
                'refund' => ['label' => 'Direfund', 'color' => 'pink', 'icon' => '✅'],
            ];
            $s = $statusMap[$order->status] ?? ['label' => $order->status, 'color' => 'gray', 'icon' => ''];
            @endphp
            <div class="bg-white/[0.03] border border-white/[0.08] rounded-2xl overflow-hidden backdrop-blur-sm flex flex-col" data-aos="fade-up">

                {{-- Header: Order # + Status --}}
                <div class="flex items-center justify-between px-4 py-3 border-b border-white/[0.06]">
                    <div>
                        <p class="text-[11px] font-bold text-white leading-tight">#{{ $order->order_number }}</p>
                        <p class="text-[8px] text-gray-500">{{ $order->created_at->format('d M · H:i') }}</p>
                    </div>
                    <span class="px-2.5 py-1 rounded-full text-[7px] font-black uppercase tracking-widest border shrink-0"
                        style="color: var(--status-{{ $s['color'] }}); background: color-mix(in srgb, var(--status-{{ $s['color'] }}) 10%, transparent); border-color: color-mix(in srgb, var(--status-{{ $s['color'] }}) 20%, transparent);">
                        {{ $s['icon'] }} {{ $s['label'] }}
                    </span>
                </div>

                {{-- Products --}}
                <div class="px-4 py-3 space-y-2 flex-1">
                    <h4 class="text-[7px] font-black text-gray-600 uppercase tracking-widest">Produk</h4>
                    @foreach($order->items as $item)
                    <div class="flex items-center gap-2.5">
                        <div class="w-8 h-8 rounded-lg bg-[#141414] overflow-hidden border border-white/[0.06] shrink-0">
                            @if($item->product && $item->product->image)
                            <img src="{{ asset('uploads/' . $item->product->image) }}" class="w-full h-full object-cover">
                            @else
                            <div class="w-full h-full flex items-center justify-center text-gray-700 text-[10px] font-black">?</div>
                            @endif
                        </div>
                        <div class="flex-1 min-w-0">
                            <p class="text-[10px] font-bold text-white truncate">{{ $item->product_name }}</p>
                            <p class="text-[8px] text-gray-500">{{ $item->quantity }} × Rp {{ number_format($item->price, 0, ',', '.') }}</p>
                        </div>
                        <p class="text-[10px] font-black text-white shrink-0">Rp {{ number_format($item->subtotal, 0, ',', '.') }}</p>
                    </div>
                    @endforeach
                </div>

                {{-- Payment + Shipping Summary --}}
                <div class="px-4 py-2.5 border-t border-white/[0.06] bg-white/[0.01] space-y-1">
                    @if($order->payment_method === 'bank_transfer' && $order->selected_bank && $order->va_number)
                    <div class="flex items-center gap-2">
                        <span class="w-5 h-5 rounded flex items-center justify-center text-white text-[6px] font-black uppercase shrink-0 {{ $order->selected_bank === 'BCA' ? 'bg-blue-600' : ($order->selected_bank === 'BRI' ? 'bg-blue-800' : ($order->selected_bank === 'BNI' ? 'bg-orange-600' : 'bg-gray-600')) }}">
                            {{ $order->selected_bank }}
                        </span>
                        <span class="text-[8px] font-bold text-gray-400">VA</span>
                        <span class="text-[9px] font-mono font-bold text-white/80 tracking-wider">{{ $order->va_number }}</span>
                        <button onclick="copyToClipboard('{{ $order->va_number }}')" class="ml-auto text-[7px] font-black text-elvo-primary uppercase tracking-widest hover:text-white transition shrink-0">Salin</button>
                    </div>
                    @endif
                    @if($order->no_resi)
                    <div class="flex items-center gap-1.5">
                        <span class="text-[8px] text-purple-400 font-bold uppercase tracking-widest">Resi:</span>
                        <span class="text-[9px] font-mono font-bold text-white/80">{{ $order->no_resi }}</span>
                    </div>
                    @endif
                </div>

                {{-- Pending notice --}}
                @if($order->status === 'pending')
                <div class="px-4 py-2 bg-orange-500/5 border-t border-orange-500/15">
                    <p class="text-[8px] text-orange-400 font-bold uppercase tracking-widest text-center">Transfer ke VA di halaman pembayaran</p>
                </div>
                @endif

                {{-- Footer: Total + Actions --}}
                <div class="flex items-center justify-between px-4 py-3 border-t border-white/[0.06] bg-white/[0.01]">
                    <div>
                        <p class="text-[6px] text-gray-600 uppercase tracking-widest font-bold">Total</p>
                        <p class="text-sm font-black text-white">Rp {{ number_format($order->total_price, 0, ',', '.') }}</p>
                    </div>
                    <div class="flex items-center gap-1.5">
                        <a href="{{ route('admin.orders.invoice', $order->id) }}" class="px-3 py-1.5 rounded-lg text-[7px] font-bold text-gray-400 hover:text-white border border-white/[0.08] hover:bg-white/5 uppercase tracking-widest transition">Invoice</a>
                        @if($order->status === 'pending')
                        <a href="{{ route('payment.page', $order->id) }}" class="px-3 py-1.5 rounded-lg bg-elvo-primary text-white text-[7px] font-black uppercase tracking-widest hover:bg-[#6a5cd8] transition">Bayar</a>
                        @endif
                        @if(in_array($order->status, ['pending', 'proses']))
                        <button onclick="openCancelModal({{ $order->id }}, '{{ $order->order_number }}')" class="px-3 py-1.5 rounded-lg text-[7px] font-bold text-red-500 border border-red-500/20 hover:bg-red-500/10 uppercase tracking-widest transition">Batal</button>
                        @endif
                        @if($order->status === 'dikirim')
                        <button onclick="openRefundModal({{ $order->id }}, '{{ $order->order_number }}')" class="px-3 py-1.5 rounded-lg text-[7px] font-bold text-amber-500 border border-amber-500/20 hover:bg-amber-500/10 uppercase tracking-widest transition">Refund</button>
                        @endif
                    </div>
                </div>
            </div>
            @endforeach
        </div>
        @endif

        {{-- Pagination --}}
        @if(method_exists($orders, 'links'))
        <div class="mt-8">
            {{ $orders->links() }}
        </div>
        @endif
    </div>
</div>

{{-- Cancel Modal --}}
<div id="cancel-modal" class="fixed inset-0 z-[300] hidden">
    <div class="absolute inset-0 bg-black/80 backdrop-blur-sm" onclick="closeCancelModal()"></div>
    <div class="absolute top-1/2 left-1/2 -translate-x-1/2 -translate-y-1/2 w-full max-w-md mx-4 bg-[#0f0f0f] border border-white/10 rounded-3xl p-8">
        <h2 class="text-lg font-black uppercase tracking-tight text-white mb-1">Batalkan Pesanan</h2>
        <p class="text-xs text-gray-500 mb-6" id="cancel-subtitle">-</p>
        <form id="cancel-form" method="POST">
            @csrf
            <label class="block text-[10px] font-black text-gray-400 uppercase tracking-widest mb-2">Alasan Pembatalan</label>
            <textarea name="cancel_reason" required rows="3" placeholder="..." class="w-full bg-white/5 border border-white/10 rounded-xl px-4 py-3 text-sm text-white placeholder-gray-600 resize-none focus:ring-1 focus:ring-red-500"></textarea>
            <div class="flex gap-3 mt-6">
                <button type="button" onclick="closeCancelModal()" class="flex-1 py-3 bg-white/5 text-gray-400 rounded-xl font-black text-[10px] uppercase tracking-widest hover:bg-white/10 transition">Kembali</button>
                <button type="submit" class="flex-1 py-3 bg-red-500 text-white rounded-xl font-black text-[10px] uppercase tracking-widest hover:bg-red-600 transition">Kirim</button>
            </div>
        </form>
    </div>
</div>

{{-- Refund Modal --}}
<div id="refund-modal" class="fixed inset-0 z-[300] hidden">
    <div class="absolute inset-0 bg-black/80 backdrop-blur-sm" onclick="closeRefundModal()"></div>
    <div class="absolute top-1/2 left-1/2 -translate-x-1/2 -translate-y-1/2 w-full max-w-md mx-4 bg-[#0f0f0f] border border-white/10 rounded-3xl p-8">
        <h2 class="text-lg font-black uppercase tracking-tight text-white mb-1">Ajukan Refund</h2>
        <p class="text-xs text-gray-500 mb-6" id="refund-subtitle">-</p>
        <form id="refund-form" method="POST">
            @csrf
            <label class="block text-[10px] font-black text-gray-400 uppercase tracking-widest mb-2">Alasan Refund</label>
            <textarea name="refund_reason" required rows="3" placeholder="..." class="w-full bg-white/5 border border-white/10 rounded-xl px-4 py-3 text-sm text-white placeholder-gray-600 resize-none focus:ring-1 focus:ring-amber-500"></textarea>
            <div class="flex gap-3 mt-6">
                <button type="button" onclick="closeRefundModal()" class="flex-1 py-3 bg-white/5 text-gray-400 rounded-xl font-black text-[10px] uppercase tracking-widest hover:bg-white/10 transition">Kembali</button>
                <button type="submit" class="flex-1 py-3 bg-amber-500 text-white rounded-xl font-black text-[10px] uppercase tracking-widest hover:bg-amber-600 transition">Kirim</button>
            </div>
        </form>
    </div>
</div>

<style>
:root {
    --status-orange: #f97316; --status-blue: #3b82f6; --status-purple: #a855f7;
    --status-green: #22c55e; --status-yellow: #eab308; --status-red: #ef4444;
    --status-amber: #f59e0b; --status-pink: #ec4899; --status-gray: #6b7280;
}
</style>

<script>
function openCancelModal(id, num) {
    document.getElementById('cancel-subtitle').textContent = 'Order #' + num;
    document.getElementById('cancel-form').action = '/orders/' + id + '/request-cancel';
    document.getElementById('cancel-modal').classList.remove('hidden');
}
function closeCancelModal() { document.getElementById('cancel-modal').classList.add('hidden'); }
function openRefundModal(id, num) {
    document.getElementById('refund-subtitle').textContent = 'Order #' + num;
    document.getElementById('refund-form').action = '/orders/' + id + '/request-refund';
    document.getElementById('refund-modal').classList.remove('hidden');
}
function closeRefundModal() { document.getElementById('refund-modal').classList.add('hidden'); }
function copyToClipboard(text) {
    navigator.clipboard.writeText(text);
}

// Auto-hide toasts
document.querySelectorAll('[class*="bg-green-500"][class*="animate-fade-up"], [class*="bg-red-500"][class*="animate-fade-up"]').forEach(el => {
    setTimeout(() => { el.style.opacity = '0'; el.style.transition = 'opacity 0.5s'; setTimeout(() => el.remove(), 500); }, 5000);
});
</script>
@endsection