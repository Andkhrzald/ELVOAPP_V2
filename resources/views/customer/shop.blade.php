@extends('layouts.customer')

@section('content')
{{-- Header --}}
<section class="pt-24 pb-6 px-8 text-center" data-aos="fade-down">
    <p class="text-[10px] font-black uppercase tracking-[0.5em] text-gray-500 mb-3">Official Collection</p>
    <h2 class="text-5xl md:text-7xl font-black italic uppercase tracking-tighter text-white">
        Our <span class="text-transparent" style="-webkit-text-stroke: 1px white;">Collection.</span>
    </h2>
</section>

{{-- Filter & Sort Bar --}}
<section class="px-8 pb-8">
    <div class="container mx-auto">
        <div class="bg-white/[0.03] border border-white/5 rounded-2xl p-4 flex flex-col lg:flex-row gap-4 items-start lg:items-center justify-between" data-aos="fade-up">

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
        <div class="grid grid-cols-2 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-x-5 gap-y-8">
            @foreach($products as $product)
            @php
            $pHasVariants = $product->hasVariants();
            $pVariantType = $product->getVariantType();
            $pAvgRating = $product->reviews->avg('rating') ?? 0;
            $pReviewCount = $product->reviews->count();
            @endphp
            <div class="group" data-aos="fade-up" data-aos-delay="{{ ($loop->iteration % 4) * 80 }}">
                <div class="relative aspect-[3/4] bg-[#0f0f0f] rounded-2xl overflow-hidden mb-2 border border-white/5 group-hover:border-white/20 transition-all duration-500">
                    <a href="{{ route('product.detail', $product->slug) }}" class="block w-full h-full">
                        @if($product->image)
                        <img src="{{ asset('uploads/' . $product->image) }}" alt="{{ $product->name }}" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-700">
                        @else
                        <div class="w-full h-full flex items-center justify-center bg-gradient-to-br from-[#111] to-[#0a0a0a]">
                            <span class="text-white/[0.03] text-8xl font-black italic uppercase">ELVO</span>
                        </div>
                        @endif
                    </a>

                    {{-- Wishlist Heart --}}
                    <button onclick="toggleWishlist({{ $product->id }})"
                        class="absolute top-2 right-2 w-7 h-7 rounded-full bg-black/60 backdrop-blur-md border border-white/10 flex items-center justify-center transition-all duration-300 hover:bg-white hover:scale-110 z-10"
                        id="wishlist-btn-{{ $product->id }}">
                        <svg class="w-3 h-3 {{ in_array($product->id, $wishlistIds) ? 'text-red-500 fill-red-500' : 'text-white' }} transition-colors" id="wishlist-icon-{{ $product->id }}" fill="{{ in_array($product->id, $wishlistIds) ? 'currentColor' : 'none' }}" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"/>
                        </svg>
                    </button>

                    {{-- Category Badge --}}
                    @if($product->category?->parent)
                    <span class="absolute top-2 left-2 px-2 py-0.5 bg-black/60 backdrop-blur-md text-white text-[7px] font-black uppercase tracking-widest rounded-full border border-white/10">
                        {{ $product->category->parent->name }}
                    </span>
                    @endif

                    {{-- Stock Badge --}}
                    @if($product->stock <= 5 && $product->stock > 0)
                    <div class="absolute top-2 right-10 px-2 py-0.5 bg-red-500/80 text-white text-[7px] font-black uppercase tracking-widest rounded-full">
                        Sisa {{ $product->stock }}
                    </div>
                    @elseif($product->stock <= 0)
                    <div class="absolute top-2 right-10 px-2 py-0.5 bg-gray-800/80 text-gray-400 text-[7px] font-black uppercase tracking-widest rounded-full">
                        Habis
                    </div>
                    @endif

                    {{-- Hover Overlay --}}
                    <div class="absolute inset-0 bg-gradient-to-t from-black/90 via-black/40 to-transparent opacity-0 group-hover:opacity-100 transition-opacity duration-500 flex flex-col items-center justify-end p-4 pointer-events-none">
                        <button onclick="handleAddToCart({{ $product->id }}, '{{ addslashes($product->name) }}', {{ $product->price }}, '{{ $product->image }}', {{ $pHasVariants ? 'true' : 'false' }}, '{{ $pVariantType }}', {{ $product->stock }})"
                            class="w-full bg-white text-black py-2.5 rounded-full text-[9px] font-black uppercase tracking-widest transform translate-y-4 group-hover:translate-y-0 transition-all duration-500 shadow-2xl hover:bg-gray-200 pointer-events-auto">
                            Add to Cart
                        </button>
                    </div>
                </div>

                {{-- Product Info --}}
                <div class="space-y-0.5 px-0.5">
                    <h3 class="font-bold text-sm text-white leading-tight">
                        <a href="{{ route('product.detail', $product->slug) }}" class="hover:text-gray-300 transition">
                            {{ $product->name }}
                        </a>
                    </h3>
                    <span class="font-black text-sm text-white">Rp {{ number_format($product->price, 0, ',', '.') }}</span>
                    <div class="flex items-center gap-1.5 text-[9px]">
                        @if($pReviewCount > 0)
                        <div class="flex items-center gap-0.5">
                            @for($i = 1; $i <= 5; $i++)
                            <svg class="w-2.5 h-2.5 {{ $i <= round($pAvgRating) ? 'text-yellow-500' : 'text-gray-700' }}" fill="currentColor" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/></svg>
                            @endfor
                            <span class="text-gray-500">({{ $pReviewCount }})</span>
                        </div>
                        @else
                        <span class="text-gray-600">Baru</span>
                        @endif
                        <span class="text-gray-700">|</span>
                        <span class="text-gray-500">Stok: {{ $product->stock }}</span>
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
const variantModalData = {};

