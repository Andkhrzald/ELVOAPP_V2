@extends('layouts.customer')

@section('content')
<section class="pt-32 pb-10 px-8 text-center" data-aos="fade-down">
    <p class="text-[10px] font-black uppercase tracking-[0.5em] text-gray-500 mb-4">Your Collection</p>
    <h2 class="text-6xl md:text-8xl font-black italic uppercase tracking-tighter text-white">
        Wishlist
    </h2>
</section>

<section class="pb-32 px-8">
    <div class="container mx-auto">
        @if($items->isEmpty())
        <div class="text-center py-24" data-aos="zoom-in">
            <svg class="w-20 h-20 mx-auto text-gray-800 mb-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"/>
            </svg>
            <p class="text-gray-500 text-[10px] uppercase tracking-widest font-bold mb-2">Wishlist masih kosong</p>
            <p class="text-gray-700 text-[9px] uppercase tracking-widest mb-6">Simpan produk favoritmu di sini (max 20)</p>
            <a href="{{ route('shop.index') }}" class="inline-block px-8 py-3 bg-white text-black text-[10px] font-black uppercase tracking-widest rounded-full">Jelajahi Produk</a>
        </div>
        @else
        <div class="flex items-center justify-between mb-8 px-1">
            <p class="text-[10px] text-gray-500 uppercase tracking-widest font-bold">{{ $items->count() }} / 20 produk</p>
            <a href="{{ route('shop.index') }}" class="text-[10px] font-bold text-elvo-primary hover:text-white uppercase tracking-widest transition-colors">+ Tambah Produk</a>
        </div>
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-x-6 gap-y-12">
            @foreach($items as $item)
            <div class="group" data-aos="fade-up" data-aos-delay="{{ ($loop->iteration % 4) * 80 }}">
                <div class="relative aspect-[3/4] bg-[#0f0f0f] rounded-[24px] overflow-hidden mb-5 border border-white/5 group-hover:border-white/20 transition-all duration-500">
                    @if($item->product->image)
                    <img src="{{ asset('uploads/' . $item->product->image) }}" alt="{{ $item->product->name }}" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-700">
                    @else
                    <div class="w-full h-full flex items-center justify-center bg-gradient-to-br from-[#111] to-[#0a0a0a]">
                        <span class="text-white/[0.03] text-8xl font-black italic uppercase">ELVO</span>
                    </div>
                    @endif

                    {{-- Wishlist indicator --}}
                    <div class="absolute top-4 left-4 px-3 py-1 bg-red-500/80 text-white text-[8px] font-black uppercase tracking-widest rounded-full">
                        ❤ Tersimpan
                    </div>

                    {{-- Hover Overlay --}}
                    <div class="absolute inset-0 bg-gradient-to-t from-black/90 via-black/40 to-transparent opacity-0 group-hover:opacity-100 transition-opacity duration-500 flex flex-col items-center justify-end p-6 gap-3">
                        <button onclick="addToCart({{ $item->product->id }}, '{{ addslashes($item->product->name) }}', {{ $item->product->price }}, '{{ $item->product->image }}')"
                            class="w-full bg-white text-black py-4 rounded-full text-[10px] font-black uppercase tracking-widest transform translate-y-4 group-hover:translate-y-0 transition-all duration-500 shadow-2xl hover:bg-gray-200">
                            Add to Cart
                        </button>
                        <form action="{{ route('wishlist.move', $item->id) }}" method="POST" class="w-full transform translate-y-4 group-hover:translate-y-0 transition-all duration-700">
                            @csrf
                            <button type="submit" class="w-full py-3 rounded-full border border-white/20 text-white text-[10px] font-black uppercase tracking-widest hover:bg-white/10 transition-all">
                                Pindahkan ke Cart
                            </button>
                        </form>
                    </div>
                </div>

                <div class="space-y-1.5 px-1">
                    <div class="flex justify-between items-start gap-2">
                        <h3 class="font-bold uppercase tracking-tight text-sm text-white leading-tight flex-1">
                            {{ $item->product->name }}
                        </h3>
                        <span class="font-black text-sm text-white whitespace-nowrap">Rp {{ number_format($item->product->price, 0, ',', '.') }}</span>
                    </div>
                    <div class="flex items-center justify-between">
                        <p class="text-[9px] font-bold text-gray-600 uppercase tracking-[0.2em]">{{ $item->product->color ?? $item->product->category->name ?? '' }}</p>
                        <p class="text-[9px] text-gray-700">Stok: {{ $item->product->stock }}</p>
                    </div>
                    {{-- Remove from wishlist --}}
                    <form action="{{ route('wishlist.toggle') }}" method="POST" class="mt-2">
                        @csrf
                        <input type="hidden" name="product_id" value="{{ $item->product_id }}">
                        <button type="submit" class="text-[9px] text-red-500 hover:text-red-400 font-bold uppercase tracking-widest transition-colors">
                            ✕ Hapus
                        </button>
                    </form>
                </div>
            </div>
            @endforeach
        </div>
        @endif
    </div>
</section>

{{-- Cart Toast --}}
<div id="cart-toast" class="fixed bottom-6 right-6 z-[100] bg-white text-black px-6 py-4 rounded-2xl shadow-2xl flex items-center gap-3 transform translate-y-20 opacity-0 transition-all duration-500">
    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
    <span class="text-[10px] font-black uppercase tracking-widest" id="cart-toast-text">Added!</span>
</div>

<script>
function getCart() { return JSON.parse(localStorage.getItem('elvo_cart') || '[]'); }
function saveCart(cart) { localStorage.setItem('elvo_cart', JSON.stringify(cart)); }
function addToCart(id, name, price, image) {
    let cart = getCart();
    let existing = cart.find(item => item.id === id);
    if (existing) { existing.qty += 1; } else { cart.push({ id, name, price, image, qty: 1 }); }
    saveCart(cart);
    updateCartBadge();
    const toast = document.getElementById('cart-toast');
    document.getElementById('cart-toast-text').textContent = name + ' added!';
    toast.classList.remove('translate-y-20', 'opacity-0');
    setTimeout(() => toast.classList.add('translate-y-20', 'opacity-0'), 2000);
}
function updateCartBadge() {
    const cart = getCart();
    const badge = document.getElementById('cart-count');
    if (badge) { badge.textContent = cart.reduce((s,i) => s + i.qty, 0); badge.classList.toggle('hidden', badge.textContent == 0); }
}
document.addEventListener('DOMContentLoaded', updateCartBadge);
</script>
@endsection
