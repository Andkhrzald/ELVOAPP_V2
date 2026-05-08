@extends('layouts.customer')

@section('content')
<div class="min-h-screen text-white pt-24 pb-12 px-6">
    <div class="max-w-4xl mx-auto">

        {{-- Toast --}}
        @if(session('success'))
        <div id="toast" class="fixed top-24 right-6 z-[100] bg-green-500/20 border border-green-500/30 text-green-400 px-6 py-4 rounded-2xl shadow-2xl backdrop-blur-xl flex items-center gap-3" style="animation: slideIn 0.5s ease-out">
            <span class="text-sm font-bold">{{ session('success') }}</span>
            <button onclick="this.parentElement.remove()" class="ml-2">&times;</button>
        </div>
        @endif
        @if(session('error'))
        <div id="toast-err" class="fixed top-24 right-6 z-[100] bg-red-500/20 border border-red-500/30 text-red-400 px-6 py-4 rounded-2xl shadow-2xl backdrop-blur-xl flex items-center gap-3" style="animation: slideIn 0.5s ease-out">
            <span class="text-sm font-bold">{{ session('error') }}</span>
            <button onclick="this.parentElement.remove()" class="ml-2">&times;</button>
        </div>
        @endif

        <h1 class="text-[20px] font-black uppercase tracking-[0.3em] mb-12">Pesanan Saya</h1>

        @if($orders->isEmpty())
        <div class="flex flex-col items-center justify-center py-24 px-6 border border-white/5 rounded-3xl bg-white/[0.02] text-center" data-aos="zoom-in">
            <div class="relative mb-8 text-gray-700 animate-bounce">
                <svg class="w-32 h-32" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1">
                    <circle cx="8" cy="21" r="1" /><circle cx="19" cy="21" r="1" />
                    <path d="M2.05 2.05h2l2.66 12.42a2 2 0 0 0 2 1.58h9.78a2 2 0 0 0 1.95-1.57l1.65-7.43H5.12" />
                </svg>
            </div>
            <h2 class="text-[18px] font-black uppercase tracking-[0.4em] mb-3">Belum Ada Pesanan</h2>
            <p class="text-gray-500 text-[10px] uppercase tracking-widest mb-8">Belum ada transaksi nih, yuk belanja!</p>
            <a href="{{ route('shop.index') }}" class="px-8 py-3 bg-white text-black text-[10px] font-black uppercase tracking-widest rounded-full hover:bg-gray-200 transition">
                Gas Belanja Sekarang
            </a>
        </div>

        @else
        <div class="space-y-6">
            @foreach($orders as $order)
            <div class="bg-white/[0.03] border border-white/10 rounded-xl overflow-hidden" data-aos="fade-up">
                {{-- Order Header --}}
                <div class="p-6 flex flex-col sm:flex-row justify-between items-start sm:items-center gap-3 border-b border-white/5">
                    <div>
                        <span class="text-[10px] font-bold uppercase tracking-widest text-gray-500">Order #{{ $order->order_number }}</span>
                        <span class="text-[10px] text-gray-600 ml-3">{{ $order->created_at->format('d M Y') }}</span>
                    </div>
                    {{-- Status Badge --}}
                    @php
                    $statusMap = [
                        'pending' => ['label' => 'Menunggu Konfirmasi', 'color' => 'orange'],
                        'proses' => ['label' => 'Sedang Diproses', 'color' => 'blue'],
                        'dikirim' => ['label' => 'Sedang Dikirim', 'color' => 'purple'],
                        'selesai' => ['label' => 'Selesai', 'color' => 'green'],
                        'minta_batal' => ['label' => 'Menunggu Konfirmasi Batal', 'color' => 'yellow'],
                        'batal' => ['label' => 'Dibatalkan', 'color' => 'red'],
                        'minta_refund' => ['label' => 'Menunggu Konfirmasi Refund', 'color' => 'amber'],
                        'refund' => ['label' => 'Refund Dikonfirmasi', 'color' => 'pink'],
                    ];
                    $s = $statusMap[$order->status] ?? ['label' => $order->status, 'color' => 'gray'];
                    @endphp
                    <span class="px-3 py-1 rounded-full bg-{{ $s['color'] }}-500/10 text-{{ $s['color'] }}-500 text-[10px] font-black uppercase tracking-wider border border-{{ $s['color'] }}-500/20">
                        {{ $s['label'] }}
                    </span>
                </div>

                {{-- Items --}}
                <div class="p-6">
                    @foreach($order->items as $item)
                    <div class="flex items-center gap-4 py-2">
                        <div class="w-12 h-12 bg-white/5 rounded-lg flex items-center justify-center text-gray-600 text-xs font-bold">
                            {{ $item->quantity }}x
                        </div>
                        <div class="flex-1">
                            <p class="text-[11px] font-black uppercase">{{ $item->product_name }}</p>
                            <p class="text-[9px] text-gray-500">Rp {{ number_format($item->price, 0, ',', '.') }} × {{ $item->quantity }}</p>
                        </div>
                        <p class="text-[11px] font-bold text-white">Rp {{ number_format($item->subtotal, 0, ',', '.') }}</p>
                    </div>
                    @endforeach
                </div>

                {{-- Resi Info --}}
                @if($order->no_resi)
                <div class="mx-6 mb-4 p-3 bg-purple-500/10 border border-purple-500/20 rounded-lg">
                    <p class="text-[10px] text-purple-400 font-bold">🚚 Resi: <span class="font-mono text-white">{{ $order->no_resi }}</span> · {{ $order->shipping_method }}</p>
                </div>
                @endif

                {{-- Status Messages --}}
                @if($order->status === 'minta_batal')
                <div class="mx-6 mb-4 p-3 bg-yellow-500/10 border border-yellow-500/20 rounded-lg">
                    <p class="text-[10px] text-yellow-400">⏳ Permintaan pembatalan sedang ditinjau admin. Alasan: "{{ $order->cancel_reason }}"</p>
                </div>
                @endif
                @if($order->status === 'batal')
                <div class="mx-6 mb-4 p-3 bg-red-500/10 border border-red-500/20 rounded-lg">
                    <p class="text-[10px] text-red-400">✅ Pembatalan pesanan sudah dikonfirmasi oleh admin.</p>
                </div>
                @endif
                @if($order->status === 'minta_refund')
                <div class="mx-6 mb-4 p-3 bg-amber-500/10 border border-amber-500/20 rounded-lg">
                    <p class="text-[10px] text-amber-400">⏳ Permintaan refund sedang ditinjau admin. Alasan: "{{ $order->refund_reason }}"</p>
                </div>
                @endif
                @if($order->status === 'refund')
                <div class="mx-6 mb-4 p-3 bg-pink-500/10 border border-pink-500/20 rounded-lg">
                    <p class="text-[10px] text-pink-400">✅ Refund sudah dikonfirmasi. Dana akan dikembalikan.</p>
                </div>
                @endif

                {{-- Footer: Total + Actions --}}
                <div class="p-6 border-t border-white/5 flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
                    <div>
                        <p class="text-[10px] text-gray-500 uppercase">Total</p>
                        <p class="text-[16px] font-black text-white">Rp {{ number_format($order->total_price, 0, ',', '.') }}</p>
                    </div>
                    <div class="flex gap-3">
                        {{-- CANCEL BUTTON (pending/proses only) --}}
                        @if(in_array($order->status, ['pending', 'proses']))
                        <button onclick="openCancelModal({{ $order->id }}, '{{ $order->order_number }}')" class="px-5 py-2.5 bg-red-500/10 text-red-500 border border-red-500/20 rounded-full text-[10px] font-black uppercase tracking-widest hover:bg-red-500 hover:text-white transition">
                            Ajukan Pembatalan
                        </button>
                        @endif

                        {{-- REFUND BUTTON (dikirim only) --}}
                        @if($order->status === 'dikirim')
                        <button onclick="openRefundModal({{ $order->id }}, '{{ $order->order_number }}')" class="px-5 py-2.5 bg-amber-500/10 text-amber-500 border border-amber-500/20 rounded-full text-[10px] font-black uppercase tracking-widest hover:bg-amber-500 hover:text-white transition">
                            Ajukan Refund
                        </button>
                        @endif
                    </div>
                </div>
            </div>
            @endforeach
        </div>
        @endif
    </div>