function getCart() { return JSON.parse(localStorage.getItem('elvo_cart') || '[]'); }
function saveCart(cart) { localStorage.setItem('elvo_cart', JSON.stringify(cart)); }

function formatPrice(num) {
    return Math.round(num).toString().replace(/\B(?=(\d{3})+(?!\d))/g, '.');
}

function handleAddToCart(id, name, price, image, hasVariants, variantType, stock) {
    if (hasVariants) {
        fetch('/api/products/' + id + '/variants')
            .then(r => r.json())
            .then(data => {
                openVariantModal(id, name, price, image, data, variantType);
            })
            .catch(() => {
                // fallback: direct add
                directAddToCart(id, name, price, image, null, '');
            });
    } else {
        directAddToCart(id, name, price, image, null, '');
    }
}

function directAddToCart(id, name, price, image, variantId, variantLabel) {
    let cart = getCart();
    let key = variantId ? 'p' + id + '_v' + variantId : 'p' + id;
    let existing = cart.find(item => item.key === key);
    if (existing) {
        existing.qty += 1;
    } else {
        cart.push({
            key: key,
            id: id,
            variant_id: variantId,
            name: name,
            variant_label: variantLabel,
            price: price,
            image: image,
            qty: 1
        });
    }
    saveCart(cart);
    updateCartBadge();
    showToast(name + ' ditambahkan!');
}

function showToast(msg) {
    const toast = document.getElementById('cart-toast');
    document.getElementById('cart-toast-text').textContent = msg;
    toast.classList.remove('translate-y-20', 'opacity-0');
    setTimeout(() => toast.classList.add('translate-y-20', 'opacity-0'), 2000);
}

function updateCartBadge() {
    const cart = getCart();
    const badge = document.getElementById('cart-count');
    if (badge) {
        const total = cart.reduce((sum, item) => sum + item.qty, 0);
        badge.textContent = total;
        badge.classList.toggle('hidden', total === 0);
    }
}

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
            btn.classList.add('wishlist-active');
        } else {
            icon.classList.remove('text-red-500', 'fill-red-500');
            icon.setAttribute('fill', 'none');
            btn.classList.remove('wishlist-active');
        }
    })
    .catch(err => {
        if (err.status === 422 || err.status === 401) alert('Wishlist penuh (max 20)!');
    });
    @else
    window.location.href = '{{ route('login') }}';
    @endauth
}

// ── Variant Selection Modal ──
let modalVariantId = null;

function openVariantModal(id, name, price, image, data, variantType) {
    modalVariantData.id = id;
    modalVariantData.name = name;
    modalVariantData.basePrice = price;
    modalVariantData.image = image;
    modalVariantData.variants = data.variants;
    modalVariantData.colors = data.colors;
    modalVariantData.sizes = data.sizes;
    modalVariantData.variantType = variantType;
    modalVariantData.selectedColor = null;
    modalVariantData.selectedSize = null;
    modalVariantData.selectedVariantId = null;
    modalVariantData.qty = 1;

    renderVariantModal();
    document.getElementById('variant-modal').classList.remove('hidden');
    document.getElementById('variant-modal').classList.add('flex');
    document.body.style.overflow = 'hidden';
    setTimeout(() => document.getElementById('variant-modal-inner').classList.remove('translate-y-full', 'opacity-0'), 50);
}

function closeVariantModal() {
    document.getElementById('variant-modal-inner').classList.add('translate-y-full', 'opacity-0');
    setTimeout(() => {
        document.getElementById('variant-modal').classList.add('hidden');
        document.getElementById('variant-modal').classList.remove('flex');
        document.body.style.overflow = '';
    }, 300);
}

