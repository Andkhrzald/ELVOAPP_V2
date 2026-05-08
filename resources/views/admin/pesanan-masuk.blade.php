@extends('layouts.app')

@section('content')
<div class="min-h-screen bg-[#0f0f0f] text-gray-300 pb-12">

    {{-- Toast --}}
    @if(session('success'))
    <div id="toast-success" class="fixed top-20 right-6 z-[100] bg-green-500/20 border border-green-500/30 text-green-400 px-6 py-4 rounded-2xl shadow-2xl backdrop-blur-xl flex items-center gap-3 animate-slide-in">
        <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
        <span class="text-sm font-bold">{{ session('success') }}</span>
        <button onclick="this.parentElement.remove()" class="ml-2 text-green-500/50 hover:text-white">&times;</button>
    </div>
    @endif
    @if(session('error'))
    <div id="toast-error" class="fixed top-20 right-6 z-[100] bg-red-500/20 border border-red-500/30 text-red-400 px-6 py-4 rounded-2xl shadow-2xl backdrop-blur-xl flex items-center gap-3 animate-slide-in">
        <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
        <span class="text-sm font-bold">{{ session('error') }}</span>
        <button onclick="this.parentElement.remove()" class="ml-2 text-red-500/50 hover:text-white">&times;</button>
    </div>
    @endif

    {{-- Header --}}
    <div class="max-w-7xl mx-auto flex flex-col md:flex-row md:items-end justify-between mb-8 px-4 lg:px-0 pt-10 gap-4">
        <div>
            <h1 class="text-3xl font-bold text-white tracking-tight">Manajemen Pesanan</h1>
            <p class="text-[11px] font-semibold text-blue-500 uppercase tracking-[0.2em] mt-1">Status: {{ ucfirst(str_replace('_', ' ', $status)) }}</p>
        </div>
    </div>

    {{-- Status Tabs --}}
    <div class="max-w-7xl mx-auto px-4 lg:px-0 mb-8">
        <div class="flex flex-wrap gap-2">
            @php
            $tabs = [
                'pending'      => ['label' => 'Pending',      'color' => 'orange'],
                'proses'       => ['label' => 'Diproses',     'color' => 'blue'],
                'dikirim'      => ['label' => 'Dikirim',      'color' => 'purple'],
                'selesai'      => ['label' => 'Selesai',      'color' => 'green'],
                'minta_batal'  => ['label' => '⚠ Minta Batal','color' => 'yellow'],
                'batal'        => ['label' => 'Batal',        'color' => 'red'],
                'minta_refund' => ['label' => '⚠ Minta Refund','color' => 'amber'],
                'refund'       => ['label' => 'Refund',       'color' => 'pink'],
            ];
            @endphp
            @foreach($tabs as $key => $tab)
            <a href="?status={{ $key }}" class="flex items-center gap-2 px-4 py-2.5 rounded-xl text-xs font-bold transition-all {{ $status == $key ? 'bg-'.$tab['color'].'-500 text-white shadow-lg' : 'bg-[#1a1a1a] text-gray-400 border border-white/5' }}">
                {{ $tab['label'] }}
                @if(($statusCounts[$key] ?? 0) > 0)
                <span class="px-2 py-0.5 rounded-full text-[10px] font-black {{ $status == $key ? 'bg-white/20' : 'bg-white/5' }}">{{ $statusCounts[$key] }}</span>
                @endif
            </a>
            @endforeach
        </div>
    </div>

    {{-- Stats --}}
    <div class="max-w-7xl mx-auto grid grid-cols-1 md:grid-cols-3 gap-6 mb-8 px-4 lg:px-0">
        <div class="p-6 bg-[#1a1a1a] rounded-2xl border border-white/5">
            <p class="text-[10px] font-black text-gray-500 uppercase tracking-widest">Total Semua Pesanan</p>
            <p class="text-3xl font-black text-white mt-2">{{ $totalOrders }}</p>
        </div>
        <div class="p-6 bg-[#1a1a1a] rounded-2xl border border-white/5">
            <p class="text-[10px] font-black text-gray-500 uppercase tracking-widest">Filter Aktif</p>
            <p class="text-3xl font-black text-white mt-2">{{ $orders->count() }} <span class="text-xs text-gray-600">Pesanan</span></p>
        </div>
        <div class="p-6 bg-[#1a1a1a] rounded-2xl border border-white/5 border-l-4 border-l-orange-500/50">
            <p class="text-[10px] font-black text-orange-500/80 uppercase tracking-widest">Perlu Tindakan</p>
            <p class="text-3xl font-black text-orange-500 mt-2">{{ $needAction }}</p>
        </div>
    </div>

    {{-- Order Cards --}}
    <div class="max-w-7xl mx-auto px-4 lg:px-0 space-y-4">
        @forelse($orders as $order)
        <div class="bg-[#1a1a1a] rounded-2xl border border-white/5 shadow-xl overflow-hidden hover:border-blue-500/20 transition-all">
            {{-- Header --}}
            <div class="p-6 flex flex-col md:flex-row md:items-center justify-between gap-4 border-b border-white/5">
                <div class="flex items-center gap-4">
                    <div class="w-12 h-12 rounded-2xl bg-blue-500/10 flex items-center justify-center text-blue-500 text-lg font-black">
                        {{ substr($order->user->name ?? 'U', 0, 1) }}
                    </div>
                    <div>
                        <h3 class="font-bold text-white">{{ $order->user->name ?? 'Unknown' }}</h3>
                        <p class="text-xs text-gray-500 font-mono">#{{ $order->order_number }} · {{ $order->created_at->format('d M Y, H:i') }}</p>
                    </div>
                </div>
                <span class="text-lg font-black text-white">Rp {{ number_format($order->total_price, 0, ',', '.') }}</span>
            </div>

            {{-- Body --}}
            <div class="p-6 grid grid-cols-1 md:grid-cols-3 gap-6">
                <div class="space-y-2">
                    <p class="text-[10px] font-black text-gray-600 uppercase tracking-widest">Pelanggan</p>
                    <p class="text-sm text-gray-300"><span class="text-gray-500">HP:</span> {{ $order->user->phone ?? '-' }}</p>
                    <p class="text-sm text-gray-300"><span class="text-gray-500">Alamat:</span> {{ \Illuminate\Support\Str::limit($order->user->address ?? '-', 60) }}</p>
                </div>
                <div class="space-y-2">
                    <p class="text-[10px] font-black text-gray-600 uppercase tracking-widest">Produk</p>
                    @foreach($order->items as $item)
                    <p class="text-sm text-gray-300">{{ $item->quantity }}x {{ $item->product_name }}</p>
                    @endforeach
                </div>
                <div class="space-y-2">
                    <p class="text-[10px] font-black text-gray-600 uppercase tracking-widest">Pengiriman</p>
                    <p class="text-sm text-gray-300"><span class="text-gray-500">Bayar:</span> {{ $order->payment_method ?? '-' }}</p>
                    <p class="text-sm text-gray-300"><span class="text-gray-500">Kurir:</span> {{ $order->shipping_method ?? '-' }}</p>
                    @if($order->no_resi)<p class="text-sm text-blue-400 font-mono font-bold">Resi: {{ $order->no_resi }}</p>@endif
                </div>
            </div>

            {{-- Alasan Cancel/Refund dari Customer --}}
            @if($order->status === 'minta_batal' && $order->cancel_reason)
            <div class="mx-6 mb-4 p-4 bg-yellow-500/10 border border-yellow-500/20 rounded-xl">
                <p class="text-[10px] font-black text-yellow-500 uppercase tracking-widest mb-1">Alasan Pembatalan dari Customer</p>
                <p class="text-sm text-yellow-200 italic">"{{ $order->cancel_reason }}"</p>
                <p class="text-[10px] text-gray-500 mt-1">Status sebelumnya: {{ ucfirst($order->previous_status) }}</p>
            </div>
            @endif
            @if($order->status === 'minta_refund' && $order->refund_reason)
            <div class="mx-6 mb-4 p-4 bg-amber-500/10 border border-amber-500/20 rounded-xl">
                <p class="text-[10px] font-black text-amber-500 uppercase tracking-widest mb-1">Alasan Refund dari Customer</p>
                <p class="text-sm text-amber-200 italic">"{{ $order->refund_reason }}"</p>
            </div>
            @endif
            @if($order->status === 'batal' && $order->cancel_reason)
            <div class="mx-6 mb-4 p-4 bg-red-500/10 border border-red-500/20 rounded-xl">
                <p class="text-[10px] font-black text-red-500 uppercase tracking-widest mb-1">Alasan Pembatalan</p>
                <p class="text-sm text-red-200 italic">"{{ $order->cancel_reason }}"</p>
            </div>
            @endif
            @if($order->status === 'refund' && $order->refund_reason)
            <div class="mx-6 mb-4 p-4 bg-pink-500/10 border border-pink-500/20 rounded-xl">
                <p class="text-[10px] font-black text-pink-500 uppercase tracking-widest mb-1">Alasan Refund</p>
                <p class="text-sm text-pink-200 italic">"{{ $order->refund_reason }}"</p>
            </div>
            @endif

            {{-- Actions --}}
            <div class="px-6 py-4 bg-white/[0.02] border-t border-white/5 flex flex-wrap items-center gap-3">

                @if($order->status === 'pending')
                <form action="{{ route('admin.orders.accept', $order->id) }}" method="POST" onsubmit="return confirm('Konfirmasi pesanan ini?')">
                    @csrf
                    <button class="flex items-center gap-2 px-5 py-2.5 bg-blue-500 text-white rounded-xl text-xs font-black uppercase hover:bg-blue-600 transition shadow-lg shadow-blue-500/20">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                        Konfirmasi Pesanan
                    </button>
                </form>
                @endif

                @if($order->status === 'proses')
                <button onclick="openShipModal({{ $order->id }}, '{{ $order->order_number }}')" class="flex items-center gap-2 px-5 py-2.5 bg-purple-500 text-white rounded-xl text-xs font-black uppercase hover:bg-purple-600 transition shadow-lg shadow-purple-500/20">
                    🚚 Kirim Pesanan
                </button>
                @endif

                @if($order->status === 'dikirim')
                <form action="{{ route('admin.orders.complete', $order->id) }}" method="POST" onsubmit="return confirm('Tandai selesai?')">
                    @csrf
                    <button class="flex items-center gap-2 px-5 py-2.5 bg-green-500 text-white rounded-xl text-xs font-black uppercase hover:bg-green-600 transition shadow-lg shadow-green-500/20">✅ Tandai Selesai</button>
                </form>
                @endif

                {{-- CONFIRM / REJECT CANCEL --}}
                @if($order->status === 'minta_batal')
                <form action="{{ route('admin.orders.confirm-cancel', $order->id) }}" method="POST" onsubmit="return confirm('Konfirmasi pembatalan pesanan ini?')">
                    @csrf
                    <button class="flex items-center gap-2 px-5 py-2.5 bg-red-500 text-white rounded-xl text-xs font-black uppercase hover:bg-red-600 transition">✅ Konfirmasi Batal</button>
                </form>
                <form action="{{ route('admin.orders.reject-cancel', $order->id) }}" method="POST" onsubmit="return confirm('Tolak pembatalan? Status akan kembali ke sebelumnya.')">
                    @csrf
                    <button class="flex items-center gap-2 px-5 py-2.5 bg-white/5 text-gray-400 border border-white/10 rounded-xl text-xs font-black uppercase hover:bg-white/10 transition">❌ Tolak Pembatalan</button>
                </form>
                @endif

                {{-- CONFIRM / REJECT REFUND --}}
                @if($order->status === 'minta_refund')
                <form action="{{ route('admin.orders.confirm-refund', $order->id) }}" method="POST" onsubmit="return confirm('Konfirmasi refund pesanan ini?')">
                    @csrf
                    <button class="flex items-center gap-2 px-5 py-2.5 bg-pink-500 text-white rounded-xl text-xs font-black uppercase hover:bg-pink-600 transition">💰 Konfirmasi Refund</button>
                </form>
                <form action="{{ route('admin.orders.reject-refund', $order->id) }}" method="POST" onsubmit="return confirm('Tolak refund? Status kembali ke Dikirim.')">
                    @csrf
                    <button class="flex items-center gap-2 px-5 py-2.5 bg-white/5 text-gray-400 border border-white/10 rounded-xl text-xs font-black uppercase hover:bg-white/10 transition">❌ Tolak Refund</button>
                </form>
                @endif

                @if($order->status === 'selesai')<span class="px-5 py-2.5 bg-green-500/10 text-green-500 rounded-xl text-xs font-black uppercase">✅ Pesanan Selesai</span>@endif
                @if($order->status === 'batal')<span class="px-5 py-2.5 bg-red-500/10 text-red-500 rounded-xl text-xs font-black uppercase">❌ Dibatalkan</span>@endif
                @if($order->status === 'refund')<span class="px-5 py-2.5 bg-pink-500/10 text-pink-500 rounded-xl text-xs font-black uppercase">💰 Refunded</span>@endif
            </div>
        </div>
        @empty
        <div class="bg-[#1a1a1a] rounded-2xl border border-white/5 p-12 text-center">
            <p class="text-gray-500 font-bold">Tidak ada pesanan dengan status "{{ ucfirst(str_replace('_', ' ', $status)) }}"</p>
        </div>
        @endforelse
    </div>