</div>

{{-- Modal Cancel --}}
<div id="cancel-modal" class="fixed inset-0 z-[300] hidden">
    <div class="absolute inset-0 bg-black/80 backdrop-blur-sm" onclick="closeCancelModal()"></div>
    <div class="absolute top-1/2 left-1/2 -translate-x-1/2 -translate-y-1/2 w-full max-w-md bg-[#0f0f0f] border border-white/10 rounded-3xl p-8">
        <h2 class="text-xl font-black uppercase tracking-tight text-white mb-1">Ajukan Pembatalan</h2>
        <p class="text-xs text-gray-500 mb-6" id="cancel-subtitle">-</p>
        <form id="cancel-form" method="POST">
            @csrf
            <label class="block text-[10px] font-black text-gray-400 uppercase tracking-widest mb-2">Alasan Pembatalan *</label>
            <textarea name="cancel_reason" required rows="3" placeholder="Jelaskan alasan pembatalan..." class="w-full bg-white/5 border border-white/10 rounded-xl px-4 py-3 text-sm text-white placeholder-gray-600 resize-none focus:ring-1 focus:ring-red-500"></textarea>
            <div class="flex gap-3 mt-6">
                <button type="button" onclick="closeCancelModal()" class="flex-1 py-3 bg-white/5 text-gray-400 rounded-full font-black text-[10px] uppercase tracking-widest">Kembali</button>
                <button type="submit" class="flex-1 py-3 bg-red-500 text-white rounded-full font-black text-[10px] uppercase tracking-widest hover:bg-red-600 transition">Kirim</button>
            </div>
        </form>
    </div>
