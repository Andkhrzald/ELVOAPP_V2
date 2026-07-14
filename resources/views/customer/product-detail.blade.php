@extends('layouts.customer')

@section('content')
<div class="min-h-screen text-white pt-20 pb-12 px-4 md:px-6">
    <div class="max-w-7xl mx-auto">

        {{-- Back Button --}}
        <a href="{{ route('shop.index') }}"
           class="inline-flex items-center gap-2 text-[10px] font-bold text-gray-500 hover:text-white uppercase tracking-widest mb-4 transition group">
            <svg class="w-3.5 h-3.5 transition-transform group-hover:-translate-x-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/></svg>
            Kembali ke Shop
        </a>

        @php
        $needsSize = $product->needsSize();
        $allImages = $product->images->pluck('image')->toArray();
        if ($product->image) {
            array_unshift($allImages, $product->image);
        }
        $allImages = array_unique($allImages);
        $mainImage = $allImages[0] ?? $product->image;
        @endphp

        {{-- Main Content --}}
        <div class="grid grid-cols-1 lg:grid-cols-12 gap-5 lg:gap-6">

            {{-- LEFT: Gallery --}}
            <div class="lg:col-span-6">
                <div class="relative aspect-[3/4] bg-[#0f0f0f] rounded-2xl overflow-hidden border border-white/5 mb-3 group">
                    <img id="main-product-image" src="{{ asset('uploads/' . $mainImage) }}" alt="{{ $product->name }}"
                         class="w-full h-full object-cover transition-all duration-500">
                    <button onclick="toggleWishlist({{ $product->id }})"
                        class="absolute top-4 right-4 w-9 h-9 rounded-full bg-black/60 backdrop-blur-md border border-white/10 flex items-center justify-center transition-all duration-300 hover:bg-white hover:scale-110 z-10"
                        id="wishlist-btn-{{ $product->id }}">
                        <svg class="w-4 h-4 {{ in_array($product->id, $wishlistIds) ? 'text-red-500 fill-red-500' : 'text-white' }} transition-colors" id="wishlist-icon-{{ $product->id }}" fill="{{ in_array($product->id, $wishlistIds) ? 'currentColor' : 'none' }}" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"/>
                        </svg>
                    </button>
                </div>

                {{-- Thumbnails --}}
                @if(count($allImages) > 1)
                <div class="flex gap-2 overflow-x-auto pb-1">
                    @foreach($allImages as $idx => $img)
                    <button onclick="changeImage('{{ asset('uploads/' . $img) }}', this)"
                        class="w-16 h-16 rounded-xl overflow-hidden border-2 border-white/10 hover:border-white/30 transition-all duration-300 flex-shrink-0 {{ $idx === 0 ? 'border-elvo-primary' : '' }} thumb-item">
                        <img src="{{ asset('uploads/' . $img) }}" class="w-full h-full object-cover">
                    </button>
                    @endforeach
                </div>
                @endif
            </div>

            {{-- RIGHT: Product Info --}}
            <div class="lg:col-span-6">
                {{-- Title --}}
                <h1 class="text-xl md:text-2xl font-bold uppercase tracking-tight text-white">{{ $product->name }}</h1>

                {{-- Price --}}
                <p class="text-2xl md:text-3xl font-black text-white mt-2" id="product-price">Rp {{ number_format($product->price, 0, ',', '.') }}</p>

                {{-- Rating + Sold Summary --}}
                <div class="flex items-center gap-4 text-sm flex-wrap mt-2">
                    <div class="flex items-center gap-1.5">
                        <div class="flex gap-0.5">
                            @for($i = 1; $i <= 5; $i++)
                            <svg class="w-4 h-4 {{ $i <= round($avgRating) ? 'text-yellow-500' : 'text-gray-700' }}" fill="currentColor" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/></svg>
                            @endfor
                        </div>
                        <span class="font-bold text-white">{{ number_format($avgRating, 1) }}</span>
                        <span class="text-gray-500">({{ $totalReviews }} ulasan)</span>
                    </div>
                    <span class="text-gray-700">|</span>
                    <span class="text-gray-400 flex items-center gap-1.5">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"/></svg>
                        Terjual {{ $totalSold }}
                    </span>
                </div>

                {{-- Variant Selectors --}}
                @if($product->activeVariants->count() > 0)
                @php
                $colorGroups = $product->activeVariants->groupBy('color');
                @endphp
                <div class="mt-4">
                {{-- Color --}}
                <div>
                    <p class="text-[10px] font-bold text-gray-500 uppercase tracking-widest mb-2">Pilih Warna <span id="selected-color-label" class="text-white"></span></p>
                    <div class="flex flex-wrap gap-2" id="color-selector">
                        @foreach($colorGroups as $color => $variants)
                        @php $first = $variants->first(); @endphp
                        <button type="button" data-color="{{ $color }}" data-hex="{{ $first->color_hex ?? '#000' }}"
                            onclick="selectColor('{{ $color }}', this)"
                            class="color-swatch w-10 h-10 rounded-full border-2 border-white/20 hover:border-white/60 transition-all"
                            style="background-color: {{ $first->color_hex ?? '#333' }}"
                            title="{{ $color }}"></button>
                        @endforeach
                    </div>
                </div>

                {{-- Size (only if needed based on category) --}}
                @if($needsSize)
                @php $uniqueSizes = $product->activeVariants->pluck('size')->unique()->filter(); @endphp
                <div class="mt-3">
                    <p class="text-[10px] font-bold text-gray-500 uppercase tracking-widest mb-2">Pilih Ukuran <span id="selected-size-label" class="text-white"></span></p>
                    <div class="flex flex-wrap gap-2" id="size-selector">
                        @foreach($uniqueSizes as $size)
                        <button type="button" data-size="{{ $size }}"
                            onclick="selectSize('{{ $size }}', this)"
                            class="size-btn px-4 py-2 rounded-xl border border-white/10 bg-white/5 text-sm font-bold text-gray-300 pointer-events-none opacity-30 transition-all">
                            {{ $size }}
                        </button>
                        @endforeach
                    </div>
                </div>
                @endif

                {{-- Stock Status --}}
                <div id="stock-display" class="text-sm font-bold mt-3 {{ $product->stock > 0 ? 'text-green-500' : 'text-red-500' }}">
                    @if($product->stock > 0) Stok tersedia @else Stok habis @endif
                </div>
                </div>
                @else
                {{-- Non-variant product --}}
                <div class="mt-4">
                    <p class="text-[10px] font-bold text-gray-500 uppercase tracking-widest mb-2">Stok</p>
                    <p class="text-sm font-bold {{ $product->stock > 0 ? 'text-green-500' : 'text-red-500' }}">
                        {{ $product->stock > 0 ? 'Stok: ' . $product->stock : 'Stok habis' }}
                    </p>
                </div>
                @endif

                {{-- Action Buttons --}}
                <div class="flex gap-3 mt-5">
                    <button onclick="handleDetailAddToCart()" id="add-to-cart-btn"
                        class="flex-1 py-3.5 rounded-full border-2 border-white/20 text-white text-[10px] font-black uppercase tracking-widest hover:bg-white hover:text-black transition-all">
                        + Keranjang
                    </button>
                    <button onclick="openBuyNowSheet()" id="buy-now-btn"
                        class="flex-1 py-3.5 rounded-full bg-white text-black text-[10px] font-black uppercase tracking-widest hover:bg-gray-200 transition-all">
                        Beli Sekarang
                    </button>
                </div>

                {{-- Description --}}
                <div class="pt-4 mt-6 border-t border-white/5">
                    <p class="text-[10px] font-bold text-gray-500 uppercase tracking-widest mb-2">Deskripsi Produk</p>
                    <div class="text-sm text-gray-400 leading-relaxed">
                        <div id="product-description" class="line-clamp-3">
                            {{ $product->description ?? 'Tidak ada deskripsi' }}
                        </div>
                        @if(strlen($product->description ?? '') > 200)
                        <button onclick="document.getElementById('product-description').classList.toggle('line-clamp-3'); this.textContent = this.textContent === 'Baca selengkapnya' ? 'Tutup' : 'Baca selengkapnya'"
                            class="text-[10px] font-bold text-elvo-primary hover:text-white mt-1 transition">Baca selengkapnya</button>
                        @endif
                    </div>
                </div>
            </div>
        </div>

        {{-- REVIEWS --}}
        <div class="mt-8">
            <h2 class="text-lg font-black uppercase tracking-tight text-white mb-6">Ulasan Pembeli</h2>

            {{-- Rating Summary --}}
            <div class="bg-white/[0.03] border border-white/5 rounded-2xl p-5 mb-6">
                <div class="flex flex-col md:flex-row gap-6">
                    <div class="text-center md:text-left min-w-[120px]">
                        <p class="text-4xl font-black text-white">{{ number_format($avgRating, 1) }}</p>
                        <div class="flex gap-0.5 justify-center md:justify-start my-1">
                            @for($i = 1; $i <= 5; $i++)
                            <svg class="w-4 h-4 {{ $i <= round($avgRating) ? 'text-yellow-500' : 'text-gray-700' }}" fill="currentColor" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/></svg>
                            @endfor
                        </div>
                        <p class="text-[10px] text-gray-500 font-bold tracking-widest uppercase">{{ $totalReviews }} ulasan</p>
                    </div>
                    <div class="flex-1 space-y-1.5">
                        @foreach([5,4,3,2,1] as $star)
                        @php
                        $count = $ratingDistribution[$star] ?? 0;
                        $pct = $totalReviews > 0 ? ($count / $totalReviews) * 100 : 0;
                        @endphp
                        <div class="flex items-center gap-2 text-xs">
                            <span class="w-4 text-gray-400 font-bold">{{ $star }}</span>
                            <svg class="w-3 h-3 text-yellow-500" fill="currentColor" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/></svg>
                            <div class="flex-1 h-2 bg-white/5 rounded-full overflow-hidden">
                                <div class="h-full bg-yellow-500 rounded-full" style="width: {{ $pct }}%"></div>
                            </div>
                            <span class="w-6 text-gray-500 text-right">{{ $count }}</span>
                        </div>
                        @endforeach
                    </div>
                </div>
            </div>

            {{-- Review Filter --}}
            <div class="flex flex-wrap gap-2 mb-4" id="review-filter">
                <button onclick="filterReviews(0)" class="review-filter-btn px-4 py-1.5 rounded-full text-[10px] font-bold uppercase tracking-widest transition bg-white text-black" data-rating="0">Semua</button>
                @foreach([5,4,3,2,1] as $star)
                <button onclick="filterReviews({{ $star }})" class="review-filter-btn px-4 py-1.5 rounded-full text-[10px] font-bold uppercase tracking-widest transition bg-white/5 text-gray-400 border border-white/10 hover:bg-white/10" data-rating="{{ $star }}">{{ $star }}★</button>
                @endforeach
            </div>

            {{-- Review Cards --}}
            <div id="review-list" class="space-y-3">
                @forelse($product->reviews as $review)
                <div class="review-card bg-white/[0.02] border border-white/5 rounded-xl p-4" data-rating="{{ $review->rating }}">
                    <div class="flex items-start gap-3 mb-3">
                        <div class="w-9 h-9 rounded-full bg-elvo-primary/20 flex items-center justify-center text-elvo-primary font-bold text-sm flex-shrink-0">
                            {{ strtoupper(substr($review->user->name ?? 'U', 0, 1)) }}
                        </div>
                        <div class="flex-1">
                            <div class="flex items-center gap-2">
                                <p class="text-sm font-bold text-white">{{ $review->user->name ?? 'Anonymous' }}</p>
                                <span class="text-[9px] text-gray-600">{{ $review->created_at->diffForHumans() }}</span>
                            </div>
                            <div class="flex gap-0.5 mt-0.5">
                                @for($i = 1; $i <= 5; $i++)
                                <svg class="w-3.5 h-3.5 {{ $i <= $review->rating ? 'text-yellow-500' : 'text-gray-700' }}" fill="currentColor" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/></svg>
                                @endfor
                            </div>
                        </div>
                    </div>
                    @if($review->comment)
                    <p class="text-sm text-gray-400 mb-3">"{{ $review->comment }}"</p>
                    @endif
                    @if($review->images->count() > 0)
                    <div class="flex gap-2 flex-wrap">
                        @foreach($review->images as $rimg)
                        <button onclick="openLightbox('{{ asset('uploads/' . $rimg->image) }}')"
                            class="w-16 h-16 rounded-xl overflow-hidden border border-white/10 hover:scale-105 transition-all duration-300 flex-shrink-0">
                            <img src="{{ asset('uploads/' . $rimg->image) }}" class="w-full h-full object-cover">
                        </button>
                        @endforeach
                    </div>
                    @endif
                </div>
                @empty
                <div class="text-center py-12 border border-white/5 rounded-2xl">
                    <svg class="w-12 h-12 mx-auto text-gray-800 mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1" d="M11.049 2.927c.3-.921 1.603-.921 1.902 0l1.519 4.674a1 1 0 00.95.69h4.915c.969 0 1.371 1.24.588 1.81l-3.976 2.888a1 1 0 00-.363 1.118l1.518 4.674c.3.922-.755 1.688-1.538 1.118l-3.976-2.888a1 1 0 00-1.176 0l-3.976 2.888c-.783.57-1.838-.197-1.538-1.118l1.518-4.674a1 1 0 00-.363-1.118l-3.976-2.888c-.784-.57-.38-1.81.588-1.81h4.914a1 1 0 00.951-.69l1.519-4.674z"/></svg>
                    <p class="text-gray-500 text-[10px] font-bold uppercase tracking-widest">Belum ada ulasan</p>
                    <p class="text-gray-700 text-[9px] mt-1">Jadilah yang pertama memberi ulasan untuk produk ini!</p>
                </div>
                @endforelse
            </div>
        </div>

        {{-- RELATED PRODUCTS --}}
        @if($relatedProducts->count() > 0)
        <div class="mt-8">
            <h2 class="text-lg font-black uppercase tracking-tight text-white mb-4">Produk Terkait</h2>
            <div class="grid grid-cols-2 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-x-5 gap-y-6">
                @foreach($relatedProducts as $rel)
                @php $relAvg = $rel->reviews->avg('rating') ?? 0; $relCount = $rel->reviews->count(); @endphp
                <div class="group">
                    <a href="{{ route('product.detail', $rel->slug) }}" class="block">
                        <div class="relative aspect-[3/4] bg-[#0f0f0f] rounded-xl overflow-hidden mb-2 border border-white/5 group-hover:border-white/20 transition">
                            @if($rel->image)
                            <img src="{{ asset('uploads/' . $rel->image) }}" class="w-full h-full object-cover group-hover:scale-105 transition duration-700">
                            @else
                            <div class="w-full h-full flex items-center justify-center bg-gradient-to-br from-[#111] to-[#0a0a0a]">
                                <span class="text-white/[0.03] text-6xl font-black italic uppercase">ELVO</span>
                            </div>
                            @endif
                        </div>
                    </a>
                    <div class="space-y-0.5 px-0.5">
                        <h3 class="font-bold text-sm text-white leading-tight">
                            <a href="{{ route('product.detail', $rel->slug) }}" class="hover:text-gray-300 transition">{{ $rel->name }}</a>
                        </h3>
                        <p class="font-black text-sm text-white">Rp {{ number_format($rel->price, 0, ',', '.') }}</p>
                        <div class="flex items-center gap-1 text-[9px]">
                            @if($relCount > 0)
                            <div class="flex gap-0.5">
                                @for($i = 1; $i <= 5; $i++)
                                <svg class="w-3 h-3 {{ $i <= round($relAvg) ? 'text-yellow-500' : 'text-gray-700' }}" fill="currentColor" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/></svg>
                                @endfor
                            </div>
                            <span class="text-gray-500">({{ $relCount }})</span>
                            @endif
                        </div>
                    </div>
                </div>
                @endforeach
            </div>
        </div>
        @endif
    </div>
