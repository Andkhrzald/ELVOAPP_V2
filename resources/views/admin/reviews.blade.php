@extends('layouts.app')

@section('content')
<div class="min-h-screen bg-[#0f0f0f] text-gray-300 pb-12">

    {{-- Toast --}}
    @if(session('success'))
    <div class="fixed top-20 right-6 z-[100] bg-green-500/20 border border-green-500/30 text-green-400 px-6 py-4 rounded-2xl shadow-2xl backdrop-blur-xl flex items-center gap-3" style="animation: slideIn 0.5s ease-out">
        <span class="text-sm font-bold">{{ session('success') }}</span>
        <button onclick="this.parentElement.remove()" class="ml-2">&times;</button>
    </div>
    @endif

    {{-- Header --}}
    <div class="max-w-7xl mx-auto flex flex-col md:flex-row md:items-end justify-between mb-8 px-4 lg:px-0 pt-10 gap-4">
        <div>
            <h1 class="text-3xl font-bold text-white tracking-tight">Review Pelanggan</h1>
            <p class="text-[11px] font-semibold text-elvo-primary uppercase tracking-[0.2em] mt-1">Rating & Ulasan Produk</p>
        </div>
        <form action="{{ route('admin.reviews') }}" method="GET" class="flex gap-3">
            <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari review..."
                class="bg-elvo-surface border border-white/10 rounded-xl px-4 py-2.5 text-sm text-white placeholder-gray-600 w-56 focus:outline-none focus:border-elvo-primary/50">
            <button class="px-4 py-2.5 btn-primary text-xs">Cari</button>
        </form>
    </div>

    {{-- Stats --}}
    <div class="max-w-7xl mx-auto grid grid-cols-1 md:grid-cols-3 gap-6 mb-8 px-4 lg:px-0">
        <div class="p-6 bg-elvo-surface rounded-2xl border border-white/[0.06] card-hover">
            <p class="text-[10px] font-black text-gray-500 uppercase tracking-widest">Total Review</p>
            <p class="text-3xl font-black text-white mt-2">{{ $totalReviews }}</p>
        </div>
        <div class="p-6 bg-elvo-surface rounded-2xl border border-white/[0.06] card-hover">
            <p class="text-[10px] font-black text-gray-500 uppercase tracking-widest">Rating Rata-rata</p>
            <div class="flex items-center gap-2 mt-2">
                <p class="text-3xl font-black text-yellow-500">{{ number_format($avgRating, 1) }}</p>
                <div class="flex gap-0.5">
                    @for($i = 1; $i <= 5; $i++)
                    <svg class="w-5 h-5 {{ $i <= round($avgRating) ? 'text-yellow-500' : 'text-gray-700' }}" fill="currentColor" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/></svg>
                    @endfor
                </div>
            </div>
        </div>
        <div class="p-6 bg-elvo-surface rounded-2xl border border-white/[0.06] card-hover">
            <p class="text-[10px] font-black text-gray-500 uppercase tracking-widest">Review 5 Bintang</p>
            <p class="text-3xl font-black text-green-500 mt-2">{{ $fiveStars }}</p>
        </div>
    </div>

    {{-- Filter Stars --}}
    <div class="max-w-7xl mx-auto px-4 lg:px-0 mb-6 flex gap-3 flex-wrap">
        <a href="{{ route('admin.reviews') }}" class="px-4 py-2 rounded-xl text-xs font-bold transition {{ !request('rating') ? 'bg-yellow-500 text-black' : 'bg-elvo-surface text-gray-400 border border-white/[0.06]' }}">Semua</a>
        @for($i = 5; $i >= 1; $i--)
        <a href="{{ route('admin.reviews', ['rating' => $i]) }}" class="flex items-center gap-1 px-4 py-2 rounded-xl text-xs font-bold transition {{ request('rating') == $i ? 'bg-yellow-500 text-black' : 'bg-elvo-surface text-gray-400 border border-white/[0.06]' }}">
            {{ $i }} <svg class="w-3 h-3" fill="currentColor" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/></svg>
        </a>
        @endfor
    </div>

    {{-- Reviews List --}}
    <div class="max-w-7xl mx-auto px-4 lg:px-0 space-y-4">
        @forelse($reviews as $review)
        <div class="bg-elvo-surface rounded-2xl border border-white/[0.06] p-6 hover:border-yellow-500/10 transition card-hover">
            <div class="flex flex-col md:flex-row md:items-start justify-between gap-4">
                <div class="flex items-start gap-4 flex-1">
                    <div class="w-10 h-10 rounded-full bg-yellow-500/10 flex items-center justify-center text-yellow-500 font-bold text-sm flex-shrink-0">
                        {{ strtoupper(substr($review->user->name ?? 'U', 0, 1)) }}
                    </div>
                    <div class="flex-1">
                        <div class="flex items-center gap-3 mb-1">
                            <p class="text-sm font-bold text-white">{{ $review->user->name ?? 'Unknown' }}</p>
                            <span class="text-[10px] text-gray-600">{{ $review->created_at->diffForHumans() }}</span>
                        </div>
                        <div class="flex gap-0.5 mb-2">
                            @for($i = 1; $i <= 5; $i++)
                            <svg class="w-4 h-4 {{ $i <= $review->rating ? 'text-yellow-500' : 'text-gray-700' }}" fill="currentColor" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/></svg>
                            @endfor
                        </div>
                        <p class="text-sm text-gray-400 italic">"{{ $review->comment ?? 'Tidak ada komentar' }}"</p>
                        <p class="text-[10px] text-gray-600 mt-2">Produk: <span class="text-white font-bold">{{ $review->product->name ?? '-' }}</span> · Order #{{ $review->order->order_number ?? '-' }}</p>
                    </div>
                </div>
                <form action="{{ route('admin.reviews.destroy', $review->id) }}" method="POST" onsubmit="return confirm('Hapus review ini?')">
                    @csrf @method('DELETE')
                    <button class="px-3 py-1.5 btn-danger text-[10px] font-bold uppercase">Hapus</button>
                </form>
            </div>
        </div>
        @empty
        <div class="bg-elvo-surface rounded-2xl border border-white/[0.06] p-12 text-center">
            <svg class="w-16 h-16 mx-auto text-gray-800 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1" d="M11.049 2.927c.3-.921 1.603-.921 1.902 0l1.519 4.674a1 1 0 00.95.69h4.915c.969 0 1.371 1.24.588 1.81l-3.976 2.888a1 1 0 00-.363 1.118l1.518 4.674c.3.922-.755 1.688-1.538 1.118l-3.976-2.888a1 1 0 00-1.176 0l-3.976 2.888c-.783.57-1.838-.197-1.538-1.118l1.518-4.674a1 1 0 00-.363-1.118l-3.976-2.888c-.784-.57-.38-1.81.588-1.81h4.914a1 1 0 00.951-.69l1.519-4.674z"/></svg>
            <p class="text-gray-600 font-bold">Belum ada review dari pelanggan</p>
        </div>
        @endforelse

        @if($reviews->hasPages())
        <div class="mt-6">{{ $reviews->withQueryString()->links() }}</div>
        @endif
    </div>
</div>

<style>
@keyframes slideIn { from { transform: translateX(100%); opacity: 0; } to { transform: translateX(0); opacity: 1; } }
</style>
@endsection