function renderVariantModal() {
    const d = modalVariantData;
    const imgSrc = d.image ? '/uploads/' + d.image : '';

    let colorsHtml = d.colors.map(c => `
        <button onclick="vmSelectColor('${c.color}', this)" data-color="${c.color}"
            class="vm-color w-9 h-9 rounded-full border-2 border-white/20 hover:border-white/60 transition-all"
            style="background-color: ${c.color_hex || '#333'}"></button>
    `).join('');

    let sizesHtml = '';
    if (d.variantType === 'color_size') {
        const uniqueSizes = [...new Set(d.variants.map(v => v.size).filter(Boolean))];
        sizesHtml = uniqueSizes.map(s => `
            <button onclick="vmSelectSize('${s}', this)" data-size="${s}"
                class="vm-size px-4 py-2 rounded-xl border border-white/10 bg-white/5 text-sm font-bold text-gray-300 opacity-30 pointer-events-none">${s}</button>
        `).join('');
    }

    document.getElementById('vm-body').innerHTML = `
        <div class="flex gap-4 mb-6">
            <div class="w-20 h-20 rounded-xl overflow-hidden bg-[#1a1a1a] border border-white/10 flex-shrink-0">
                ${imgSrc ? '<img src="' + imgSrc + '" class="w-full h-full object-cover" id="vm-img">' : '<div class="w-full h-full flex items-center justify-center text-gray-700 text-xs">No img</div>'}
            </div>
            <div>
                <p class="text-sm font-bold text-white">${d.name}</p>
                <p class="text-lg font-black text-white" id="vm-price">Rp ${formatPrice(d.basePrice)}</p>
                <p class="text-[10px] text-gray-500 mt-0.5" id="vm-stock">Pilih varian</p>
            </div>
        </div>
        <div class="mb-4">
            <p class="text-[10px] font-bold text-gray-500 uppercase tracking-widest mb-2">Pilih Warna <span id="vm-color-label" class="text-white"></span></p>
            <div class="flex flex-wrap gap-2" id="vm-colors">${colorsHtml}</div>
        </div>
        ${d.variantType === 'color_size' ? `
        <div class="mb-4">
            <p class="text-[10px] font-bold text-gray-500 uppercase tracking-widest mb-2">Pilih Ukuran <span id="vm-size-label" class="text-white"></span></p>
            <div class="flex flex-wrap gap-2" id="vm-sizes">${sizesHtml}</div>
        </div>` : ''}
        <div class="mb-4">
            <p class="text-[10px] font-bold text-gray-500 uppercase tracking-widest mb-2">Jumlah</p>
            <div class="flex items-center gap-3">
                <button onclick="vmChangeQty(-1)" class="w-8 h-8 rounded-full bg-white/5 border border-white/10 text-white text-sm font-bold hover:bg-white/20">−</button>
                <span class="text-sm font-bold text-white w-6 text-center" id="vm-qty">1</span>
                <button onclick="vmChangeQty(1)" class="w-8 h-8 rounded-full bg-white/5 border border-white/10 text-white text-sm font-bold hover:bg-white/20">+</button>
            </div>
        </div>
        <button onclick="vmConfirmAdd()" id="vm-confirm-btn"
            class="w-full py-3.5 rounded-full bg-white text-black text-[10px] font-black uppercase tracking-widest hover:bg-gray-200 transition-all disabled:opacity-30 disabled:pointer-events-none" disabled>
            + Tambah ke Keranjang
        </button>
    `;
}

