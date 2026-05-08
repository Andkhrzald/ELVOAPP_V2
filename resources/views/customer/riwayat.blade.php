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

        <h1 class="text-[20px] font-black uppercase tracking-[0.3em] mb-3">Riwayat Transaksi</h1>
        <p class="text-[10px] text-gray-500 uppercase tracking-widest mb-12">Pesanan yang sudah selesai, dibatalkan, atau di-refund</p>

        @if($orders->isEmpty())
        <div class="flex flex-col items-center justify-center py-24 px-6 border border-white/5 rounded-3xl bg-white/[0.02] text-center" data-aos="zoom-in">
            <div class="relative mb-8 text-gray-700">
                <svg class="w-24 h-24" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"/>
                </svg>
            </div>
            <h2 class="text-[16px] font-black uppercase tracking-[0.3em] mb-3">Belum Ada Riwayat</h2>
            <p class="text-gray-500 text-[10px] uppercase tracking-widest mb-8">Transaksi yang selesai akan muncul di sini</p>
            <a href="{{ route('history.index') }}" class="px-8 py-3 bg-white/5 border border-white/10 text-white text-[10px] font-black uppercase tracking-widest rounded-full hover:bg-white/10 transition">
                Lihat Pesanan Aktif
            </a>
        </div>

        @else
        <div class="space-y-6">
            @foreach($orders as $order)
            <div class="bg-white/[0.03] border border-white/10 rounded-xl overflow-hidden" data-aos="fade-up">
                {{-- Header --}}
                <div class="p-6 flex flex-col sm:flex-row justify-between items-start sm:items-center gap-3 border-b border-white/5">
                    <div>
                        <span class="text-[10px] font-bold uppercase tracking-widest text-gray-500">Order #{{ $order->order_number }}</span>
                        <span class="text-[10px] text-gray-600 ml-3">{{ $order->created_at->format('d M Y') }}</span>
                    </div>
                    @if($order->status === 'selesai')
                    <span class="px-3 py-1 rounded-full bg-green-500/10 text-green-500 text-[10px] font-black uppercase border border-green-500/20">✅ Selesai</span>
                    @elseif($order->status === 'batal')
                    <span class="px-3 py-1 rounded-full bg-red-500/10 text-red-500 text-[10px] font-black uppercase border border-red-500/20">❌ Dibatalkan</span>
                    @elseif($order->status === 'refund')
                    <span class="px-3 py-1 rounded-full bg-pink-500/10 text-pink-500 text-[10px] font-black uppercase border border-pink-500/20">💰 Refund</span>
                    @endif
                </div>

                {{-- Items + Review Button per item --}}
                <div class="p-6">
                    @foreach($order->items as $item)
                    <div class="flex items-center gap-4 py-2">
                        <div class="w-12 h-12 bg-white/5 rounded-lg flex items-center justify-center text-gray-600 text-xs font-bold">{{ $item->quantity }}x</div>
                        <div class="flex-1">
                            <p class="text-[11px] font-black uppercase">{{ $item->product_name }}</p>
                            <p class="text-[9px] text-gray-500">Rp {{ number_format($item->price, 0, ',', '.') }} × {{ $item->quantity }}</p>
                        </div>
                        <p class="text-[11px] font-bold text-white mr-4">Rp {{ number_format($item->subtotal, 0, ',', '.') }}</p>

                        {{-- Review Button (hanya jika selesai & belum review) --}}
                        @if($order->status === 'selesai' && $item->product_id)
                            @php
                            $reviewed = \App\Models\Review::where('user_id', auth()->id())
                                ->where('product_id', $item->product_id)
                                ->where('order_id', $order->id)->exists();
                            @endphp
                            @if($reviewed)
                            <span class="px-3 py-1.5 bg-yellow-500/10 text-yellow-500 rounded-full text-[9px] font-bold">⭐ Sudah Review</span>
                            @else
                            <button onclick="openReviewModal({{ $order->id }}, {{ $item->product_id }}, '{{ addslashes($item->product_name) }}')"
                                class="px-3 py-1.5 bg-yellow-500/10 text-yellow-500 border border-yellow-500/20 rounded-full text-[9px] font-bold uppercase hover:bg-yellow-500 hover:text-black transition">
                                Beri Review
                            </button>
                            @endif
                        @endif
                    </div>
                    @endforeach
                </div>

                {{-- Info --}}
                @if($order->status === 'batal' && $order->cancel_reason)
                <div class="mx-6 mb-4 p-3 bg-red-500/10 border border-red-500/20 rounded-lg">
                    <p class="text-[10px] text-red-400"><span class="font-bold">Alasan pembatalan:</span> "{{ $order->cancel_reason }}"</p>
                </div>
                @endif
                @if($order->status === 'refund' && $order->refund_reason)
                <div class="mx-6 mb-4 p-3 bg-pink-500/10 border border-pink-500/20 rounded-lg">
                    <p class="text-[10px] text-pink-400"><span class="font-bold">Alasan refund:</span> "{{ $order->refund_reason }}"</p>
                </div>
                @endif
                @if($order->no_resi)
                <div class="mx-6 mb-4 p-3 bg-purple-500/10 border border-purple-500/20 rounded-lg">
                    <p class="text-[10px] text-purple-400 font-bold">🚚 Resi: <span class="font-mono text-white">{{ $order->no_resi }}</span></p>
                </div>
                @endif

                {{-- Footer --}}
                <div class="p-6 border-t border-white/5 flex justify-between items-center">
                    <div>
                        <p class="text-[10px] text-gray-500 uppercase">Total</p>
                        <p class="text-[16px] font-black text-white">Rp {{ number_format($order->total_price, 0, ',', '.') }}</p>
                    </div>
                    <p class="text-[9px] text-gray-600 uppercase tracking-widest">{{ $order->payment_method ?? '-' }}</p>
                </div>
            </div>
            @endforeach
        </div>
        @endif
    </div>