</div>

{{-- Sticky Bottom Bar (Mobile) --}}
<div id="mobile-sticky-bar" class="fixed bottom-0 left-0 right-0 z-50 bg-[#0f0f0f] border-t border-white/10 px-4 py-3 flex items-center gap-3 lg:hidden">
    <div class="flex-1">
        <p class="font-black text-white" id="sticky-price">Rp {{ number_format($product->price, 0, ',', '.') }}</p>
        <p class="text-[9px] text-gray-500" id="sticky-stock">{{ $product->stock > 0 ? 'Stok tersedia' : 'Stok habis' }}</p>
    </div>
    <button onclick="handleDetailAddToCart()" class="px-5 py-2.5 rounded-full border-2 border-white/20 text-white text-[10px] font-black uppercase tracking-widest hover:bg-white hover:text-black transition">
        + Keranjang
    </button>
    <button onclick="openBuyNowSheet()" class="px-5 py-2.5 rounded-full bg-white text-black text-[10px] font-black uppercase tracking-widest hover:bg-gray-200 transition">
        Beli
    </button>
</div>

{{-- BUY NOW BOTTOM SHEET --}}
<div id="buy-now-modal" class="fixed inset-0 z-[160] hidden items-end justify-center bg-black/80 backdrop-blur-md">
    <div id="buy-now-inner" class="relative w-full max-w-lg bg-elvo-surface rounded-t-[2rem] shadow-2xl border border-white/10 translate-y-full opacity-0 transition-all duration-300 max-h-[85vh] overflow-y-auto" style="margin-top: auto;">
        <div class="sticky top-0 bg-elvo-surface z-10 flex items-center justify-between p-6 pb-2 border-b border-white/5">
            <h3 class="text-sm font-black text-white uppercase tracking-widest">Checkout</h3>
            <button onclick="closeBuyNowSheet()" class="w-8 h-8 bg-white/5 text-gray-400 hover:text-white rounded-full flex items-center justify-center">&times;</button>
        </div>
        <div class="p-6" id="bn-body">
            {{-- populated by JS --}}
        </div>
    </div>
