@extends('layouts.customer')

@section('content')
<div class="min-h-screen text-white pt-28 pb-20 px-6">
    <div class="max-w-7xl mx-auto">

        {{-- Header --}}
        <div class="flex items-center justify-between mb-8" data-aos="fade-down">
            <div>
                <h1 class="text-2xl md:text-3xl font-black italic uppercase tracking-tighter">Riwayat Transaksi</h1>
                <p class="text-[9px] text-gray-500 uppercase tracking-[0.5em] font-bold mt-1">Completed / Cancelled / Refunded</p>
            </div>
            <a href="{{ route('history.index') }}" class="text-[9px] font-bold text-elvo-primary hover:text-white uppercase tracking-widest transition-colors flex items-center gap-1.5">
                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/></svg>
                Pesanan Aktif
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
            <svg class="w-16 h-16 mx-auto text-gray-800 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/></svg>
            <p class="text-gray-500 text-[9px] uppercase tracking-widest font-bold mb-1">Belum Ada Riwayat</p>
            <p class="text-gray-700 text-[8px] uppercase tracking-widest mb-6">Transaksi yang selesai akan muncul di sini</p>
            <a href="{{ route('shop.index') }}" class="inline-block px-6 py-2.5 bg-white text-black text-[9px] font-black uppercase tracking-widest rounded-full hover:bg-gray-200 transition">Mulai Belanja</a>
        </div>
        @else
        <div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-3 gap-4">
            @foreach($orders as $order)
            @php
            $statusLabel = match($order->status) {
                'selesai' => ['label' => '✅ Selesai', 'color' => 'green'],
                'batal' => ['label' => '❌ Dibatalkan', 'color' => 'red'],
                'refund' => ['label' => '💰 Refund', 'color' => 'pink'],
                default => ['label' => $order->status, 'color' => 'gray'],
            };
            @endphp
            <div class="bg-white/[0.03] border border-white/[0.08] rounded-2xl overflow-hidden backdrop-blur-sm flex flex-col" data-aos="fade-up">

                {{-- Header --}}
                <div class="flex items-center justify-between px-4 py-3 border-b border-white/[0.06]">
                    <div>
                        <p class="text-[11px] font-bold text-white leading-tight">#{{ $order->order_number }}</p>
                        <p class="text-[8px] text-gray-500">{{ $order->created_at->format('d M · H:i') }}</p>
                    </div>
                    <span class="px-2.5 py-1 rounded-full text-[7px] font-black uppercase tracking-widest border shrink-0"
                        style="color: var(--status-{{ $statusLabel['color'] }}); background: color-mix(in srgb, var(--status-{{ $statusLabel['color'] }}) 10%, transparent); border-color: color-mix(in srgb, var(--status-{{ $statusLabel['color'] }}) 20%, transparent);">
                        {{ $statusLabel['label'] }}
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
                            @if($item->variant_label)<p class="text-[8px] text-elvo-primary/70 font-bold">{{ $item->variant_label }}</p>@endif
                            <p class="text-[8px] text-gray-500">{{ $item->quantity }} × Rp {{ number_format($item->price, 0, ',', '.') }}</p>
                        </div>
                        <div class="flex items-center gap-1.5 shrink-0">
                            <p class="text-[10px] font-black text-white">Rp {{ number_format($item->subtotal, 0, ',', '.') }}</p>
                            @if($order->status === 'selesai' && $item->product_id)
                                @php
                                $reviewed = \App\Models\Review::where('user_id', auth()->id())
                                    ->where('product_id', $item->product_id)
                                    ->where('order_id', $order->id)->exists();
                                @endphp
                                @if($reviewed)
                                <span class="text-[7px] text-yellow-500 font-bold">⭐</span>
                                @else
                                <button onclick="openReviewModal({{ $order->id }}, {{ $item->product_id }}, '{{ addslashes($item->product_name) }}')"
                                    class="px-2 py-0.5 bg-yellow-500/10 text-yellow-500 border border-yellow-500/20 rounded text-[6px] font-bold uppercase hover:bg-yellow-500 hover:text-black transition">Review</button>
                                @endif
                            @endif
                        </div>
                    </div>
                    @endforeach
                </div>

                {{-- Extra Info (VA / Cancel Reason / Refund Reason / Resi) --}}
                <div class="px-4 py-2.5 border-t border-white/[0.06] bg-white/[0.01] space-y-1">
                    @if($order->status === 'selesai' && $order->selected_bank && $order->va_number)
                    <div class="flex items-center gap-2">
                        <span class="w-5 h-5 rounded flex items-center justify-center text-white text-[6px] font-black uppercase shrink-0 {{ $order->selected_bank === 'BCA' ? 'bg-blue-600' : ($order->selected_bank === 'BRI' ? 'bg-blue-800' : ($order->selected_bank === 'BNI' ? 'bg-orange-600' : 'bg-gray-600')) }}">
                            {{ $order->selected_bank }}
                        </span>
                        <span class="text-[8px] font-bold text-gray-400">VA</span>
                        <span class="text-[9px] font-mono font-bold text-white/80 tracking-wider">{{ $order->va_number }}</span>
                    </div>
                    @endif
                    @if($order->status === 'batal' && $order->cancel_reason)
                    <div class="text-[8px] text-red-400 italic truncate">"{{ $order->cancel_reason }}"</div>
                    @endif
                    @if($order->status === 'refund' && $order->refund_reason)
                    <div class="text-[8px] text-pink-400 italic truncate">"{{ $order->refund_reason }}"</div>
                    @endif
                    @if($order->no_resi)
                    <div class="flex items-center gap-1.5">
                        <span class="text-[8px] text-purple-400 font-bold uppercase tracking-widest">Resi:</span>
                        <span class="text-[9px] font-mono font-bold text-white/80">{{ $order->no_resi }}</span>
                    </div>
                    @endif
                </div>

                {{-- Footer --}}
                <div class="flex items-center justify-between px-4 py-3 border-t border-white/[0.06] bg-white/[0.01]">
                    <div>
                        <p class="text-[6px] text-gray-600 uppercase tracking-widest font-bold">Total</p>
                        <p class="text-sm font-black text-white">Rp {{ number_format($order->total_price, 0, ',', '.') }}</p>
                    </div>
                    <div class="flex items-center gap-1.5">
                        <span class="text-[7px] text-gray-600 uppercase tracking-widest">{{ $order->payment_method === 'bank_transfer' ? 'TRANSFER' : ($order->payment_method === 'qris' ? 'QRIS' : strtoupper(str_replace('_', ' ', $order->payment_method ?? '-'))) }}</span>
                        <a href="{{ route('orders.invoice', $order->id) }}" class="px-3 py-1.5 rounded-lg text-[7px] font-bold text-gray-400 hover:text-white border border-white/[0.08] hover:bg-white/5 uppercase tracking-widest transition">Invoice</a>
                    </div>
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
        <h2 class="text-lg font-black uppercase tracking-tight text-white mb-1">Beri Review</h2>
        <p class="text-xs text-gray-500 mb-6" id="review-product-name">-</p>
        <form id="review-form" action="{{ route('reviews.store') }}" method="POST" enctype="multipart/form-data">
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

            {{-- Photo Upload --}}
            <label class="block text-[10px] font-black text-gray-400 uppercase tracking-widest mb-2 mt-4">Foto Unboxing (Maks. 5)</label>
            <div class="flex items-center gap-2 mb-2" id="review-image-previews"></div>
            <label class="flex items-center justify-center w-full h-20 border-2 border-dashed border-white/10 rounded-xl cursor-pointer hover:border-white/30 transition group" id="upload-label">
                <div class="flex flex-col items-center">
                    <svg class="w-5 h-5 text-gray-600 group-hover:text-white transition" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                    <span class="text-[8px] text-gray-600 mt-1 font-bold uppercase tracking-widest">Tambah Foto</span>
                </div>
                <input type="file" name="images[]" id="review-images-input" multiple accept="image/jpeg,image/png,image/jpg,image/webp" class="hidden" onchange="previewReviewImages(this)">
            </label>
            <p class="text-[9px] text-gray-600 mt-1" id="image-count">0/5 foto</p>

            <div class="flex gap-3 mt-6">
                <button type="button" onclick="closeReviewModal()" class="flex-1 py-3 bg-white/5 text-gray-400 rounded-full font-black text-[10px] uppercase tracking-widest">Batal</button>
                <button type="submit" class="flex-1 py-3 bg-yellow-500 text-black rounded-full font-black text-[10px] uppercase tracking-widest hover:bg-yellow-400 transition">⭐ Kirim Review</button>
            </div>
        </form>
    </div>
