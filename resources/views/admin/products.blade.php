@extends('layouts.app')

@section('content')
<div class="space-y-6">
    {{-- Header Section --}}
    <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
        <div>
            <h1 class="text-3xl font-black animate-fade-up animate-fade-up text-white tracking-tight">Katalog Produk</h1>
            <p class="text-sm text-gray-500 mt-1">Kelola inventaris, harga, dan visibilitas produk Anda.</p>
        </div>
        <div class="flex items-center gap-3">
            <a href="{{ route('admin.products.export') }}"
               class="px-4 py-2.5 text-sm font-bold bg-emerald-500/10 text-emerald-400 border border-emerald-500/20 rounded-xl hover:bg-emerald-500/20 transition-all flex items-center gap-2">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                Export Excel
            </a>
            <button onclick="toggleModal('modal-tambah')" class="btn-primary px-5 py-2.5 text-sm flex items-center gap-2">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 4v16m8-8H4"/>
                </svg>
                Tambah Produk
            </button>
        </div>
    </div>

    {{-- Stats Grid --}}
    <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
        <div class="bg-elvo-surface p-5 rounded-2xl border border-white/[0.06] shadow-[0_0_25px_rgba(0,0,0,0.2)] card-hover">
            <p class="text-[10px] font-bold text-gray-500 uppercase tracking-widest mb-1">Total Produk</p>
            <div class="flex items-end justify-between">
                <h3 class="text-2xl font-black text-white">{{ $totalProducts }}</h3>
                <span class="text-[10px] bg-elvo-primary/10 text-elvo-primary px-2 py-1 rounded-md font-bold">Items</span>
            </div>
        </div>
        <div class="bg-elvo-surface p-5 rounded-2xl border border-white/[0.06] shadow-[0_0_25px_rgba(0,0,0,0.2)] card-hover">
            <p class="text-[10px] font-bold text-gray-500 uppercase tracking-widest mb-1">Produk Aktif</p>
            <div class="flex items-end justify-between">
                <h3 class="text-2xl font-black text-green-500">{{ $activeProducts }}</h3>
                <span class="text-[10px] bg-green-500/10 text-green-500 px-2 py-1 rounded-md font-bold">Live</span>
            </div>
        </div>
        <div class="bg-elvo-surface p-5 rounded-2xl border border-white/[0.06] shadow-[0_0_25px_rgba(0,0,0,0.2)] card-hover">
            <p class="text-[10px] font-bold text-gray-500 uppercase tracking-widest mb-1">Disembunyikan</p>
            <div class="flex items-end justify-between">
                <h3 class="text-2xl font-black text-yellow-500">{{ $hiddenProducts }}</h3>
                <span class="text-[10px] bg-yellow-500/10 text-yellow-500 px-2 py-1 rounded-md font-bold">Hidden</span>
            </div>
        </div>
        <div class="bg-elvo-surface p-5 rounded-2xl border border-white/[0.06] shadow-[0_0_25px_rgba(0,0,0,0.2)] card-hover">
            <p class="text-[10px] font-bold text-gray-500 uppercase tracking-widest mb-1">Stok Menipis</p>
            <div class="flex items-end justify-between">
                <h3 class="text-2xl font-black text-red-500">{{ $lowStockProducts }}</h3>
                <span class="text-[10px] bg-red-500/10 text-red-500 px-2 py-1 rounded-md font-bold">Alert</span>
            </div>
        </div>
    </div>

    <div class="grid grid-cols-12 gap-6">
        {{-- Sidebar Section --}}
        <div class="col-span-12 lg:col-span-3 space-y-6">
            <form action="{{ route('admin.products') }}" method="GET" class="bg-elvo-surface p-6 rounded-2xl border border-white/[0.06] sticky top-24">
                <h4 class="text-xs font-black text-white uppercase tracking-widest mb-6 pb-2 border-b border-white/[0.06]">Filter & Pencarian</h4>
                
                <div class="space-y-5">
                    <div>
                        <label class="block text-[10px] font-bold text-gray-500 uppercase tracking-widest mb-2">Cari Nama</label>
                        <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari..." class="w-full bg-elvo-bg border border-white/[0.06] rounded-xl px-4 py-2.5 text-sm text-white focus:border-elvo-primary outline-none transition-all">
                    </div>

                    <div>
                        <label class="block text-[10px] font-bold text-gray-500 uppercase tracking-widest mb-2">Kategori</label>
                        <select name="category" class="w-full bg-elvo-bg border border-white/[0.06] rounded-xl px-4 py-2.5 text-sm text-white focus:border-elvo-primary outline-none transition-all appearance-none">
                            <option value="">Semua Kategori</option>
                            @foreach(\App\Models\Category::all() as $cat)
                                <option value="{{ $cat->id }}" {{ request('category') == $cat->id ? 'selected' : '' }}>{{ $cat->name }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div>
                        <label class="block text-[10px] font-bold text-gray-500 uppercase tracking-widest mb-2">Status</label>
                        <div class="space-y-2">
                            <label class="flex items-center gap-3 cursor-pointer group">
                                <input type="radio" name="status" value="" {{ request('status') == '' ? 'checked' : '' }} class="hidden peer">
                                <span class="w-4 h-4 rounded-full border-2 border-white/10 peer-checked:border-elvo-primary peer-checked:bg-elvo-primary transition-all"></span>
                                <span class="text-sm text-gray-400 group-hover:text-white transition-colors">Semua</span>
                            </label>
                            <label class="flex items-center gap-3 cursor-pointer group">
                                <input type="radio" name="status" value="active" {{ request('status') == 'active' ? 'checked' : '' }} class="hidden peer">
                                <span class="w-4 h-4 rounded-full border-2 border-white/10 peer-checked:border-green-500 peer-checked:bg-green-500 transition-all"></span>
                                <span class="text-sm text-gray-400 group-hover:text-white transition-colors">Aktif (Live)</span>
                            </label>
                            <label class="flex items-center gap-3 cursor-pointer group">
                                <input type="radio" name="status" value="hidden" {{ request('status') == 'hidden' ? 'checked' : '' }} class="hidden peer">
                                <span class="w-4 h-4 rounded-full border-2 border-white/10 peer-checked:border-yellow-500 peer-checked:bg-yellow-500 transition-all"></span>
                                <span class="text-sm text-gray-400 group-hover:text-white transition-colors">Disembunyikan</span>
                            </label>
                        </div>
                    </div>

                    <div class="grid grid-cols-2 gap-3">
                        <a href="{{ route('admin.products') }}" class="py-3 btn-ghost text-[10px] font-black uppercase tracking-widest text-center text-white">
                            Reset
                        </a>
                        <button type="submit" class="py-3 btn-primary text-[10px] font-black uppercase tracking-widest">
                            Filter
                        </button>
                    </div>
                </div>
            </form>
        </div>

        {{-- Table Section --}}
        <div class="col-span-12 lg:col-span-9">
            <div class="bg-elvo-surface rounded-2xl border border-white/[0.06] overflow-hidden shadow-2xl">
                <div class="overflow-x-auto">
                    <table class="w-full text-left">
                        <thead>
                            <tr class="bg-white/[0.02] border-b border-white/[0.06]">
                                <th class="px-6 py-4 text-[10px] font-black text-gray-500 uppercase tracking-widest">Produk</th>
                                <th class="px-6 py-4 text-[10px] font-black text-gray-500 uppercase tracking-widest text-center">Status</th>
                                <th class="px-6 py-4 text-[10px] font-black text-gray-500 uppercase tracking-widest">Kategori</th>
                                <th class="px-6 py-4 text-[10px] font-black text-gray-500 uppercase tracking-widest">Stok</th>
                                <th class="px-6 py-4 text-[10px] font-black text-gray-500 uppercase tracking-widest">Harga</th>
                                <th class="px-6 py-4 text-[10px] font-black text-gray-500 uppercase tracking-widest text-right">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-white/5">
                            @forelse($products as $p)
                            <tr class="hover:bg-white/[0.01] transition-colors group">
                                <td class="px-6 py-4">
                                    <div class="flex items-center gap-4">
                                        <div class="w-12 h-12 rounded-xl bg-elvo-bg border border-white/[0.06] overflow-hidden flex-shrink-0">
                                            @if($p->image)
                                                <img src="{{ asset('uploads/' . $p->image) }}" class="w-full h-full object-cover">
                                            @else
                                                <div class="w-full h-full flex items-center justify-center text-gray-600">
                                                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" stroke-width="2"/></svg>
                                                </div>
                                            @endif
                                        </div>
                                        <div>
                                            <div class="text-sm font-bold text-white">{{ $p->name }}</div>
                                            <div class="text-[10px] text-gray-500 font-medium">SKU: #ELV-{{ str_pad($p->id, 4, '0', STR_PAD_LEFT) }}</div>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-6 py-4 text-center">
                                    <button onclick="toggleStatus({{ $p->id }}, this)" 
                                        class="relative inline-flex items-center h-6 rounded-full w-11 transition-colors focus:outline-none {{ $p->is_active ? 'bg-elvo-primary' : 'bg-gray-700' }}">
                                        <span class="sr-only">Toggle Status</span>
                                        <span class="inline-block w-4 h-4 transform bg-white rounded-full transition-transform {{ $p->is_active ? 'translate-x-6' : 'translate-x-1' }}"></span>
                                    </button>
                                    <div class="mt-1 text-[9px] font-bold uppercase tracking-tighter {{ $p->is_active ? 'text-elvo-primary' : 'text-gray-500' }}">
                                        {{ $p->is_active ? 'Visible' : 'Hidden' }}
                                    </div>
                                </td>
                                <td class="px-6 py-4">
                                    <span class="px-2 py-1 bg-white/5 text-gray-400 border border-white/[0.06] rounded-md text-[10px] font-bold uppercase">
                                        {{ $p->category->name ?? 'None' }}
                                    </span>
                                </td>
                                <td class="px-6 py-4">
                                    <div class="flex items-center gap-2">
                                        <div class="w-1.5 h-1.5 rounded-full {{ $p->stock > 10 ? 'bg-green-500' : ($p->stock > 0 ? 'bg-yellow-500' : 'bg-red-500') }}"></div>
                                        <span class="text-sm font-bold {{ $p->stock > 0 ? 'text-white' : 'text-red-500' }}">{{ $p->stock }}</span>
                                    </div>
                                </td>
                                <td class="px-6 py-4 text-sm font-black text-white">
                                    <span class="text-[10px] text-gray-500 font-normal mr-1">Rp</span>{{ number_format($p->price, 0, ',', '.') }}
                                </td>
                                <td class="px-6 py-4">
                                    <div class="flex justify-end items-center gap-2">
                                        <a href="{{ route('admin.products.edit', $p->id) }}" class="p-2 btn-ghost px-2 py-2 rounded-lg hover:text-white hover:border-elvo-primary">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/></svg>
                                        </a>
                                        <form action="{{ route('admin.products.destroy', $p->id) }}" method="POST" onsubmit="return confirm('Hapus produk ini?')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="p-2 bg-white/5 text-gray-400 hover:text-white hover:bg-elvo-rose rounded-lg transition-all">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/></svg>
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="6" class="px-6 py-20 text-center">
                                    <div class="flex flex-col items-center">
                                        <div class="w-16 h-16 bg-white/5 rounded-full flex items-center justify-center mb-4">
                                            <svg class="w-8 h-8 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/></svg>
                                        </div>
                                        <p class="text-gray-500 font-medium italic">Tidak ada produk yang ditemukan.</p>
                                    </div>
                                </td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                @if($products->hasPages())
                <div class="p-6 border-t border-white/[0.06]">
                    {{ $products->withQueryString()->links() }}
                </div>
                @endif
            </div>
        </div>
    </div>
</div>

{{-- MODAL TAMBAH PRODUK --}}
<div id="modal-tambah" class="fixed inset-0 z-[100] hidden items-center justify-center bg-black/80 backdrop-blur-md">
    <div class="relative w-full max-w-5xl max-h-[90vh] overflow-y-auto bg-elvo-surface rounded-[2rem] shadow-2xl border border-white/10 animate-modal-in">
        <form action="{{ route('admin.products.store') }}" method="POST" enctype="multipart/form-data">
            @csrf
            <div class="flex items-center justify-between p-5 border-b border-white/[0.06] sticky top-0 bg-elvo-surface z-10 rounded-t-[2rem]">
                <div>
                    <h3 class="text-lg font-black text-white">Tambah Produk Baru</h3>
                    <p class="text-[9px] text-gray-500 mt-0.5 uppercase tracking-widest">Inventory System / Add Product</p>
                </div>
                <button type="button" onclick="toggleModal('modal-tambah')" class="w-8 h-8 bg-white/5 text-gray-400 hover:text-white rounded-full flex items-center justify-center transition-all text-lg">&times;</button>
            </div>

            <div class="p-5 grid grid-cols-12 gap-5">
                {{-- LEFT: Form Fields (7/12) --}}
                <div class="col-span-12 lg:col-span-7 space-y-5">
                    {{-- Nama + Slug --}}
                    <div class="bg-elvo-surface p-5 rounded-2xl border border-white/[0.06] shadow-lg">
                        <div class="space-y-4">
                            <div>
                                <label class="block text-[9px] font-black text-gray-500 uppercase tracking-[0.15em] mb-1.5">Nama Produk</label>
                                <input type="text" name="name" id="productName" onkeyup="generateSlug(this.value)"
                                    class="w-full bg-elvo-bg border border-white/[0.06] rounded-xl px-4 h-11 text-sm font-bold text-white focus:border-elvo-primary outline-none transition-all" placeholder="Premium Elvo Hoodie Black Edition" required>
                            </div>
                            <div class="flex items-center gap-2 bg-[#0a0a0a] border border-white/[0.06] rounded-xl px-4 h-10 text-[9px] text-gray-500 font-mono">
                                <span class="font-black text-gray-600 uppercase tracking-widest">Slug:</span>
                                <span>elvo.com/shop/</span>
                                <input type="text" name="slug" id="productSlug" class="bg-transparent border-none p-0 focus:ring-0 text-[10px] font-black text-elvo-primary w-full" readonly>
                            </div>

                            {{-- Kategori + Jenis + Material --}}
                            <div class="grid grid-cols-3 gap-4">
                                <div>
                                    <label class="block text-[9px] font-black text-gray-500 uppercase tracking-[0.15em] mb-1.5">Kategori Utama</label>
                                    <select id="main-category" onchange="updateSubCategories()" class="w-full bg-elvo-bg border border-white/[0.06] rounded-xl px-4 h-11 text-sm font-bold text-white focus:border-elvo-primary outline-none appearance-none transition-all" required>
                                        <option value="">Pilih</option>
                                        @foreach($categories->whereNull('parent_id') as $parent)
                                            <option value="{{ $parent->id }}">{{ $parent->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div>
                                    <label class="block text-[9px] font-black text-gray-500 uppercase tracking-[0.15em] mb-1.5">Jenis Produk</label>
                                    <select name="category_id" id="sub-category" onchange="updateDynamicFields()" class="w-full bg-elvo-bg border border-white/[0.06] rounded-xl px-4 h-11 text-sm font-bold text-white focus:border-elvo-primary outline-none appearance-none transition-all" required>
                                        <option value="">Pilih Kategori Utama dulu</option>
                                    </select>
                                </div>
                                <div>
                                    <label class="block text-[9px] font-black text-gray-500 uppercase tracking-[0.15em] mb-1.5">Material</label>
                                    <input type="text" name="material" class="w-full bg-elvo-bg border border-white/[0.06] rounded-xl px-4 h-11 text-sm font-bold text-white focus:border-elvo-primary outline-none transition-all" placeholder="Cotton, Leather">
                                </div>
                            </div>

                            {{-- Warna + Berat + Conditional --}}
                            <div class="grid grid-cols-3 gap-4">
                                <div class="field-warna">
                                    <label class="block text-[9px] font-black text-gray-500 uppercase tracking-[0.15em] mb-1.5">Warna</label>
                                    <input type="text" name="color" class="w-full bg-elvo-bg border border-white/[0.06] rounded-xl px-4 h-11 text-sm font-bold text-white focus:border-elvo-primary outline-none transition-all" placeholder="Hitam / Putih">
                                </div>
                                <div class="field-berat">
                                    <label class="block text-[9px] font-black text-gray-500 uppercase tracking-[0.15em] mb-1.5">Berat (g)</label>
                                    <input type="number" name="weight" class="w-full bg-elvo-bg border border-white/[0.06] rounded-xl px-4 h-11 text-sm font-bold text-white focus:border-elvo-primary outline-none transition-all">
                                </div>
                                <div class="field-diameter hidden">
                                    <label class="block text-[9px] font-black text-gray-500 uppercase tracking-[0.15em] mb-1.5">Diameter Jam (cm)</label>
                                    <input type="number" name="diameter" step="0.1" class="w-full bg-elvo-bg border border-white/[0.06] rounded-xl px-4 h-11 text-sm font-bold text-white focus:border-elvo-primary outline-none transition-all" placeholder="4.2">
                                </div>
                                <div class="field-panjang-kalung hidden">
                                    <label class="block text-[9px] font-black text-gray-500 uppercase tracking-[0.15em] mb-1.5">Panjang Kalung (cm)</label>
                                    <input type="number" name="panjang_kalung" step="0.1" class="w-full bg-elvo-bg border border-white/[0.06] rounded-xl px-4 h-11 text-sm font-bold text-white focus:border-elvo-primary outline-none transition-all" placeholder="45">
                                </div>
                                <div class="field-kapasitas hidden">
                                    <label class="block text-[9px] font-black text-gray-500 uppercase tracking-[0.15em] mb-1.5">Kapasitas</label>
                                    <input type="text" name="kapasitas" class="w-full bg-elvo-bg border border-white/[0.06] rounded-xl px-4 h-11 text-sm font-bold text-white focus:border-elvo-primary outline-none transition-all" placeholder="10L">
                                </div>
                            </div>

                            {{-- Deskripsi --}}
                            <div>
                                <label class="block text-[9px] font-black text-gray-500 uppercase tracking-[0.15em] mb-1.5">Deskripsi</label>
                                <textarea name="description" rows="3"
                                    class="w-full bg-elvo-bg border border-white/[0.06] rounded-xl px-4 py-3 text-sm text-gray-300 focus:border-elvo-primary outline-none resize-none transition-all leading-relaxed h-[88px]" required placeholder="Jelaskan detail produk, bahan, dan keunggulannya..."></textarea>
                            </div>
                        </div>
                    </div>

                    {{-- VARIANTS --}}
                    <div class="bg-elvo-surface p-5 rounded-2xl border border-white/[0.06] shadow-lg">
                        <div class="flex items-center justify-between mb-3">
                            <div>
                                <label class="block text-[9px] font-black text-gray-500 uppercase tracking-[0.15em]">Varian Produk</label>
                                <p id="variant-type-hint" class="text-[8px] text-gray-600 font-bold mt-0.5">Pilih Kategori Utama untuk petunjuk varian</p>
                            </div>
                        </div>

                        {{-- COLORS --}}
                        <div class="mb-4">
                            <label class="block text-[8px] font-black text-gray-500 uppercase tracking-[0.15em] mb-2">Warna</label>
                            <div class="flex items-center gap-2 flex-wrap">
                                <input type="text" id="color-name-input" placeholder="Nama Warna" class="w-36 bg-elvo-bg border border-white/[0.06] rounded-lg px-3 h-9 text-xs text-white focus:border-elvo-primary outline-none">
                                <input type="color" id="color-hex-input" value="#000000" class="w-9 h-9 rounded-lg bg-elvo-bg border border-white/[0.06] cursor-pointer p-0.5">
                                <label class="flex items-center gap-1.5 px-3 h-9 bg-elvo-bg border border-white/[0.06] rounded-lg cursor-pointer hover:border-elvo-primary/50 text-gray-400 hover:text-white text-[9px] font-bold transition-all">
                                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" stroke-width="2"/></svg>
                                    Foto
                                    <input type="file" id="color-image-input" accept="image/*" class="hidden">
                                </label>
                                <button type="button" onclick="addColor()" class="px-3 h-9 bg-elvo-primary/20 text-elvo-primary text-[9px] font-bold rounded-lg hover:bg-elvo-primary/30 transition-all">+ Tambah Warna</button>
                            </div>
                            <div id="color-chips" class="flex flex-wrap gap-2 mt-2"></div>
                            <div id="color-image-data"></div>
                        </div>

                        {{-- SIZES --}}
                        <div id="sizes-section" class="mb-4 hidden">
                            <label class="block text-[8px] font-black text-gray-500 uppercase tracking-[0.15em] mb-2">Ukuran</label>
                            <div id="size-presets" class="flex flex-wrap gap-1.5 mb-2"></div>
                            <div class="flex items-center gap-2">
                                <input type="text" id="size-name-input" placeholder="Ukuran kustom" class="w-28 bg-elvo-bg border border-white/[0.06] rounded-lg px-3 h-9 text-xs text-white focus:border-elvo-primary outline-none">
                                <button type="button" onclick="addSize()" class="px-3 h-9 bg-elvo-primary/20 text-elvo-primary text-[9px] font-bold rounded-lg hover:bg-elvo-primary/30 transition-all">+ Tambah Ukuran</button>
                            </div>
                            <div id="size-chips" class="flex flex-wrap gap-2 mt-2"></div>
                        </div>

                        {{-- MATRIX TABLE (size categories) --}}
                        <div id="matrix-section" class="hidden overflow-x-auto">
                            <table class="w-full text-xs border border-white/[0.06] rounded-xl overflow-hidden">
                                <thead>
                                    <tr class="bg-white/[0.03] text-[8px] text-gray-500 uppercase tracking-widest font-black">
                                        <th class="py-2 px-3 text-left min-w-[140px] border-r border-white/[0.06]">Warna \ Ukuran</th>
                                        <th id="matrix-header-row" class="py-2 px-2 text-center" colspan="1">Tambahkan warna & ukuran</th>
                                    </tr>
                                </thead>
                                <tbody id="matrix-body"></tbody>
                            </table>
                        </div>

                        {{-- COLOR LIST (non-size categories) --}}
                        <div id="color-list-section" class="hidden overflow-x-auto">
                            <table class="w-full text-xs border border-white/[0.06] rounded-xl overflow-hidden">
                                <thead>
                                    <tr class="bg-white/[0.03] text-[8px] text-gray-500 uppercase tracking-widest font-black">
                                        <th class="py-2 px-3 text-left">Warna</th>
                                        <th class="py-2 px-2 text-center w-[70px]">Stok</th>
                                        <th class="py-2 px-2 text-center w-[100px]">Harga</th>
                                        <th class="py-2 px-2 text-center w-[56px]">Foto</th>
                                        <th class="py-2 px-2 text-center w-[28px]"></th>
                                    </tr>
                                </thead>
                                <tbody id="color-list-body"></tbody>
                            </table>
                        </div>
                    </div>
                </div>

                {{-- RIGHT: Media + Pricing (5/12) --}}
                <div class="col-span-12 lg:col-span-5 space-y-5">
                    {{-- Media --}}
                    <div class="bg-elvo-surface p-5 rounded-2xl border border-white/[0.06] shadow-lg">
                        <label class="block text-[9px] font-black text-gray-500 uppercase tracking-[0.15em] mb-3">Foto Utama</label>
                        <div class="flex items-center gap-3">
                            <div id="imagePreviewContainer" class="hidden w-[88px] h-[88px] rounded-xl bg-elvo-bg border border-white/[0.06] overflow-hidden flex-shrink-0">
                                <img id="imagePreview" src="#" class="w-full h-full object-cover">
                            </div>
                            <label class="flex-1 h-[88px] border-2 border-dashed border-white/[0.06] rounded-xl flex flex-col items-center justify-center cursor-pointer hover:border-elvo-primary/50 transition-all group">
                                <svg class="w-5 h-5 mb-1 text-gray-500 group-hover:text-elvo-primary transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M12 4v16m8-8H4" stroke-width="2" stroke-linecap="round"/></svg>
                                <span class="text-[8px] text-gray-500 font-bold uppercase tracking-widest group-hover:text-white">Upload</span>
                                <input type="file" name="image" accept="image/*" class="hidden" onchange="previewImage(this)" />
                            </label>
                        </div>
                    </div>

                    <div class="bg-elvo-surface p-5 rounded-2xl border border-white/[0.06] shadow-lg">
                        <div class="flex items-center justify-between mb-3">
                            <label class="block text-[9px] font-black text-gray-500 uppercase tracking-[0.15em]">Galeri Foto</label>
                            <label class="text-[8px] font-bold text-elvo-primary hover:text-white cursor-pointer transition-colors">+ Tambah</label>
                        </div>
                        <div id="galleryPreview" class="flex flex-wrap gap-2 mb-2"></div>
                        <label class="flex items-center justify-center w-full h-[72px] border-2 border-dashed border-white/[0.06] rounded-xl cursor-pointer hover:border-elvo-primary/50 transition-all group">
                            <div class="flex items-center gap-2 text-gray-500 group-hover:text-white">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" stroke-width="2"/></svg>
                                <span class="text-[9px] font-bold uppercase tracking-widest">Multi Upload</span>
                            </div>
                            <input type="file" name="images[]" multiple accept="image/*" class="hidden" onchange="previewGallery(this)" />
                        </label>
                    </div>

                    {{-- Sticky Pricing Panel --}}
                    <div class="sticky top-24">
                        <div class="bg-elvo-surface p-5 rounded-2xl border border-white/[0.06] shadow-lg">
                            <div class="mb-4">
                                <label class="block text-[9px] font-black text-gray-500 uppercase tracking-[0.15em] mb-1.5">Harga Jual (Rp)</label>
                                <div class="flex items-center gap-2 bg-elvo-bg border border-white/[0.06] rounded-xl px-4 h-11 focus-within:border-elvo-primary transition-all">
                                    <span class="text-sm font-black text-gray-500">Rp</span>
                                    <input type="number" name="price" class="bg-transparent w-full text-lg font-black text-white outline-none border-none p-0 focus:ring-0" placeholder="0" required>
                                </div>
                            </div>

                            <div class="mb-4">
                                <label class="block text-[9px] font-black text-gray-500 uppercase tracking-[0.15em] mb-1.5">Stok <span class="text-[7px] text-elvo-primary/60 font-bold uppercase tracking-widest">(Auto)</span></label>
                                <div class="flex items-center gap-2 bg-elvo-bg border border-white/[0.06] rounded-xl px-4 h-11 opacity-70">
                                    <svg class="w-4 h-4 text-gray-500 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/></svg>
                                    <input type="number" id="product-stock" readonly
                                        class="bg-transparent w-full text-sm font-bold text-white outline-none border-none p-0 focus:ring-0 cursor-default" placeholder="0">
                                    <span class="text-[7px] text-elvo-primary/60 font-bold uppercase tracking-widest shrink-0">Auto</span>
                                </div>
                                <p class="text-[7px] text-gray-600 mt-1">Stok otomatis dari jumlah stok varian</p>
                            </div>

                            <div class="flex items-center justify-between py-3 border-t border-white/[0.06]">
                                <div>
                                    <p class="text-[9px] font-black text-gray-500 uppercase tracking-[0.15em]">Live</p>
                                    <p class="text-[8px] text-gray-600">Tampilkan di etalase</p>
                                </div>
                                <label class="relative inline-flex items-center cursor-pointer">
                                    <input type="hidden" name="is_active" value="0">
                                    <input type="checkbox" name="is_active" value="1" checked class="sr-only peer">
                                    <div class="w-11 h-6 bg-gray-700 peer-focus:outline-none rounded-full peer peer-checked:after:translate-x-full rtl:peer-checked:after:-translate-x-full after:content-[''] after:absolute after:top-[2px] after:start-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-elvo-primary"></div>
                                </label>
                            </div>

                            <div class="flex items-center gap-2 p-2.5 rounded-lg mt-2 bg-green-500/10 border border-green-500/20">
                                <div class="w-1.5 h-1.5 rounded-full bg-green-500 animate-pulse"></div>
                                <span class="text-[8px] font-black uppercase tracking-widest text-green-500">Aktif & Live</span>
                            </div>

                            <div class="flex gap-2 mt-4">
                                <button type="button" onclick="toggleModal('modal-tambah')" class="flex-1 h-11 border border-white/[0.06] text-gray-400 text-[9px] font-black uppercase tracking-[0.2em] rounded-xl hover:text-white hover:border-white/20 transition-all">
                                    Batal
                                </button>
                                <button type="submit" class="flex-1 h-11 bg-white text-black text-[9px] font-black uppercase tracking-[0.2em] rounded-xl hover:bg-elvo-primary hover:text-white transition-all shadow-lg">
                                    Simpan
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </form>
    </div>
</div>

<script>
    const subCategories = @json(\App\Models\Category::whereNotNull('parent_id')->get(['id', 'name', 'parent_id']));
    const sizeParents = ['fashion', 'outerwear', 'bawahan', 'footwear'];

    const SIZE_PRESETS = {
        fashion: ['S', 'M', 'L', 'XL'],
        outerwear: ['S', 'M', 'L', 'XL'],
        bawahan: ['28', '30', '32', '34', '36'],
        footwear: ['39', '40', '41', '42', '43'],
    };

    let colors = [];
    let sizes = [];
    let matrix = {};

    function updateSubCategories() {
        const parentId = document.getElementById('main-category').value;
        const subSelect = document.getElementById('sub-category');
        subSelect.innerHTML = '<option value="">Pilih Jenis Produk</option>';
        subSelect.disabled = !parentId;
        if (!parentId) return;
        const filtered = subCategories.filter(c => c.parent_id == parentId);
        filtered.forEach(c => {
            const opt = document.createElement('option');
            opt.value = c.id;
            opt.textContent = c.name;
            subSelect.appendChild(opt);
        });
        updateDynamicFields();
    }

    function updateDynamicFields() {
        const parentEl = document.getElementById('main-category');
        const parentId = parentEl.value;
        const parentName = parentEl.options[parentEl.selectedIndex]?.text?.toLowerCase() || '';

        const subSelect = document.getElementById('sub-category');
        const childName = subSelect.value ? subSelect.options[subSelect.selectedIndex]?.text?.toLowerCase() || '' : '';

        const isClothing = parentName === 'fashion' || parentName === 'outerwear';
        const isBawahan = parentName === 'bawahan';
        const isFootwear = parentName === 'footwear';
        const isAksesoris = parentName === 'aksesoris';
        const isElektronik = parentName === 'elektronik';
        const usesSize = isClothing || isBawahan || isFootwear;

        toggle('.field-berat', isAksesoris || isElektronik);
        toggle('.field-diameter', isAksesoris && childName === 'jam tangan');
        toggle('.field-panjang-kalung', isAksesoris && childName === 'kalung');
        toggle('.field-kapasitas', isAksesoris && childName === 'tas');

        updateVariantHint(parentName, usesSize);

        const sizesSection = document.getElementById('sizes-section');
        const matrixSection = document.getElementById('matrix-section');
        const colorListSection = document.getElementById('color-list-section');

        if (usesSize) {
            sizesSection.classList.remove('hidden');
            matrixSection.classList.remove('hidden');
            colorListSection.classList.add('hidden');
            updateSizePresets(parentName);
            renderMatrix();
        } else {
            sizesSection.classList.add('hidden');
            matrixSection.classList.add('hidden');
            colorListSection.classList.remove('hidden');
            renderColorList();
        }
    }

    function updateSizePresets(parentName) {
        const container = document.getElementById('size-presets');
        if (!container) return;
        const presets = SIZE_PRESETS[parentName] || ['S', 'M', 'L', 'XL'];
        container.innerHTML = '<span class="text-[8px] text-gray-500 font-bold uppercase tracking-widest mr-1 self-center">Quick Size:</span>';
        presets.forEach(size => {
            const btn = document.createElement('button');
            btn.type = 'button';
            btn.textContent = size;
            btn.className = 'size-preset-btn px-2.5 py-1 text-[9px] font-bold border border-white/[0.06] rounded-lg text-gray-400 hover:text-white hover:border-elvo-primary hover:bg-elvo-primary/10 transition-all';
            btn.onclick = function() { addSizePreset(size); };
            container.appendChild(btn);
        });
    }

    function addSizePreset(s) {
        if (sizes.includes(s)) return;
        sizes.push(s);
        renderSizeChips();
        renderMatrix();
        updateProductStock();
    }

    function addSize() {
        const input = document.getElementById('size-name-input');
        const s = input.value.trim();
        if (!s || sizes.includes(s)) return;
        sizes.push(s);
        input.value = '';
        renderSizeChips();
        renderMatrix();
        updateProductStock();
    }

    function removeSize(s) {
        sizes = sizes.filter(x => x !== s);
        Object.keys(matrix).forEach(key => {
            if (key.endsWith('_' + s)) delete matrix[key];
        });
        renderSizeChips();
        renderMatrix();
        updateProductStock();
    }

    function renderSizeChips() {
        const container = document.getElementById('size-chips');
        container.innerHTML = '';
        sizes.forEach(s => {
            const chip = document.createElement('span');
            chip.className = 'inline-flex items-center gap-1.5 px-2.5 py-1 bg-elvo-bg border border-white/[0.06] rounded-lg text-[10px] font-bold text-white';
            chip.innerHTML = `${s} <button type="button" onclick="removeSize('${s}')" class="text-red-400 hover:text-red-300">&times;</button>`;
            container.appendChild(chip);
        });
    }

    function addColor() {
        const nameInput = document.getElementById('color-name-input');
        const hexInput = document.getElementById('color-hex-input');
        const imgInput = document.getElementById('color-image-input');
        const name = nameInput.value.trim();
        if (!name || colors.some(c => c.name === name)) return;
        const color = { name, hex: hexInput.value, image: imgInput.files[0] || null };
        colors.push(color);
        nameInput.value = '';
        imgInput.value = '';
        renderColorChips();
        renderMatrix();
        renderColorList();
        updateProductStock();
    }

    function removeColor(name) {
        colors = colors.filter(c => c.name !== name);
        Object.keys(matrix).forEach(key => {
            if (key.startsWith(name + '_')) delete matrix[key];
        });
        renderColorChips();
        renderMatrix();
        renderColorList();
        updateProductStock();
    }

    function renderColorChips() {
        const container = document.getElementById('color-chips');
        container.innerHTML = '';
        colors.forEach(c => {
            const chip = document.createElement('span');
            chip.className = 'inline-flex items-center gap-1.5 px-2.5 py-1 bg-elvo-bg border border-white/[0.06] rounded-lg text-[10px] font-bold text-white';
            let imgHtml = '';
            if (c.image) {
                imgHtml = `<img src="${URL.createObjectURL(c.image)}" class="w-5 h-5 rounded object-cover">`;
            }
            chip.innerHTML = `<span class="w-3 h-3 rounded-full inline-block" style="background:${c.hex}"></span> ${c.name} ${imgHtml} <button type="button" onclick="removeColor('${c.name}')" class="text-red-400 hover:text-red-300">&times;</button>`;
            container.appendChild(chip);
        });
    }

    function renderMatrix() {
        const section = document.getElementById('matrix-section');
        if (!section) return;
        if (colors.length === 0 || sizes.length === 0) {
            const header = document.getElementById('matrix-header-row');
            header.colSpan = 1;
            header.textContent = 'Tambahkan warna & ukuran terlebih dahulu';
            document.getElementById('matrix-body').innerHTML = '';
            return;
        }

        const headerRow = document.getElementById('matrix-header-row');
        headerRow.colSpan = sizes.length;
        headerRow.innerHTML = sizes.map(s => `<th class="py-2 px-2 text-center border-r border-white/[0.06]">${s}</th>`).join('');

        const body = document.getElementById('matrix-body');
        body.innerHTML = '';
        colors.forEach(c => {
            const tr = document.createElement('tr');
            tr.className = 'border-t border-white/[0.06]';
            let imgHtml = '';
            if (c.image) {
                imgHtml = `<img src="${URL.createObjectURL(c.image)}" class="w-6 h-6 rounded object-cover inline-block align-middle ml-1">`;
            }
            let cellsHtml = `<td class="py-1.5 px-3 text-[10px] font-bold text-white border-r border-white/[0.06] whitespace-nowrap"><span class="w-3 h-3 rounded-full inline-block align-middle" style="background:${c.hex}"></span> ${c.name} ${imgHtml}</td>`;

            sizes.forEach(s => {
                const key = c.name + '_' + s;
                const cell = matrix[key] || { enabled: false, stock: '', price: '' };
                const checked = cell.enabled ? 'checked' : '';
                const disabled = cell.enabled ? '' : 'opacity-40 pointer-events-none';
                cellsHtml += `<td class="py-1.5 px-1.5 text-center border-r border-white/[0.06] align-top">
                    <label class="flex items-center justify-center gap-1 cursor-pointer">
                        <input type="checkbox" onchange="toggleCell('${c.name}','${s}',this)" ${checked} class="w-3 h-3 rounded bg-elvo-bg border-white/10 text-elvo-primary focus:ring-elvo-primary">
                        <span class="text-[7px] text-gray-500 uppercase font-bold">On</span>
                    </label>
                    <div class="matrix-cell-inputs ${disabled} mt-0.5 space-y-0.5">
                        <input type="number" value="${cell.stock}" onchange="setCellStock('${c.name}','${s}',this.value)" placeholder="Stok" class="w-full bg-elvo-bg border border-white/[0.06] rounded px-1.5 h-6 text-[9px] text-white text-right focus:border-elvo-primary outline-none" ${cell.enabled ? '' : 'disabled'}>
                        <input type="text" value="${cell.price ? Number(cell.price).toLocaleString('id-ID') : ''}" onfocus="this.value=this.value.replace(/\./g,'')" onchange="setCellPrice('${c.name}','${s}',this)" placeholder="Harga" class="w-full bg-elvo-bg border border-white/[0.06] rounded px-1.5 h-6 text-[9px] text-white text-right focus:border-elvo-primary outline-none" ${cell.enabled ? '' : 'disabled'}>
                    </div>
                </td>`;
            });

            tr.innerHTML = cellsHtml;
            body.appendChild(tr);
        });
        updateProductStock();
    }

    function toggleCell(color, size, cb) {
        const key = color + '_' + size;
        if (cb.checked) {
            matrix[key] = matrix[key] || { enabled: true, stock: '', price: '' };
            matrix[key].enabled = true;
        } else {
            if (matrix[key]) matrix[key].enabled = false;
        }
        renderMatrix();
        updateProductStock();
    }

    function setCellStock(color, size, val) {
        const key = color + '_' + size;
        matrix[key] = matrix[key] || { enabled: true, stock: '', price: '' };
        matrix[key].stock = val;
        updateProductStock();
    }

    function setCellPrice(color, size, input) {
        const key = color + '_' + size;
        matrix[key] = matrix[key] || { enabled: true, stock: '', price: '' };
        const raw = input.value.replace(/[^0-9]/g, '');
        matrix[key].price = raw;
        if (raw) input.value = Number(raw).toLocaleString('id-ID');
    }

    function renderColorList() {
        const body = document.getElementById('color-list-body');
        if (!body) return;
        body.innerHTML = '';
        if (colors.length === 0) {
            body.innerHTML = '<tr><td colspan="5" class="py-6 text-center text-[9px] text-gray-600 italic">Belum ada warna. Tambahkan warna di atas.</td></tr>';
            return;
        }
        colors.forEach(c => {
            const tr = document.createElement('tr');
            tr.className = 'border-t border-white/[0.06]';
            const key = c.name + '_';
            const cell = matrix[key] || { enabled: true, stock: '', price: '' };
            let imgHtml = '';
            if (c.image) {
                imgHtml = `<img src="${URL.createObjectURL(c.image)}" class="w-6 h-6 rounded object-cover inline-block">`;
            }
            tr.innerHTML = `<td class="py-2 px-3 text-[10px] font-bold text-white"><span class="w-3 h-3 rounded-full inline-block align-middle" style="background:${c.hex}"></span> ${c.name}</td>
                <td class="py-2 px-2 text-center"><input type="number" value="${cell.stock}" onchange="setCellStock('${c.name}','',this.value)" placeholder="Stok" class="w-16 bg-elvo-bg border border-white/[0.06] rounded px-2 h-7 text-[10px] text-white text-right focus:border-elvo-primary outline-none"></td>
                <td class="py-2 px-2 text-center"><input type="text" value="${cell.price ? Number(cell.price).toLocaleString('id-ID') : ''}" onfocus="this.value=this.value.replace(/\./g,'')" onchange="setCellPrice('${c.name}','',this)" placeholder="Harga" class="w-24 bg-elvo-bg border border-white/[0.06] rounded px-2 h-7 text-[10px] text-white text-right focus:border-elvo-primary outline-none"></td>
                <td class="py-2 px-2 text-center">${imgHtml ? imgHtml : '<span class="text-[8px] text-gray-500">none</span>'}</td>
                <td class="py-2 px-2 text-center"><button type="button" onclick="removeColor('${c.name}')" class="text-red-400 hover:text-red-300 text-xs">&times;</button></td>`;
            body.appendChild(tr);
        });
        updateProductStock();
    }

    function updateProductStock() {
        const hint = document.getElementById('variant-type-hint');
        const usesSize = hint && hint.textContent.includes('Ukuran tersedia');
        const stockInput = document.getElementById('product-stock');
        if (!stockInput) return;
        let total = 0;
        if (usesSize) {
            colors.forEach(c => {
                sizes.forEach(s => {
                    const key = c.name + '_' + s;
                    const cell = matrix[key];
                    if (cell && cell.enabled && cell.stock) total += Number(cell.stock);
                });
            });
        } else {
            colors.forEach(c => {
                const key = c.name + '_';
                const cell = matrix[key] || { enabled: true, stock: '', price: '' };
                if (cell.stock) total += Number(cell.stock);
            });
        }
        stockInput.value = total;
    }

    function toggle(selector, show) {
        const el = document.querySelector(selector);
        if (el) el.classList.toggle('hidden', !show);
    }

    function updateVariantHint(parentName, usesSize) {
        const hint = document.getElementById('variant-type-hint');
        if (!hint) return;
        if (usesSize) {
            const presets = SIZE_PRESETS[parentName] || ['S', 'M', 'L', 'XL'];
            hint.textContent = 'Ukuran tersedia: ' + presets.join(', ');
            hint.className = 'text-[8px] text-elvo-primary font-bold';
        } else {
            hint.textContent = 'Varian Warna saja (ukuran tidak diperlukan)';
            hint.className = 'text-[8px] text-gray-500 font-bold';
        }
    }

    function toggleModal(id) {
        const modal = document.getElementById(id);
        if (modal.classList.contains('hidden')) {
            modal.classList.remove('hidden');
            modal.classList.add('flex');
            document.body.style.overflow = 'hidden';
        } else {
            modal.classList.add('hidden');
            modal.classList.remove('flex');
            document.body.style.overflow = 'auto';
        }
    }

    async function toggleStatus(productId, btn) {
        try {
            const response = await fetch(`/admin/products/toggle-status/${productId}`, {
                method: 'PATCH',
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                    'Content-Type': 'application/json',
                    'Accept': 'application/json'
                }
            });
            const data = await response.json();
            if (data.success) {
                const dot = btn.querySelector('span:last-child');
                const label = btn.nextElementSibling;
                if (data.is_active) {
                    btn.classList.remove('bg-gray-700');
                    btn.classList.add('bg-elvo-primary');
                    dot.classList.remove('translate-x-1');
                    dot.classList.add('translate-x-6');
                    label.textContent = 'Visible';
                    label.classList.remove('text-gray-500');
                    label.classList.add('text-elvo-primary');
                } else {
                    btn.classList.remove('bg-elvo-primary');
                    btn.classList.add('bg-gray-700');
                    dot.classList.remove('translate-x-6');
                    dot.classList.add('translate-x-1');
                    label.textContent = 'Hidden';
                    label.classList.remove('text-elvo-primary');
                    label.classList.add('text-gray-500');
                }
            }
        } catch (error) {
            console.error('Error toggling status:', error);
            alert('Gagal mengubah status produk.');
        }
    }

    function previewImage(input) {
        const container = document.getElementById('imagePreviewContainer');
        const preview = document.getElementById('imagePreview');
        if (input.files && input.files[0]) {
            const reader = new FileReader();
            reader.onload = function(e) {
                preview.src = e.target.result;
                container.classList.remove('hidden');
            }
            reader.readAsDataURL(input.files[0]);
        }
    }

    function generateSlug(text) {
        const slug = text.toLowerCase().replace(/[^\w ]+/g, '').replace(/ +/g, '-');
        document.getElementById('productSlug').value = slug;
    }

    function previewGallery(input) {
        const container = document.getElementById('galleryPreview');
        container.innerHTML = '';
        for (const file of input.files) {
            const reader = new FileReader();
            reader.onload = function(e) {
                const div = document.createElement('div');
                div.className = 'w-[72px] h-[72px] rounded-xl overflow-hidden border border-white/10';
                div.innerHTML = `<img src="${e.target.result}" class="w-full h-full object-cover">`;
                container.appendChild(div);
            }
            reader.readAsDataURL(file);
        }
    }

    document.querySelector('#modal-tambah form').addEventListener('submit', function(e) {
        const hint = document.getElementById('variant-type-hint');
        const usesSize = hint && hint.textContent.includes('Ukuran tersedia');
        const existing = this.querySelector('input[name^="variants"]');
        if (existing) return;

        let idx = 0;
        const flatContainer = document.createElement('div');
        flatContainer.style.display = 'none';

        if (usesSize) {
            colors.forEach(c => {
                sizes.forEach(s => {
                    const key = c.name + '_' + s;
                    const cell = matrix[key];
                    if (cell && cell.enabled) {
                        const prefix = 'variants[' + (idx++) + ']';
                        addHidden(flatContainer, prefix + '[size]', s);
                        addHidden(flatContainer, prefix + '[color]', c.name);
                        addHidden(flatContainer, prefix + '[color_hex]', c.hex);
                        addHidden(flatContainer, prefix + '[stock]', cell.stock || 0);
                        if (cell.price) addHidden(flatContainer, prefix + '[price]', cell.price);
                        if (c.image) {
                            const fileInput = document.createElement('input');
                            fileInput.type = 'file';
                            fileInput.name = prefix + '[image]';
                            fileInput.className = 'hidden';
                            const dt = new DataTransfer();
                            dt.items.add(c.image);
                            fileInput.files = dt.files;
                            flatContainer.appendChild(fileInput);
                        }
                    }
                });
            });
        } else {
            colors.forEach(c => {
                const key = c.name + '_';
                const cell = matrix[key] || { enabled: true, stock: '', price: '' };
                const prefix = 'variants[' + (idx++) + ']';
                addHidden(flatContainer, prefix + '[color]', c.name);
                addHidden(flatContainer, prefix + '[color_hex]', c.hex);
                addHidden(flatContainer, prefix + '[stock]', cell.stock || 0);
                if (cell.price) addHidden(flatContainer, prefix + '[price]', cell.price);
                if (c.image) {
                    const fileInput = document.createElement('input');
                    fileInput.type = 'file';
                    fileInput.name = prefix + '[image]';
                    fileInput.className = 'hidden';
                    const dt = new DataTransfer();
                    dt.items.add(c.image);
                    fileInput.files = dt.files;
                    flatContainer.appendChild(fileInput);
                }
            });
        }

        // Ensure stock is submitted
        const stockVal = document.getElementById('product-stock')?.value || '0';
        addHidden(flatContainer, 'stock', stockVal);
        this.appendChild(flatContainer);
    });

    function addHidden(parent, name, value) {
        const input = document.createElement('input');
        input.type = 'hidden';
        input.name = name;
        input.value = value;
        parent.appendChild(input);
    }
</script>

<style>
    @keyframes modalIn {
        from { transform: scale(0.9) translateY(20px); opacity: 0; }
        to { transform: scale(1) translateY(0); opacity: 1; }
    }
    .animate-modal-in {
        animation: modalIn 0.3s cubic-bezier(0.34, 1.56, 0.64, 1);
    }
    
    /* Custom Scrollbar for Modal */
    #modal-tambah::-webkit-scrollbar {
        width: 8px;
    }
    #modal-tambah::-webkit-scrollbar-track {
        background: transparent;
    }
    #modal-tambah::-webkit-scrollbar-thumb {
        background: rgba(255, 255, 255, 0.1);
        border-radius: 10px;
    }

    input::-webkit-outer-spin-button,
    input::-webkit-inner-spin-button { -webkit-appearance: none; margin: 0; }
    .variant-table td { vertical-align: middle; }
</style>
@endsection