</div>

{{-- REVIEW LIGHTBOX --}}
<div id="lightbox-modal" class="fixed inset-0 z-[170] hidden items-center justify-center bg-black/95 backdrop-blur-xl">
    <button onclick="closeLightbox()" class="absolute top-6 right-6 text-white/60 hover:text-white transition z-10">
        <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
    </button>
    <img id="lightbox-img" src="" class="max-w-[90vw] max-h-[85vh] object-contain rounded-2xl">
</div>

{{-- Cart Toast --}}
<div id="cart-toast" class="fixed bottom-20 lg:bottom-6 right-6 z-[190] bg-white text-black px-6 py-4 rounded-2xl shadow-2xl flex items-center gap-3 transform translate-y-20 opacity-0 transition-all duration-500">
    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
    <span class="text-[10px] font-black uppercase tracking-widest" id="cart-toast-text">Added!</span>
</div>

<script>
let selectedColor = null;
let selectedSize = null;
let selectedVariantId = null;
let selectedPrice = {{ $product->price }};
let selectedImage = '{{ $mainImage }}';
const isVariants = {{ $product->activeVariants->count() > 0 ? 'true' : 'false' }};
const needsSize = {{ $needsSize ? 'true' : 'false' }};
const productData = {
    id: {{ $product->id }},
    name: '{{ addslashes($product->name) }}',
    slug: '{{ $product->slug }}',
    price: {{ $product->price }},
    stock: {{ $product->stock }},
    image: '{{ $mainImage }}'
};