</div>

{{-- Review Modal --}}
<div id="review-modal" class="fixed inset-0 z-[300] hidden">
    <div class="absolute inset-0 bg-black/80 backdrop-blur-sm" onclick="closeReviewModal()"></div>
    <div class="absolute top-1/2 left-1/2 -translate-x-1/2 -translate-y-1/2 w-full max-w-md bg-[#0f0f0f] border border-white/10 rounded-3xl p-8">
        <h2 class="text-xl font-black uppercase tracking-tight text-white mb-1">Beri Review</h2>
        <p class="text-xs text-gray-500 mb-6" id="review-product-name">-</p>
        <form id="review-form" action="{{ route('reviews.store') }}" method="POST">
            @csrf
            <input type="hidden" name="order_id" id="review-order-id">
            <input type="hidden" name="product_id" id="review-product-id">

            {{-- Star Rating --}}
            <label class="block text-[10px] font-black text-gray-400 uppercase tracking-widest mb-3">Rating</label>
            <div class="flex gap-2 mb-6" id="star-container">
                @for($i = 1; $i <= 5; $i++)
                <button type="button" onclick="setRating({{ $i }})" class="star-btn text-gray-700 hover:text-yellow-500 transition" data-star="{{ $i }}">
                    <svg class="w-8 h-8" fill="currentColor" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/></svg>
                </button>
                @endfor
            </div>
            <input type="hidden" name="rating" id="rating-value" value="5">

            {{-- Comment --}}
            <label class="block text-[10px] font-black text-gray-400 uppercase tracking-widest mb-2">Ulasan (Opsional)</label>
            <textarea name="comment" rows="3" placeholder="Tulis ulasan kamu..."
                class="w-full bg-white/5 border border-white/10 rounded-xl px-4 py-3 text-sm text-white placeholder-gray-600 resize-none focus:ring-1 focus:ring-yellow-500"></textarea>

            <div class="flex gap-3 mt-6">
                <button type="button" onclick="closeReviewModal()" class="flex-1 py-3 bg-white/5 text-gray-400 rounded-full font-black text-[10px] uppercase tracking-widest">Batal</button>
                <button type="submit" class="flex-1 py-3 bg-yellow-500 text-black rounded-full font-black text-[10px] uppercase tracking-widest hover:bg-yellow-400 transition">⭐ Kirim Review</button>
            </div>
        </form>
    </div>
</div>

<script>
function openReviewModal(orderId, productId, productName) {
    document.getElementById('review-order-id').value = orderId;
    document.getElementById('review-product-id').value = productId;
    document.getElementById('review-product-name').textContent = productName;
    setRating(5);
    document.getElementById('review-modal').classList.remove('hidden');
}
function closeReviewModal() { document.getElementById('review-modal').classList.add('hidden'); }
function setRating(n) {
    document.getElementById('rating-value').value = n;
    document.querySelectorAll('.star-btn').forEach(btn => {
        btn.classList.toggle('text-yellow-500', parseInt(btn.dataset.star) <= n);
        btn.classList.toggle('text-gray-700', parseInt(btn.dataset.star) > n);
    });
}
setTimeout(() => { document.querySelectorAll('[id^="toast"]').forEach(el => el.remove()); }, 5000);
</script>
<style>
@keyframes slideIn { from { transform: translateX(100%); opacity: 0; } to { transform: translateX(0); opacity: 1; } }
</style>
@endsection