</div>

{{-- Modal Kirim --}}
<div id="ship-modal" class="fixed inset-0 z-[200] hidden">
    <div class="absolute inset-0 bg-black/70 backdrop-blur-sm" onclick="closeShipModal()"></div>
    <div class="absolute top-1/2 left-1/2 -translate-x-1/2 -translate-y-1/2 w-full max-w-md bg-[#1a1a1a] rounded-3xl border border-white/10 shadow-2xl p-8">
        <h2 class="text-xl font-bold text-white mb-1">Kirim Pesanan</h2>
        <p class="text-xs text-gray-500 mb-6" id="ship-subtitle">-</p>
        <form id="ship-form" method="POST">
            @csrf
            <div class="space-y-4">
                <div>
                    <label class="block text-xs font-bold text-gray-400 uppercase mb-2">Kurir</label>
                    <select name="shipping_method" class="w-full bg-[#252525] border border-white/10 rounded-xl px-4 py-3 text-sm text-white">
                        <option value="JNE Reguler">JNE Reguler</option>
                        <option value="JNE YES">JNE YES</option>
                        <option value="J&T Express">J&T Express</option>
                        <option value="SiCepat Best">SiCepat Best</option>
                        <option value="AnterAja">AnterAja</option>
                    </select>
                </div>
                <div>
                    <label class="block text-xs font-bold text-gray-400 uppercase mb-2">Nomor Resi *</label>
                    <input type="text" name="no_resi" required placeholder="Masukkan nomor resi..." class="w-full bg-[#252525] border border-white/10 rounded-xl px-4 py-3 text-sm text-white placeholder-gray-600">
                </div>
            </div>
            <div class="flex gap-3 mt-8">
                <button type="button" onclick="closeShipModal()" class="flex-1 py-3 bg-white/5 text-gray-400 rounded-xl font-bold text-xs uppercase">Batal</button>
                <button type="submit" class="flex-1 py-3 bg-purple-500 text-white rounded-xl font-bold text-xs uppercase hover:bg-purple-600 transition">🚚 Kirim</button>
            </div>
        </form>
    </div>
</div>

<script>
function openShipModal(id, num) {
    document.getElementById('ship-subtitle').textContent = '#' + num;
    document.getElementById('ship-form').action = '/admin/orders/' + id + '/ship';
    document.getElementById('ship-modal').classList.remove('hidden');
}
function closeShipModal() { document.getElementById('ship-modal').classList.add('hidden'); }
setTimeout(() => { document.querySelectorAll('[id^="toast-"]').forEach(el => el.remove()); }, 5000);
</script>
<style>
@keyframes slideIn { from { transform: translateX(100%); opacity: 0; } to { transform: translateX(0); opacity: 1; } }
.animate-slide-in { animation: slideIn 0.5s ease-out; }
</style>
@endsection