@if($product->activeVariants->count() > 0)
const variantsRaw = @json($product->activeVariants);

function selectColor(color, btn) {
    document.querySelectorAll('.color-swatch').forEach(el => {
        el.classList.remove('border-white', 'ring-2', 'ring-white/40');
        el.classList.add('border-white/20');
    });
    btn.classList.remove('border-white/20');
    btn.classList.add('border-white', 'ring-2', 'ring-white/40');

    selectedColor = color;
    document.getElementById('selected-color-label').textContent = color;

    const colorVariants = variantsRaw.filter(v => v.color === color);

    if (needsSize) {
        document.querySelectorAll('.size-btn').forEach(el => {
            const btnSize = el.dataset.size;
            const variant = colorVariants.find(v => v.size === btnSize);
            if (variant && variant.stock > 0) {
                el.classList.remove('opacity-30', 'pointer-events-none', 'border-red-500/30');
                el.classList.add('border-white/10', 'hover:border-white/30');
            } else {
                el.classList.add('opacity-30', 'pointer-events-none', 'border-red-500/30');
                el.classList.remove('border-white/10', 'hover:border-white/30');
            }
        });

        const firstAvailable = document.querySelector('.size-btn:not(.opacity-30)');
        if (firstAvailable) {
            selectSize(firstAvailable.dataset.size, firstAvailable);
        } else {
            selectedSize = null;
            selectedVariantId = null;
            document.getElementById('selected-size-label').textContent = '';
            updateUI();
        }
    } else {
        const variant = colorVariants[0];
        if (variant) {
            selectedVariantId = variant.id;
            selectedSize = null;
            selectedPrice = variant.price || productData.price;
        }
    }

    const colorVariantWithImage = colorVariants.find(v => v.image);
    if (colorVariantWithImage) {
        selectedImage = colorVariantWithImage.image;
        document.getElementById('main-product-image').src = '/uploads/' + colorVariantWithImage.image;
    }

    updateUI();
}

