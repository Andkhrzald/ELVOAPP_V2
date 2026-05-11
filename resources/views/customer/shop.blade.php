@extends('layouts.customer')

@section('content')
{{-- Header --}}
<section class="pt-32 pb-10 px-8 text-center" data-aos="fade-down">
    <p class="text-[10px] font-black uppercase tracking-[0.5em] text-gray-500 mb-4">Official Collection</p>
    <h2 class="text-6xl md:text-8xl font-black italic uppercase tracking-tighter text-white">
        Our <span class="text-transparent" style="-webkit-text-stroke: 1px white;">Collection.</span>
    </h2>
</section>

{{-- Filter & Sort Bar --}}
<section class="px-8 pb-12">
    <div class="container mx-auto">
        <div class="bg-white/[0.03] border border-white/5 rounded-2xl p-6 flex flex-col lg:flex-row gap-6 items-start lg:items-center justify-between" data-aos="fade-up">

            {{-- Category Filter --}}
            <div class="flex flex-wrap items-center gap-3">
                <a href="{{ route('shop.index') }}" class="px-5 py-2.5 rounded-full text-[10px] font-black uppercase tracking-widest transition-all {{ !request('category') ? 'bg-white text-black' : 'bg-white/5 text-gray-400 border border-white/10 hover:bg-white/10 hover:text-white' }}">
                    All
                </a>
                @foreach($categories as $cat)
                <a href="{{ route('shop.index', ['category' => $cat->slug, 'sort' => request('sort')]) }}"
                    class="px-5 py-2.5 rounded-full text-[10px] font-black uppercase tracking-widest transition-all {{ request('category') === $cat->slug ? 'bg-white text-black' : 'bg-white/5 text-gray-400 border border-white/10 hover:bg-white/10 hover:text-white' }}">
                    {{ $cat->name }}
                </a>
                @endforeach
            </div>

            {{-- Sort & Search --}}
            <div class="flex items-center gap-4 w-full lg:w-auto">
                {{-- Search --}}
                <form action="{{ route('shop.index') }}" method="GET" class="relative flex-1 lg:w-56">
                    @if(request('category'))<input type="hidden" name="category" value="{{ request('category') }}">@endif
                    @if(request('sort'))<input type="hidden" name="sort" value="{{ request('sort') }}">@endif
                    <input type="text" name="search" value="{{ request('search') }}" placeholder="Search..."
                        class="w-full bg-white/5 border border-white/10 rounded-xl px-4 py-2.5 text-[10px] text-white placeholder-gray-600 font-bold uppercase tracking-widest focus:outline-none focus:border-white/30 transition">
                    <button type="submit" class="absolute right-3 top-1/2 -translate-y-1/2 text-gray-500 hover:text-white">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><circle cx="11" cy="11" r="8"/><path d="M21 21l-4.3-4.3"/></svg>
                    </button>
                </form>

                {{-- Sort Dropdown --}}
                <div class="relative group">
                    <button class="flex items-center gap-2 bg-white/5 border border-white/10 rounded-xl px-4 py-2.5 text-[10px] text-gray-400 font-bold uppercase tracking-widest hover:text-white transition whitespace-nowrap">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 4h13M3 8h9m-9 4h6m4 0l4-4m0 0l4 4m-4-4v12"/></svg>
                        {{ ['price_low'=>'Termurah','price_high'=>'Termahal','name'=>'A-Z'][request('sort')] ?? 'Terbaru' }}
                    </button>
                    <div class="absolute right-0 mt-2 w-40 bg-[#151515] border border-white/10 rounded-xl py-2 opacity-0 invisible group-hover:opacity-100 group-hover:visible transition-all duration-200 shadow-2xl z-50">
                        @foreach(['latest'=>'Terbaru','price_low'=>'Harga Terendah','price_high'=>'Harga Tertinggi','name'=>'Nama A-Z'] as $key => $label)
                        <a href="{{ route('shop.index', array_merge(request()->query(), ['sort' => $key])) }}"
                            class="block px-4 py-2 text-[10px] font-bold uppercase tracking-widest {{ request('sort', 'latest') === $key ? 'text-white bg-white/5' : 'text-gray-500 hover:text-white hover:bg-white/5' }} transition">
                            {{ $label }}
                        </a>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>

        {{-- Active Filters --}}
        @if(request('category') || request('search'))
        <div class="mt-4 flex items-center gap-3 text-[10px] text-gray-500 uppercase tracking-widest">
            <span>Filter aktif:</span>
            @if(request('category'))
            <span class="px-3 py-1 bg-white/5 rounded-full text-white font-bold">{{ request('category') }}</span>
            @endif
            @if(request('search'))
            <span class="px-3 py-1 bg-white/5 rounded-full text-white font-bold">"{{ request('search') }}"</span>
            @endif
            <a href="{{ route('shop.index') }}" class="text-red-500 hover:text-red-400 font-bold">× Reset</a>
            <span class="ml-auto text-gray-600">{{ $products->count() }} produk ditemukan</span>
        </div>
        @endif
    </div>