</div>

{{-- Modal Refund --}}
<div id="refund-modal" class="fixed inset-0 z-[300] hidden">
    <div class="absolute inset-0 bg-black/80 backdrop-blur-sm" onclick="closeRefundModal()"></div>
    <div class="absolute top-1/2 left-1/2 -translate-x-1/2 -translate-y-1/2 w-full max-w-md bg-[#0f0f0f] border border-white/10 rounded-3xl p-8">
        <h2 class="text-xl font-black uppercase tracking-tight text-white mb-1">Ajukan Refund</h2>
        <p class="text-xs text-gray-500 mb-6" id="refund-subtitle">-</p>
        <form id="refund-form" method="POST">
            @csrf
            <label class="block text-[10px] font-black text-gray-400 uppercase tracking-widest mb-2">Alasan Refund *</label>
            <textarea name="refund_reason" required rows="3" placeholder="Jelaskan alasan refund..." class="w-full bg-white/5 border border-white/10 rounded-xl px-4 py-3 text-sm text-white placeholder-gray-600 resize-none focus:ring-1 focus:ring-amber-500"></textarea>
            <div class="flex gap-3 mt-6">
                <button type="button" onclick="closeRefundModal()" class="flex-1 py-3 bg-white/5 text-gray-400 rounded-full font-black text-[10px] uppercase tracking-widest">Kembali</button>
                <button type="submit" class="flex-1 py-3 bg-amber-500 text-white rounded-full font-black text-[10px] uppercase tracking-widest hover:bg-amber-600 transition">Kirim</button>
            </div>
        </form>
    </div>
</div>

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

setTimeout(() => { document.querySelectorAll('[id^="toast"]').forEach(el => el.remove()); }, 5000);
</script>
<style>
@keyframes slideIn { from { transform: translateX(100%); opacity: 0; } to { transform: translateX(0); opacity: 1; } }
</style>
@endsection