function selectSize(size, btn) {
    if (btn.classList.contains('opacity-30')) return;

    document.querySelectorAll('.size-btn').forEach(el => {
        el.classList.remove('bg-white', 'text-black', 'border-white');
        el.classList.add('bg-white/5', 'text-gray-300', 'border-white/10');
    });
    btn.classList.remove('bg-white/5', 'text-gray-300', 'border-white/10');
    btn.classList.add('bg-white', 'text-black', 'border-white');

    selectedSize = size;

    const variant = variantsRaw.find(v => v.color === selectedColor && v.size === size);
    if (variant) {
        selectedVariantId = variant.id;
        selectedPrice = variant.price || productData.price;
        document.getElementById('selected-size-label').textContent = size;
        if (variant.image) {
            selectedImage = variant.image;
            document.getElementById('main-product-image').src = '/uploads/' + variant.image;
        }
    }

    updateUI();
}

const firstColorBtn = document.querySelector('.color-swatch');
if (firstColorBtn) {
    selectColor(firstColorBtn.dataset.color, firstColorBtn);
}
@endif

function updateUI() {
    let stock = 0;
    let price = productData.price;

    if (isVariants && selectedVariantId) {
        const variant = variantsRaw.find(v => v.id === selectedVariantId);
        if (variant) {
            stock = variant.stock;
            price = variant.price || productData.price;
        }
    } else {
        stock = {{ $product->stock }};
    }

    const formattedPrice = 'Rp ' + formatPrice(price);
    document.querySelectorAll('#product-price, #sticky-price').forEach(el => el.textContent = formattedPrice);

    const stockEl = document.getElementById('stock-display');
    const stickyStock = document.getElementById('sticky-stock');
    if (stock > 0) {
        if (stockEl) { stockEl.textContent = 'Stok: ' + stock; stockEl.className = 'text-sm font-bold text-green-500'; }
        if (stickyStock) { stickyStock.textContent = 'Stok tersedia'; stickyStock.className = 'text-[9px] text-green-500'; }
    } else {
        if (stockEl) { stockEl.textContent = 'Stok habis'; stockEl.className = 'text-sm font-bold text-red-500'; }
        if (stickyStock) { stickyStock.textContent = 'Stok habis'; stickyStock.className = 'text-[9px] text-red-500'; }
    }

    const btns = ['add-to-cart-btn', 'buy-now-btn'].map(id => document.getElementById(id));
    btns.forEach(btn => {
        if (!btn) return;
        if (stock <= 0 || (isVariants && !selectedVariantId)) {
            btn.disabled = true;
            btn.classList.add('opacity-30', 'pointer-events-none');
        } else {
            btn.disabled = false;
            btn.classList.remove('opacity-30', 'pointer-events-none');
        }
    });
}

function changeImage(src, btn) {
    document.getElementById('main-product-image').style.opacity = '0';
    setTimeout(() => {
        document.getElementById('main-product-image').src = src;
        document.getElementById('main-product-image').style.opacity = '1';
    }, 200);
    document.querySelectorAll('.thumb-item').forEach(el => {
        el.classList.remove('border-elvo-primary');
        el.classList.add('border-white/10');
    });
    if (btn) {
        btn.classList.remove('border-white/10');
        btn.classList.add('border-elvo-primary');
    }
}