function vmSelectColor(color, btn) {
    document.querySelectorAll('.vm-color').forEach(el => {
        el.classList.remove('border-white', 'ring-2', 'ring-white/40');
        el.classList.add('border-white/20');
    });
    btn.classList.remove('border-white/20');
    btn.classList.add('border-white', 'ring-2', 'ring-white/40');

    modalVariantData.selectedColor = color;
    document.getElementById('vm-color-label').textContent = color;

    const d = modalVariantData;
    const colorVariants = d.variants.filter(v => v.color === color);

    // Update image
    const colorVariantWithImage = colorVariants.find(v => v.image);
    if (colorVariantWithImage) {
        document.getElementById('vm-img').src = '/uploads/' + colorVariantWithImage.image;
        modalVariantData.image = colorVariantWithImage.image;
    }

    if (d.variantType === 'color_size') {
        document.querySelectorAll('.vm-size').forEach(el => {
            const s = el.dataset.size;
            const variant = colorVariants.find(v => v.size === s);
            if (variant && variant.stock > 0) {
                el.classList.remove('opacity-30', 'pointer-events-none');
                el.classList.add('border-white/10', 'hover:border-white/30');
            } else {
                el.classList.add('opacity-30', 'pointer-events-none');
                el.classList.remove('border-white/10', 'hover:border-white/30');
            }
        });
        const firstAvail = document.querySelector('.vm-size:not(.opacity-30)');
        if (firstAvail) vmSelectSize(firstAvail.dataset.size, firstAvail);
    } else {
        // color only - find the variant
        const variant = colorVariants[0];
        if (variant) {
            modalVariantData.selectedVariantId = variant.id;
            modalVariantData.selectedSize = null;
            const price = variant.price || d.basePrice;
            document.getElementById('vm-price').textContent = 'Rp ' + formatPrice(price);
            document.getElementById('vm-stock').textContent = 'Stok: ' + variant.stock;
            if (variant.image) {
                document.getElementById('vm-img').src = '/uploads/' + variant.image;
                modalVariantData.image = variant.image;
            }
            document.getElementById('vm-confirm-btn').disabled = false;
        }
    }
}

function vmSelectSize(size, btn) {
    if (btn.classList.contains('opacity-30')) return;
    document.querySelectorAll('.vm-size').forEach(el => {
        el.classList.remove('bg-white', 'text-black', 'border-white');
        el.classList.add('bg-white/5', 'text-gray-300', 'border-white/10');
    });
    btn.classList.remove('bg-white/5', 'text-gray-300', 'border-white/10');
    btn.classList.add('bg-white', 'text-black', 'border-white');

    modalVariantData.selectedSize = size;
    document.getElementById('vm-size-label').textContent = size;

    const d = modalVariantData;
    const variant = d.variants.find(v => v.color === d.selectedColor && v.size === size);
    if (variant) {
        modalVariantData.selectedVariantId = variant.id;
        const price = variant.price || d.basePrice;
        document.getElementById('vm-price').textContent = 'Rp ' + formatPrice(price);
        document.getElementById('vm-stock').textContent = 'Stok: ' + variant.stock;

        if (variant.image) {
            document.getElementById('vm-img').src = '/uploads/' + variant.image;
            modalVariantData.image = variant.image;
        }
        document.getElementById('vm-confirm-btn').disabled = false;
    }
}

function vmChangeQty(delta) {
    const d = modalVariantData;
    d.qty = Math.max(1, d.qty + delta);
    document.getElementById('vm-qty').textContent = d.qty;
}

function vmConfirmAdd() {
    const d = modalVariantData;
    if (!d.selectedVariantId && d.variantType === 'color_size') return;

    let variantLabel = '';
    let variantId = null;
    let price = d.basePrice;

    if (d.selectedVariantId) {
        const variant = d.variants.find(v => v.id === d.selectedVariantId);
        if (variant) {
            variantId = variant.id;
            variantLabel = [variant.color, variant.size].filter(Boolean).join(' / ');
            price = variant.price || d.basePrice;
        }
    }

    let cart = getCart();
    let key = variantId ? 'p' + d.id + '_v' + variantId : 'p' + d.id;
    let existing = cart.find(item => item.key === key);
    if (existing) {
        existing.qty += d.qty;
    } else {
        cart.push({
            key: key,
            id: d.id,
            variant_id: variantId,
            name: d.name,
            variant_label: variantLabel,
            price: price,
            image: d.image,
            qty: d.qty
        });
    }
    saveCart(cart);
    updateCartBadge();
    closeVariantModal();
    showToast(d.name + ' ditambahkan!');
}

document.addEventListener('DOMContentLoaded', updateCartBadge);
</script>

{{-- Variant Selection Modal --}}
<div id="variant-modal" class="fixed inset-0 z-[150] hidden items-end justify-center bg-black/80 backdrop-blur-md">
    <div id="variant-modal-inner" class="relative w-full max-w-lg bg-elvo-surface rounded-t-[2rem] shadow-2xl border border-white/10 translate-y-full opacity-0 transition-all duration-300 max-h-[85vh] overflow-y-auto" style="margin-top: auto;">
        <div class="sticky top-0 bg-elvo-surface z-10 flex items-center justify-between p-6 pb-2 border-b border-white/5">
            <h3 class="text-sm font-black text-white uppercase tracking-widest">Pilih Varian</h3>
            <button onclick="closeVariantModal()" class="w-8 h-8 bg-white/5 text-gray-400 hover:text-white rounded-full flex items-center justify-center">&times;</button>
        </div>
        <div class="p-6" id="vm-body">
            {{-- populated by JS --}}
        </div>
    </div>
</div>
@endsection