</section>

{{-- Product Grid --}}
<section class="pb-32 px-8">
    <div class="container mx-auto">
        @if($products->isEmpty())
        <div class="text-center py-24" data-aos="zoom-in">
            <svg class="w-20 h-20 mx-auto text-gray-800 mb-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
            <p class="text-gray-500 text-[10px] uppercase tracking-widest font-bold">Tidak ada produk ditemukan</p>
            <a href="{{ route('shop.index') }}" class="inline-block mt-6 px-8 py-3 bg-white text-black text-[10px] font-black uppercase tracking-widest rounded-full">Lihat Semua</a>
        </div>
        @else
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-x-6 gap-y-12">
            @foreach($products as $product)
            <div class="group cursor-pointer" data-aos="fade-up" data-aos-delay="{{ ($loop->iteration % 4) * 80 }}">
                <div class="relative aspect-[3/4] bg-[#0f0f0f] rounded-[24px] overflow-hidden mb-5 border border-white/5 group-hover:border-white/20 transition-all duration-500">
                    @if($product->image)
                    <img src="{{ asset('uploads/' . $product->image) }}" alt="{{ $product->name }}" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-700">
                    @else
                    <div class="w-full h-full flex items-center justify-center bg-gradient-to-br from-[#111] to-[#0a0a0a]">
                        <span class="text-white/[0.03] text-8xl font-black italic uppercase">ELVO</span>
                    </div>
                    @endif

                    {{-- Category Badge --}}
                    @if($product->category)
                    <div class="absolute top-4 left-4 px-3 py-1 bg-black/60 backdrop-blur-md text-white text-[8px] font-black uppercase tracking-widest rounded-full border border-white/10">
                        {{ $product->category->name }}
                    </div>
                    @endif

                    {{-- Stock Badge --}}
                    @if($product->stock <= 5)
                    <div class="absolute top-4 right-4 px-3 py-1 bg-red-500/80 text-white text-[8px] font-black uppercase tracking-widest rounded-full">
                        Sisa {{ $product->stock }}
                    </div>
                    @endif

                    {{-- Hover Overlay --}}
                    <div class="absolute inset-0 bg-gradient-to-t from-black/90 via-black/40 to-transparent opacity-0 group-hover:opacity-100 transition-opacity duration-500 flex flex-col items-center justify-end p-6">
                        <button onclick="addToCart({{ $product->id }}, '{{ addslashes($product->name) }}', {{ $product->price }}, '{{ $product->image }}')"
                            class="w-full bg-white text-black py-4 rounded-full text-[10px] font-black uppercase tracking-widest transform translate-y-4 group-hover:translate-y-0 transition-all duration-500 shadow-2xl hover:bg-gray-200">
                            Add to Cart
                        </button>
                        @if($product->description)
                        <p class="text-[9px] text-gray-400 text-center mt-3 line-clamp-2 transform translate-y-4 group-hover:translate-y-0 transition-all duration-700">{{ $product->description }}</p>
                        @endif
                    </div>
                </div>

                {{-- Product Info --}}
                <div class="space-y-1.5 px-1">
                    <div class="flex justify-between items-start gap-2">
                        <h3 class="font-bold uppercase tracking-tight text-sm text-white leading-tight flex-1">
                            {{ $product->name }}
                        </h3>
                        <span class="font-black text-sm text-white whitespace-nowrap">Rp {{ number_format($product->price, 0, ',', '.') }}</span>
                    </div>
                    {{-- Rating Stars --}}
                    @php
                    $avgRating = $product->reviews->avg('rating') ?? 0;
                    $reviewCount = $product->reviews->count();
                    @endphp
                    @if($reviewCount > 0)
                    <div class="flex items-center gap-1">
                        @for($i = 1; $i <= 5; $i++)
                        <svg class="w-3 h-3 {{ $i <= round($avgRating) ? 'text-yellow-500' : 'text-gray-700' }}" fill="currentColor" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/></svg>
                        @endfor
                        <span class="text-[9px] text-gray-500 ml-1">({{ $reviewCount }})</span>
                    </div>
                    @endif
                    <div class="flex items-center justify-between">
                        <p class="text-[9px] font-bold text-gray-600 uppercase tracking-[0.2em]">{{ $product->color ?? $product->category->name ?? '' }}</p>
                        <p class="text-[9px] text-gray-700">Stok: {{ $product->stock }}</p>
                    </div>
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
    const toast = document.getElementById('cart-toast');
    document.getElementById('cart-toast-text').textContent = name + ' added!';
    toast.classList.remove('translate-y-20', 'opacity-0');
    setTimeout(() => toast.classList.add('translate-y-20', 'opacity-0'), 2000);
}
</script>
@endsection