// ── Cart Functions ──
function getCart() { return JSON.parse(localStorage.getItem('elvo_cart') || '[]'); }
function saveCart(cart) { localStorage.setItem('elvo_cart', JSON.stringify(cart)); }

function formatPrice(num) {
    return Math.round(num).toString().replace(/\B(?=(\d{3})+(?!\d))/g, '.');
}

function handleDetailAddToCart() {
    let cart = getCart();
    let variantId = isVariants ? selectedVariantId : null;
    let key = variantId ? 'p' + productData.id + '_v' + variantId : 'p' + productData.id;
    let variantLabel = '';

    if (isVariants) {
        if (!selectedVariantId) {
            if (needsSize) {
                alert('Silakan pilih warna dan ukuran terlebih dahulu');
            } else {
                alert('Silakan pilih warna terlebih dahulu');
            }
            return;
        }
        const v = variantsRaw.find(x => x.id === selectedVariantId);
        if (v) variantLabel = [v.color, v.size].filter(Boolean).join(' / ');
    }

    let existing = cart.find(item => item.key === key);
    if (existing) {
        existing.qty += 1;
    } else {
        cart.push({
            key: key,
            id: productData.id,
            variant_id: variantId,
            name: productData.name,
            variant_label: variantLabel,
            price: selectedPrice,
            image: selectedImage,
            qty: 1
        });
    }
    saveCart(cart);
    updateCartBadge();
    showToast(productData.name + ' ditambahkan!');
}

function showToast(msg) {
    const toast = document.getElementById('cart-toast');
    document.getElementById('cart-toast-text').textContent = msg;
    toast.classList.remove('translate-y-20', 'opacity-0');
    setTimeout(() => toast.classList.add('translate-y-20', 'opacity-0'), 2000);
}

// ── BUY NOW Bottom Sheet ──
function openBuyNowSheet() {
    if (isVariants && !selectedVariantId) {
        if (needsSize) {
            alert('Silakan pilih warna dan ukuran terlebih dahulu');
        } else {
            alert('Silakan pilih warna terlebih dahulu');
        }
        return;
    }

    renderBuyNowSheet();
    document.getElementById('buy-now-modal').classList.remove('hidden');
    document.getElementById('buy-now-modal').classList.add('flex');
    document.body.style.overflow = 'hidden';
    setTimeout(() => document.getElementById('buy-now-inner').classList.remove('translate-y-full', 'opacity-0'), 50);
}

function closeBuyNowSheet() {
    document.getElementById('buy-now-inner').classList.add('translate-y-full', 'opacity-0');
    setTimeout(() => {
        document.getElementById('buy-now-modal').classList.add('hidden');
        document.getElementById('buy-now-modal').classList.remove('flex');
        document.body.style.overflow = '';
    }, 300);
}