</div>

<style>
:root {
    --status-green: #22c55e; --status-red: #ef4444; --status-pink: #ec4899; --status-gray: #6b7280;
}
</style>

<script>
let reviewFiles = [];

function openReviewModal(orderId, productId, productName) {
    document.getElementById('review-order-id').value = orderId;
    document.getElementById('review-product-id').value = productId;
    document.getElementById('review-product-name').textContent = productName;
    setRating(5);
    reviewFiles = [];
    document.getElementById('review-image-previews').innerHTML = '';
    document.getElementById('image-count').textContent = '0/5 foto';
    document.getElementById('review-images-input').value = '';
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

function previewReviewImages(input) {
    const container = document.getElementById('review-image-previews');
    const count = document.getElementById('image-count');
    const dt = new DataTransfer();
    const existingFiles = Array.from(reviewFiles);
    const newFiles = Array.from(input.files).slice(0, 5 - existingFiles.length);
    
    reviewFiles = [...existingFiles, ...newFiles];
    reviewFiles.slice(0, 5).forEach((file, i) => {
        dt.items.add(file);
    });
    input.files = dt.files;
    
    container.innerHTML = '';
    reviewFiles.slice(0, 5).forEach((file, i) => {
        const reader = new FileReader();
        reader.onload = e => {
            const div = document.createElement('div');
            div.className = 'relative w-16 h-16 rounded-xl overflow-hidden border border-white/10 flex-shrink-0';
            div.innerHTML = `
                <img src="${e.target.result}" class="w-full h-full object-cover">
                <button type="button" onclick="removeReviewImage(${i})" class="absolute top-0.5 right-0.5 w-4 h-4 bg-red-500/80 rounded-full text-white text-[7px] flex items-center justify-center font-bold hover:bg-red-600 transition">×</button>
            `;
            container.appendChild(div);
        };
        reader.readAsDataURL(file);
    });
    count.textContent = `${Math.min(reviewFiles.length, 5)}/5 foto`;
}

function removeReviewImage(index) {
    reviewFiles.splice(index, 1);
    const input = document.getElementById('review-images-input');
    const dt = new DataTransfer();
    reviewFiles.slice(0, 5).forEach(f => dt.items.add(f));
    input.files = dt.files;
    
    const container = document.getElementById('review-image-previews');
    const count = document.getElementById('image-count');
    container.innerHTML = '';
    reviewFiles.slice(0, 5).forEach((file, i) => {
        const reader = new FileReader();
        reader.onload = e => {
            const div = document.createElement('div');
            div.className = 'relative w-16 h-16 rounded-xl overflow-hidden border border-white/10 flex-shrink-0';
            div.innerHTML = `
                <img src="${e.target.result}" class="w-full h-full object-cover">
                <button type="button" onclick="removeReviewImage(${i})" class="absolute top-0.5 right-0.5 w-4 h-4 bg-red-500/80 rounded-full text-white text-[7px] flex items-center justify-center font-bold hover:bg-red-600 transition">×</button>
            `;
            container.appendChild(div);
        };
        reader.readAsDataURL(file);
    });
    count.textContent = `${reviewFiles.length}/5 foto`;
}

// Auto-hide toasts
document.querySelectorAll('[class*="animate-fade-up"]').forEach(el => {
    setTimeout(() => { el.style.opacity = '0'; el.style.transition = 'opacity 0.5s'; setTimeout(() => el.remove(), 500); }, 5000);
});
</script>
@endsection