function renderBuyNowSheet() {
    let variant = null;
    if (isVariants && selectedVariantId) {
        variant = variantsRaw.find(v => v.id === selectedVariantId);
    }

    const price = variant ? (variant.price || productData.price) : productData.price;
    const stock = variant ? variant.stock : productData.stock;
    const imgSrc = selectedImage ? '/uploads/' + selectedImage : '';

    let colorsHtml = '';
    let sizesHtml = '';
    let bnSelectedColor = selectedColor || '';
    let bnSelectedSize = selectedSize || '';

    if (isVariants) {
        const colorGroups = {};
        variantsRaw.forEach(v => {
            if (!colorGroups[v.color]) colorGroups[v.color] = v;
        });
        colorsHtml = Object.entries(colorGroups).map(([color, v]) => `
            <button type="button" data-color="${color}"
                onclick="bnSelectColor('${color}', this)"
                class="bn-color w-8 h-8 rounded-full border-2 ${color === bnSelectedColor ? 'border-white ring-2 ring-white/40' : 'border-white/20 hover:border-white/60'} transition-all"
                style="background-color: ${v.color_hex || '#333'}"></button>
        `).join('');

        if (needsSize && bnSelectedColor) {
            const colorVariants = variantsRaw.filter(v => v.color === bnSelectedColor);
            const uniqueSizes = [...new Set(colorVariants.map(v => v.size).filter(Boolean))];
            sizesHtml = uniqueSizes.map(s => {
                const v = colorVariants.find(x => x.size === s);
                const disabled = !v || v.stock <= 0;
                return `<button type="button" data-size="${s}"
                    onclick="bnSelectSize('${s}', this)"
                    class="bn-size px-3 py-1.5 rounded-lg text-[10px] font-bold border transition-all ${s === bnSelectedSize ? 'bg-white text-black border-white' : disabled ? 'opacity-30 pointer-events-none border-red-500/30' : 'border-white/10 bg-white/5 text-gray-300 hover:border-white/30'}">${s}</button>`;
            }).join('');
        }
    }

    document.getElementById('bn-body').innerHTML = `
        <div class="flex gap-4 mb-6">
            <div class="w-20 h-20 rounded-xl overflow-hidden bg-[#1a1a1a] border border-white/10 flex-shrink-0">
                ${imgSrc ? '<img src="' + imgSrc + '" class="w-full h-full object-cover" id="bn-img">' : '<div class="w-full h-full flex items-center justify-center text-gray-700 text-xs">No img</div>'}
            </div>
            <div class="flex-1 min-w-0">
                <p class="text-sm font-bold text-white truncate">${productData.name}</p>
                <p class="text-lg font-black text-white" id="bn-price">Rp ${formatPrice(price)}</p>
                <p class="text-[10px] text-gray-500 mt-0.5" id="bn-stock">${stock > 0 ? 'Stok: ' + stock : 'Stok habis'}</p>
            </div>
        </div>
        ${isVariants ? `
        <div class="mb-4">
            <p class="text-[10px] font-bold text-gray-500 uppercase tracking-widest mb-2">Pilih Warna</p>
            <div class="flex flex-wrap gap-2" id="bn-colors">${colorsHtml}</div>
        </div>
        ${needsSize ? `
        <div class="mb-4">
            <p class="text-[10px] font-bold text-gray-500 uppercase tracking-widest mb-2">Pilih Ukuran</p>
            <div class="flex flex-wrap gap-2" id="bn-sizes">${sizesHtml}</div>
        </div>` : ''}
        ` : ''}
        <div class="mb-4">
            <p class="text-[10px] font-bold text-gray-500 uppercase tracking-widest mb-2">Jumlah</p>
            <div class="flex items-center gap-3">
                <button onclick="bnChangeQty(-1)" class="w-8 h-8 rounded-full bg-white/5 border border-white/10 text-white text-sm font-bold hover:bg-white/20">−</button>
                <span class="text-sm font-bold text-white w-6 text-center" id="bn-qty">1</span>
                <button onclick="bnChangeQty(1)" class="w-8 h-8 rounded-full bg-white/5 border border-white/10 text-white text-sm font-bold hover:bg-white/20">+</button>
            </div>
        </div>
        <div class="flex justify-between items-center mb-4 pt-2 border-t border-white/5">
            <span class="text-[10px] font-bold text-gray-500 uppercase tracking-widest">Subtotal</span>
            <span class="text-lg font-black text-white" id="bn-subtotal">Rp ${formatPrice(price)}</span>
        </div>
        <button onclick="bnProceedCheckout()" id="bn-checkout-btn" ${stock <= 0 ? 'disabled' : ''}
            class="w-full py-3.5 rounded-full bg-white text-black text-[10px] font-black uppercase tracking-widest hover:bg-gray-200 transition-all disabled:opacity-30 disabled:pointer-events-none">
            Lanjut Checkout
        </button>
    `;
}

let bnQty = 1;
function bnChangeQty(delta) {
    bnQty = Math.max(1, bnQty + delta);
    document.getElementById('bn-qty').textContent = bnQty;
    updateBnSubtotal();
}

function updateBnSubtotal() {
    let price = productData.price;
    if (isVariants && selectedVariantId) {
        const v = variantsRaw.find(x => x.id === selectedVariantId);
        if (v) price = v.price || productData.price;
    }
    document.getElementById('bn-subtotal').textContent = 'Rp ' + formatPrice(price * bnQty);
}

let bnSelectedColor = selectedColor || '';
let bnSelectedSize = selectedSize || '';

function bnSelectColor(color, btn) {
    document.querySelectorAll('.bn-color').forEach(el => {
        el.classList.remove('border-white', 'ring-2', 'ring-white/40');
        el.classList.add('border-white/20');
    });
    btn.classList.remove('border-white/20');
    btn.classList.add('border-white', 'ring-2', 'ring-white/40');

    bnSelectedColor = color;
    selectedColor = color;

    const colorVariants = variantsRaw.filter(v => v.color === color);
    const first = colorVariants[0];
    if (first) {
        selectedVariantId = first.id;
        if (!needsSize) {
            selectedPrice = first.price || productData.price;
            updateUI();
        }
        if (first.image) {
            const img = document.getElementById('bn-img');
            if (img) img.src = '/uploads/' + first.image;
        }
    }

    if (needsSize) {
        const sizesContainer = document.getElementById('bn-sizes');
        if (sizesContainer) {
            bnSelectedSize = '';
            selectedSize = null;
            const uniqueSizes = [...new Set(colorVariants.map(v => v.size).filter(Boolean))];
            sizesContainer.innerHTML = uniqueSizes.map(s => {
                const v = colorVariants.find(x => x.size === s);
                const disabled = !v || v.stock <= 0;
                return `<button type="button" data-size="${s}"
                    onclick="bnSelectSize('${s}', this)"
                    class="bn-size px-3 py-1.5 rounded-lg text-[10px] font-bold border transition-all ${disabled ? 'opacity-30 pointer-events-none border-red-500/30' : 'border-white/10 bg-white/5 text-gray-300 hover:border-white/30'}">${s}</button>`;
            }).join('');
        }
    }

    updateBnVariant();
}

function bnSelectSize(size, btn) {
    if (btn.classList.contains('opacity-30')) return;
    document.querySelectorAll('.bn-size').forEach(el => {
        el.classList.remove('bg-white', 'text-black', 'border-white');
        el.classList.add('bg-white/5', 'text-gray-300', 'border-white/10');
    });
    btn.classList.remove('bg-white/5', 'text-gray-300', 'border-white/10');
    btn.classList.add('bg-white', 'text-black', 'border-white');

    bnSelectedSize = size;
    selectedSize = size;

    const v = variantsRaw.find(x => x.color === bnSelectedColor && x.size === size);
    if (v) {
        selectedVariantId = v.id;
        selectedPrice = v.price || productData.price;
        if (v.image) {
            const img = document.getElementById('bn-img');
            if (img) img.src = '/uploads/' + v.image;
        }
    }
    updateBnVariant();
}

function updateBnVariant() {
    let price = productData.price;
    let stock = productData.stock;
    if (selectedVariantId) {
        const v = variantsRaw.find(x => x.id === selectedVariantId);
        if (v) {
            price = v.price || productData.price;
            stock = v.stock;
        }
    }
    document.getElementById('bn-price').textContent = 'Rp ' + formatPrice(price);
    document.getElementById('bn-stock').textContent = stock > 0 ? 'Stok: ' + stock : 'Stok habis';
    updateBnSubtotal();

    const btn = document.getElementById('bn-checkout-btn');
    if (stock <= 0) {
        btn.disabled = true;
        btn.classList.add('opacity-30', 'pointer-events-none');
    } else {
        btn.disabled = false;
        btn.classList.remove('opacity-30', 'pointer-events-none');
    }
}

function bnProceedCheckout() {
    // Save this item as cart and redirect to checkout
    let cart = getCart();
    let key = selectedVariantId ? 'p' + productData.id + '_v' + selectedVariantId : 'p' + productData.id;
    let variantLabel = '';
    if (isVariants && selectedVariantId) {
        const v = variantsRaw.find(x => x.id === selectedVariantId);
        if (v) variantLabel = [v.color, v.size].filter(Boolean).join(' / ');
    }
    let price = selectedVariantId ? (variantsRaw.find(v => v.id === selectedVariantId)?.price || productData.price) : productData.price;

    let existing = cart.find(item => item.key === key);
    if (existing) {
        existing.qty += bnQty;
    } else {
        cart.push({
            key: key,
            id: productData.id,
            variant_id: selectedVariantId,
            name: productData.name,
            variant_label: variantLabel,
            price: price,
            image: selectedImage,
            qty: bnQty
        });
    }
    saveCart(cart);
    updateCartBadge();
    closeBuyNowSheet();
    window.location.href = '{{ route("checkout") }}';
}

// ── Review Filters ──
function filterReviews(rating) {
    document.querySelectorAll('.review-filter-btn').forEach(el => {
        if (parseInt(el.dataset.rating) === rating) {
            el.classList.remove('bg-white/5', 'text-gray-400', 'border-white/10');
            el.classList.add('bg-white', 'text-black');
        } else {
            el.classList.remove('bg-white', 'text-black');
            el.classList.add('bg-white/5', 'text-gray-400', 'border-white/10');
        }
    });
    document.querySelectorAll('.review-card').forEach(el => {
        el.style.display = (rating === 0 || parseInt(el.dataset.rating) === rating) ? '' : 'none';
    });
}

// ── Review Lightbox ──
function openLightbox(src) {
    document.getElementById('lightbox-img').src = src;
    document.getElementById('lightbox-modal').classList.remove('hidden');
    document.getElementById('lightbox-modal').classList.add('flex');
    document.body.style.overflow = 'hidden';
}

function closeLightbox() {
    document.getElementById('lightbox-modal').classList.add('hidden');
    document.getElementById('lightbox-modal').classList.remove('flex');
    document.body.style.overflow = '';
}

// ── Wishlist ──
function toggleWishlist(productId) {
    @auth
    fetch('{{ route('wishlist.toggle') }}', {
        method: 'POST',
        headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': '{{ csrf_token() }}' },
        body: JSON.stringify({ product_id: productId })
    })
    .then(r => r.json())
    .then(data => {
        const icon = document.getElementById('wishlist-icon-' + productId);
        const btn = document.getElementById('wishlist-btn-' + productId);
        if (data.status === 'added') {
            icon.classList.add('text-red-500', 'fill-red-500');
            icon.setAttribute('fill', 'currentColor');
        } else {
            icon.classList.remove('text-red-500', 'fill-red-500');
            icon.setAttribute('fill', 'none');
        }
    });
    @else
    window.location.href = '{{ route('login') }}';
    @endauth
}

document.addEventListener('DOMContentLoaded', function() {
    updateCartBadge();
});
</script>
@endsection
