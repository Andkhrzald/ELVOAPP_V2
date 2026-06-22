@extends('layouts.app')

@section('content')
<div class="min-h-screen text-gray-300 pb-8">

    {{-- Header --}}
    <div class="flex items-end justify-between mb-6 px-4 lg:px-6 pt-4">
        <div>
            <p class="text-[9px] font-black text-elvo-primary uppercase tracking-[0.3em] mb-1">Management / Inventory / Edit</p>
            <h1 class="text-2xl font-black text-white tracking-tight">Edit Data Produk</h1>
        </div>
        <a href="{{ route('admin.products') }}" class="flex items-center gap-2 text-[9px] font-black text-gray-500 hover:text-white uppercase tracking-widest bg-white/5 px-3 py-2 rounded-lg border border-white/[0.06] transition-all">
            <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M15 19l-7-7 7-7" stroke-width="3" stroke-linecap="round" stroke-linejoin="round"/></svg>
            Kembali
        </a>
    </div>

    <div class="px-4 lg:px-6">
        <form action="{{ route('admin.products.update', $product->id) }}" method="POST" enctype="multipart/form-data" class="grid grid-cols-12 gap-5">
            @csrf
            @method('PUT')

            {{-- LEFT: Informasi Produk (7/12) --}}
            <div class="col-span-12 lg:col-span-7 xl:col-span-7 space-y-5">
                <div class="bg-elvo-surface p-5 rounded-2xl border border-white/[0.06] shadow-lg">
                    <div class="space-y-5">
                        {{-- Nama + Slug --}}
                        <div>
                            <label class="block text-[9px] font-black text-gray-500 uppercase tracking-[0.15em] mb-1.5">Nama Produk</label>
                            <input type="text" name="name" value="{{ old('name', $product->name) }}" id="productName" onkeyup="generateSlug(this.value)"
                                class="w-full bg-elvo-bg border border-white/[0.06] rounded-xl px-4 h-11 text-sm font-bold text-white focus:border-elvo-primary outline-none transition-all" required>
                        </div>
                        <div class="flex items-center gap-2 bg-[#0a0a0a] border border-white/[0.06] rounded-xl px-4 h-10 text-[9px] text-gray-500 font-mono">
                            <span class="font-black text-gray-600 uppercase tracking-widest">Slug:</span>
                            <span>elvo.com/shop/</span>
                            <input type="text" name="slug" id="productSlug" value="{{ old('slug', $product->slug) }}" class="bg-transparent border-none p-0 focus:ring-0 text-[10px] font-black text-elvo-primary w-full" readonly>
                        </div>

                        {{-- Row 2: Kategori + Jenis + Material --}}
                        <div class="grid grid-cols-3 gap-4">
                            <div>
                                <label class="block text-[9px] font-black text-gray-500 uppercase tracking-[0.15em] mb-1.5">Kategori Utama</label>
                                <select id="edit-main-category" onchange="editUpdateSubCategories()" class="w-full bg-elvo-bg border border-white/[0.06] rounded-xl px-4 h-11 text-sm font-bold text-white focus:border-elvo-primary outline-none appearance-none transition-all">
                                    <option value="">Pilih</option>
                                    @foreach($categories->whereNull('parent_id') as $parent)
                                        <option value="{{ $parent->id }}" {{ ($product->category?->parent_id ?? $product->category_id) == $parent->id ? 'selected' : '' }}>{{ $parent->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div>
                                <label class="block text-[9px] font-black text-gray-500 uppercase tracking-[0.15em] mb-1.5">Jenis Produk</label>
                                <select name="category_id" id="edit-sub-category" onchange="editUpdateDynamicFields()" class="w-full bg-elvo-bg border border-white/[0.06] rounded-xl px-4 h-11 text-sm font-bold text-white focus:border-elvo-primary outline-none appearance-none transition-all" required>
                                    <option value="">Pilih</option>
                                    @php
                                    $editParentId = $product->category?->parent_id ?? $product->category_id;
                                    $editChildId = $product->category?->parent_id ? $product->category_id : null;
                                    @endphp
                                    @foreach($categories->where('parent_id', $editParentId) as $child)
                                        <option value="{{ $child->id }}" {{ $editChildId == $child->id ? 'selected' : '' }}>{{ $child->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div>
                                <label class="block text-[9px] font-black text-gray-500 uppercase tracking-[0.15em] mb-1.5">Material</label>
                                <input type="text" name="material" value="{{ old('material', $product->material) }}"
                                    class="w-full bg-elvo-bg border border-white/[0.06] rounded-xl px-4 h-11 text-sm font-bold text-white focus:border-elvo-primary outline-none transition-all" placeholder="Cotton, Leather">
                            </div>
                        </div>

                        {{-- Row 3: Warna + Berat + Conditional --}}
                        <div class="grid grid-cols-3 gap-4">
                            <div class="edit-field-warna">
                                <label class="block text-[9px] font-black text-gray-500 uppercase tracking-[0.15em] mb-1.5">Warna</label>
                                <input type="text" name="color" value="{{ old('color', $product->color) }}"
                                    class="w-full bg-elvo-bg border border-white/[0.06] rounded-xl px-4 h-11 text-sm font-bold text-white focus:border-elvo-primary outline-none transition-all" placeholder="Hitam / Putih">
                            </div>
                            <div class="edit-field-berat">
                                <label class="block text-[9px] font-black text-gray-500 uppercase tracking-[0.15em] mb-1.5">Berat (g)</label>
                                <input type="number" name="weight" value="{{ old('weight', $product->weight) }}"
                                    class="w-full bg-elvo-bg border border-white/[0.06] rounded-xl px-4 h-11 text-sm font-bold text-white focus:border-elvo-primary outline-none transition-all">
                            </div>
                            <div class="edit-field-diameter hidden">
                                <label class="block text-[9px] font-black text-gray-500 uppercase tracking-[0.15em] mb-1.5">Diameter Jam (cm)</label>
                                <input type="number" name="diameter" value="{{ old('diameter', $product->diameter) }}" step="0.1"
                                    class="w-full bg-elvo-bg border border-white/[0.06] rounded-xl px-4 h-11 text-sm font-bold text-white focus:border-elvo-primary outline-none transition-all" placeholder="4.2">
                            </div>
                            <div class="edit-field-panjang-kalung hidden">
                                <label class="block text-[9px] font-black text-gray-500 uppercase tracking-[0.15em] mb-1.5">Panjang Kalung (cm)</label>
                                <input type="number" name="panjang_kalung" value="{{ old('panjang_kalung', $product->panjang_kalung) }}" step="0.1"
                                    class="w-full bg-elvo-bg border border-white/[0.06] rounded-xl px-4 h-11 text-sm font-bold text-white focus:border-elvo-primary outline-none transition-all" placeholder="45">
                            </div>
                            <div class="edit-field-kapasitas hidden">
                                <label class="block text-[9px] font-black text-gray-500 uppercase tracking-[0.15em] mb-1.5">Kapasitas</label>
                                <input type="text" name="kapasitas" value="{{ old('kapasitas', $product->kapasitas) }}"
                                    class="w-full bg-elvo-bg border border-white/[0.06] rounded-xl px-4 h-11 text-sm font-bold text-white focus:border-elvo-primary outline-none transition-all" placeholder="10L">
                            </div>
                        </div>

                        {{-- Deskripsi --}}
                        <div>
                            <label class="block text-[9px] font-black text-gray-500 uppercase tracking-[0.15em] mb-1.5">Deskripsi</label>
                            <textarea name="description" rows="3"
                                class="w-full bg-elvo-bg border border-white/[0.06] rounded-xl px-4 py-3 text-sm text-gray-300 focus:border-elvo-primary outline-none resize-none transition-all leading-relaxed h-[88px]" required>{{ old('description', $product->description) }}</textarea>
                        </div>
                    </div>
                </div>

                {{-- VARIANTS --}}
                <div class="bg-elvo-surface p-5 rounded-2xl border border-white/[0.06] shadow-lg">
                    <div class="flex items-center justify-between mb-3">
                        <div>
                            <label class="block text-[9px] font-black text-gray-500 uppercase tracking-[0.15em]">Varian Produk</label>
                            <p id="edit-variant-hint" class="text-[8px] text-gray-600 font-bold mt-0.5">Pilih Kategori Utama untuk petunjuk varian</p>
                        </div>
                    </div>

                    {{-- COLORS --}}
                    <div class="mb-4">
                        <label class="block text-[8px] font-black text-gray-500 uppercase tracking-[0.15em] mb-2">Warna</label>
                        <div class="flex items-center gap-2 flex-wrap">
                            <input type="text" id="edit-color-name-input" placeholder="Nama Warna" class="w-36 bg-elvo-bg border border-white/[0.06] rounded-lg px-3 h-9 text-xs text-white focus:border-elvo-primary outline-none">
                            <input type="color" id="edit-color-hex-input" value="#000000" class="w-9 h-9 rounded-lg bg-elvo-bg border border-white/[0.06] cursor-pointer p-0.5">
                            <label class="flex items-center gap-1.5 px-3 h-9 bg-elvo-bg border border-white/[0.06] rounded-lg cursor-pointer hover:border-elvo-primary/50 text-gray-400 hover:text-white text-[9px] font-bold transition-all">
                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" stroke-width="2"/></svg>
                                Foto
                                <input type="file" id="edit-color-image-input" accept="image/*" class="hidden">
                            </label>
                            <button type="button" onclick="editAddColor()" class="px-3 h-9 bg-elvo-primary/20 text-elvo-primary text-[9px] font-bold rounded-lg hover:bg-elvo-primary/30 transition-all">+ Tambah Warna</button>
                        </div>
                        <div id="edit-color-chips" class="flex flex-wrap gap-2 mt-2"></div>
                        <div id="edit-color-image-data"></div>
                    </div>

                    {{-- SIZES --}}
                    <div id="edit-sizes-section" class="mb-4 hidden">
                        <label class="block text-[8px] font-black text-gray-500 uppercase tracking-[0.15em] mb-2">Ukuran</label>
                        <div id="edit-size-presets" class="flex flex-wrap gap-1.5 mb-2"></div>
                        <div class="flex items-center gap-2">
                            <input type="text" id="edit-size-name-input" placeholder="Ukuran kustom" class="w-28 bg-elvo-bg border border-white/[0.06] rounded-lg px-3 h-9 text-xs text-white focus:border-elvo-primary outline-none">
                            <button type="button" onclick="editAddSize()" class="px-3 h-9 bg-elvo-primary/20 text-elvo-primary text-[9px] font-bold rounded-lg hover:bg-elvo-primary/30 transition-all">+ Tambah Ukuran</button>
                        </div>
                        <div id="edit-size-chips" class="flex flex-wrap gap-2 mt-2"></div>
                    </div>

                    {{-- MATRIX TABLE --}}
                    <div id="edit-matrix-section" class="hidden overflow-x-auto">
                        <table class="w-full text-xs border border-white/[0.06] rounded-xl overflow-hidden">
                            <thead>
                                <tr class="bg-white/[0.03] text-[8px] text-gray-500 uppercase tracking-widest font-black">
                                    <th class="py-2 px-3 text-left min-w-[140px] border-r border-white/[0.06]">Warna \ Ukuran</th>
                                    <th id="edit-matrix-header-row" class="py-2 px-2 text-center" colspan="1">Tambahkan warna & ukuran</th>
                                </tr>
                            </thead>
                            <tbody id="edit-matrix-body"></tbody>
                        </table>
                    </div>

                    {{-- COLOR LIST --}}
                    <div id="edit-color-list-section" class="hidden overflow-x-auto">
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
                            <tbody id="edit-color-list-body"></tbody>
                        </table>
                    </div>
                </div>
            </div>

            {{-- MIDDLE: Media (3/12) --}}
            <div class="col-span-12 lg:col-span-3 xl:col-span-3 space-y-5">
                <div class="bg-elvo-surface p-5 rounded-2xl border border-white/[0.06] shadow-lg">
                    <label class="block text-[9px] font-black text-gray-500 uppercase tracking-[0.15em] mb-3">Foto Utama</label>
                    <div class="flex items-center gap-3">
                        <div class="w-[88px] h-[88px] rounded-xl bg-elvo-bg border border-white/[0.06] overflow-hidden flex-shrink-0">
                            @if($product->image)
                            <img id="currentImage" src="{{ asset('uploads/' . $product->image) }}" class="w-full h-full object-cover">
                            @else
                            <div class="w-full h-full flex items-center justify-center text-gray-600">
                                <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" stroke-width="2"/></svg>
                            </div>
                            @endif
                        </div>
                        <label class="flex-1 h-[88px] border-2 border-dashed border-white/[0.06] rounded-xl flex flex-col items-center justify-center cursor-pointer hover:border-elvo-primary/50 transition-all group">
                            <svg class="w-5 h-5 mb-1 text-gray-500 group-hover:text-elvo-primary transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M12 4v16m8-8H4" stroke-width="2" stroke-linecap="round"/></svg>
                            <span class="text-[8px] text-gray-500 font-bold uppercase tracking-widest group-hover:text-white">Ganti</span>
                            <input type="file" name="image" class="hidden" onchange="previewImage(this)" />
                        </label>
                    </div>
                </div>

                <div class="bg-elvo-surface p-5 rounded-2xl border border-white/[0.06] shadow-lg">
                    <div class="flex items-center justify-between mb-3">
                        <label class="block text-[9px] font-black text-gray-500 uppercase tracking-[0.15em]">Galeri</label>
                        <label class="text-[8px] font-bold text-elvo-primary hover:text-white cursor-pointer transition-colors">+ Tambah</label>
                    </div>
                    <div class="flex flex-wrap gap-2">
                        @foreach($product->images as $img)
                        <div class="relative w-[72px] h-[72px] rounded-xl overflow-hidden border border-white/10 group">
                            <img src="{{ asset('uploads/' . $img->image) }}" class="w-full h-full object-cover">
                            <div class="absolute inset-0 bg-black/0 group-hover:bg-black/40 transition flex items-center justify-center gap-1 opacity-0 group-hover:opacity-100">
                                @if(!$img->is_primary)
                                <button type="button" onclick="setPrimary({{ $img->id }}, this)" class="w-5 h-5 bg-elvo-primary/90 text-white rounded-full text-[7px] font-bold flex items-center justify-center hover:bg-elvo-primary" title="Utama">★</button>
                                @endif
                                <button type="button" onclick="deleteImage({{ $img->id }}, this)" class="w-5 h-5 bg-red-500/80 text-white rounded-full text-[7px] flex items-center justify-center hover:bg-red-600" title="Hapus">&times;</button>
                            </div>
                            @if($img->is_primary)
                            <div class="absolute bottom-0.5 left-0.5 px-1 py-0.5 bg-elvo-primary/90 text-white text-[6px] font-bold rounded">★</div>
                            @endif
                        </div>
                        @endforeach
                        <label class="w-[72px] h-[72px] rounded-xl border-2 border-dashed border-white/[0.06] flex items-center justify-center cursor-pointer hover:border-elvo-primary/50 text-gray-600 hover:text-white transition-all text-xl">
                            <input type="file" name="images[]" multiple accept="image/*" class="hidden" onchange="previewNewGallery(this)" />
                            +
                        </label>
                    </div>
                    <div id="newGalleryPreview" class="flex flex-wrap gap-2 mt-2"></div>
                </div>
            </div>

            {{-- RIGHT: Sticky Panel (2/12) --}}
            <div class="col-span-12 lg:col-span-2 xl:col-span-2">
                <div class="sticky top-24 space-y-5">
                    <div class="bg-elvo-surface p-5 rounded-2xl border border-white/[0.06] shadow-lg">
                        <div class="mb-4">
                            <label class="block text-[9px] font-black text-gray-500 uppercase tracking-[0.15em] mb-1.5">Harga Jual (Rp)</label>
                            <div class="flex items-center gap-2 bg-elvo-bg border border-white/[0.06] rounded-xl px-4 h-11 focus-within:border-elvo-primary transition-all">
                                <span class="text-sm font-black text-gray-500">Rp</span>
                                <input type="number" name="price" value="{{ old('price', $product->price) }}"
                                    class="bg-transparent w-full text-lg font-black text-white outline-none border-none p-0 focus:ring-0" required>
                            </div>
                        </div>

                        <div class="mb-4">
                            <label class="block text-[9px] font-black text-gray-500 uppercase tracking-[0.15em] mb-1.5">Stok <span class="text-[7px] text-elvo-primary/60 font-bold uppercase tracking-widest">(Auto)</span></label>
                            <div class="flex items-center gap-2 bg-elvo-bg border border-white/[0.06] rounded-xl px-4 h-11 opacity-70">
                                <svg class="w-4 h-4 text-gray-500 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/></svg>
                                <input type="number" id="edit-product-stock" readonly
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
                                <input type="checkbox" name="is_active" value="1" {{ $product->is_active ? 'checked' : '' }} class="sr-only peer">
                                <div class="w-11 h-6 bg-gray-700 peer-focus:outline-none rounded-full peer peer-checked:after:translate-x-full rtl:peer-checked:after:-translate-x-full after:content-[''] after:absolute after:top-[2px] after:start-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-elvo-primary"></div>
                            </label>
                        </div>

                        <div class="flex items-center gap-2 p-2.5 rounded-lg mt-2 {{ $product->is_active ? 'bg-green-500/10 border border-green-500/20' : 'bg-yellow-500/10 border border-yellow-500/20' }}">
                            <div class="w-1.5 h-1.5 rounded-full {{ $product->is_active ? 'bg-green-500 animate-pulse' : 'bg-yellow-500' }}"></div>
                            <span class="text-[8px] font-black uppercase tracking-widest {{ $product->is_active ? 'text-green-500' : 'text-yellow-500' }}">
                                {{ $product->is_active ? 'Aktif & Live' : 'Disembunyikan' }}
                            </span>
                        </div>

                        <button type="submit" class="w-full h-11 mt-4 bg-white text-black text-[9px] font-black uppercase tracking-[0.2em] rounded-xl hover:bg-elvo-primary hover:text-white transition-all shadow-lg">
                            Simpan Perubahan
                        </button>

                        <button type="button" onclick="confirmDelete()" class="w-full h-10 mt-2 border border-red-500/30 text-red-500 text-[8px] font-black uppercase tracking-[0.2em] rounded-xl hover:bg-red-500/10 transition-all">
                            Hapus Produk
                        </button>
                    </div>
                </div>
            </div>
        </form>
    </div>
</div>

<form id="delete-form" action="{{ route('admin.products.destroy', $product->id) }}" method="POST" class="hidden">
    @csrf
    @method('DELETE')
</form>

<script>
    const editSubCategories = @json(\App\Models\Category::whereNotNull('parent_id')->get(['id', 'name', 'parent_id']));
    const sizeParents = ['fashion', 'outerwear', 'bawahan', 'footwear'];

    const SIZE_PRESETS = {
        fashion: ['S', 'M', 'L', 'XL'],
        outerwear: ['S', 'M', 'L', 'XL'],
        bawahan: ['28', '30', '32', '34', '36'],
        footwear: ['39', '40', '41', '42', '43'],
    };

    let editColors = [];
    let editSizes = [];
    let editMatrix = {};

    function editInitVariants(variants) {
        const colorMap = {};
        variants.forEach(v => {
            const color = v.color || 'Default';
            if (!colorMap[color]) {
                colorMap[color] = { name: color, hex: v.color_hex || '#000000', imageUrl: v.image || null, image: null };
                editColors.push(colorMap[color]);
            }
            if (v.size && !editSizes.includes(v.size)) {
                editSizes.push(v.size);
            }
            const key = (v.size || '') ? color + '_' + v.size : color + '_';
            editMatrix[key] = { enabled: true, stock: v.stock || '', price: v.price || '' };
        });
        editRenderColorChips();
        editRenderSizeChips();
        editUpdateMatrixVisibility();
        editUpdateProductStock();
    }

    document.addEventListener('DOMContentLoaded', function() {
        const parentId = document.getElementById('edit-main-category')?.value;
        if (parentId) editUpdateDynamicFields();
        const existingVariants = @json($product->variants);
        if (existingVariants && existingVariants.length > 0) {
            editInitVariants(existingVariants);
        }
    });

    function editUpdateSubCategories() {
        const parentId = document.getElementById('edit-main-category').value;
        const subSelect = document.getElementById('edit-sub-category');
        subSelect.innerHTML = '<option value="">Pilih Jenis Produk</option>';
        subSelect.disabled = !parentId;
        if (!parentId) return;
        const filtered = editSubCategories.filter(c => c.parent_id == parentId);
        filtered.forEach(c => {
            const opt = document.createElement('option');
            opt.value = c.id;
            opt.textContent = c.name;
            subSelect.appendChild(opt);
        });
        editUpdateDynamicFields();
    }

    function editUpdateDynamicFields() {
        const parentEl = document.getElementById('edit-main-category');
        const parentName = parentEl.options[parentEl.selectedIndex]?.text?.toLowerCase() || '';
        const subSelect = document.getElementById('edit-sub-category');
        const childName = subSelect.value ? subSelect.options[subSelect.selectedIndex]?.text?.toLowerCase() || '' : '';

        const isClothing = parentName === 'fashion' || parentName === 'outerwear';
        const isBawahan = parentName === 'bawahan';
        const isFootwear = parentName === 'footwear';
        const isAksesoris = parentName === 'aksesoris';
        const isElektronik = parentName === 'elektronik';
        const usesSize = isClothing || isBawahan || isFootwear;

        editToggle('.edit-field-berat', isAksesoris || isElektronik);
        editToggle('.edit-field-diameter', isAksesoris && childName === 'jam tangan');
        editToggle('.edit-field-panjang-kalung', isAksesoris && childName === 'kalung');
        editToggle('.edit-field-kapasitas', isAksesoris && childName === 'tas');

        editUpdateVariantHint(parentName, usesSize);

        const sizesSection = document.getElementById('edit-sizes-section');
        const matrixSection = document.getElementById('edit-matrix-section');
        const colorListSection = document.getElementById('edit-color-list-section');

        if (usesSize) {
            sizesSection.classList.remove('hidden');
            matrixSection.classList.remove('hidden');
            colorListSection.classList.add('hidden');
            editUpdateSizePresets(parentName);
            editRenderMatrix();
        } else {
            sizesSection.classList.add('hidden');
            matrixSection.classList.add('hidden');
            colorListSection.classList.remove('hidden');
            editRenderColorList();
        }
    }

    function editUpdateSizePresets(parentName) {
        const container = document.getElementById('edit-size-presets');
        if (!container) return;
        const presets = SIZE_PRESETS[parentName] || ['S', 'M', 'L', 'XL'];
        container.innerHTML = '<span class="text-[8px] text-gray-500 font-bold uppercase tracking-widest mr-1 self-center">Quick Size:</span>';
        presets.forEach(size => {
            const btn = document.createElement('button');
            btn.type = 'button';
            btn.textContent = size;
            btn.className = 'size-preset-btn px-2.5 py-1 text-[9px] font-bold border border-white/[0.06] rounded-lg text-gray-400 hover:text-white hover:border-elvo-primary hover:bg-elvo-primary/10 transition-all';
            btn.onclick = function() { editAddSizePreset(size); };
            container.appendChild(btn);
        });
    }

    function editAddSizePreset(s) {
        if (editSizes.includes(s)) return;
        editSizes.push(s);
        editRenderSizeChips();
        editRenderMatrix();
        editUpdateProductStock();
    }

    function editAddSize() {
        const input = document.getElementById('edit-size-name-input');
        const s = input.value.trim();
        if (!s || editSizes.includes(s)) return;
        editSizes.push(s);
        input.value = '';
        editRenderSizeChips();
        editRenderMatrix();
        editUpdateProductStock();
    }

    function editRemoveSize(s) {
        editSizes = editSizes.filter(x => x !== s);
        Object.keys(editMatrix).forEach(key => {
            if (key.endsWith('_' + s)) delete editMatrix[key];
        });
        editRenderSizeChips();
        editRenderMatrix();
        editUpdateProductStock();
    }

    function editRenderSizeChips() {
        const container = document.getElementById('edit-size-chips');
        container.innerHTML = '';
        editSizes.forEach(s => {
            const chip = document.createElement('span');
            chip.className = 'inline-flex items-center gap-1.5 px-2.5 py-1 bg-elvo-bg border border-white/[0.06] rounded-lg text-[10px] font-bold text-white';
            chip.innerHTML = `${s} <button type="button" onclick="editRemoveSize('${s}')" class="text-red-400 hover:text-red-300">&times;</button>`;
            container.appendChild(chip);
        });
    }

    function editAddColor() {
        const nameInput = document.getElementById('edit-color-name-input');
        const hexInput = document.getElementById('edit-color-hex-input');
        const imgInput = document.getElementById('edit-color-image-input');
        const name = nameInput.value.trim();
        if (!name || editColors.some(c => c.name === name)) return;
        const color = { name, hex: hexInput.value, image: imgInput.files[0] || null, imageUrl: null };
        editColors.push(color);
        nameInput.value = '';
        imgInput.value = '';
        editRenderColorChips();
        editUpdateMatrixVisibility();
        editUpdateProductStock();
    }

    function editRemoveColor(name) {
        editColors = editColors.filter(c => c.name !== name);
        Object.keys(editMatrix).forEach(key => {
            if (key.startsWith(name + '_')) delete editMatrix[key];
        });
        editRenderColorChips();
        editUpdateMatrixVisibility();
        editUpdateProductStock();
    }

    function editRenderColorChips() {
        const container = document.getElementById('edit-color-chips');
        container.innerHTML = '';
        editColors.forEach(c => {
            const chip = document.createElement('span');
            chip.className = 'inline-flex items-center gap-1.5 px-2.5 py-1 bg-elvo-bg border border-white/[0.06] rounded-lg text-[10px] font-bold text-white';
            let imgHtml = '';
            if (c.image) {
                imgHtml = `<img src="${URL.createObjectURL(c.image)}" class="w-5 h-5 rounded object-cover">`;
            } else if (c.imageUrl) {
                imgHtml = `<img src="{{ asset('uploads/') }}/${c.imageUrl}" class="w-5 h-5 rounded object-cover">`;
            }
            chip.innerHTML = `<span class="w-3 h-3 rounded-full inline-block" style="background:${c.hex}"></span> ${c.name} ${imgHtml} <button type="button" onclick="editRemoveColor('${c.name}')" class="text-red-400 hover:text-red-300">&times;</button>`;
            container.appendChild(chip);
        });
    }

    function editUpdateMatrixVisibility() {
        if (editColors.length > 0 && editSizes.length > 0) {
            editRenderMatrix();
        } else {
            const header = document.getElementById('edit-matrix-header-row');
            if (header) {
                header.colSpan = 1;
                header.textContent = editColors.length === 0 && editSizes.length === 0
                    ? 'Tambahkan warna & ukuran'
                    : editColors.length === 0 ? 'Tambahkan warna' : 'Tambahkan ukuran';
                document.getElementById('edit-matrix-body').innerHTML = '';
            }
        }
        editRenderColorList();
    }

    function editRenderMatrix() {
        const section = document.getElementById('edit-matrix-section');
        if (!section) return;
        if (editColors.length === 0 || editSizes.length === 0) {
            return editUpdateMatrixVisibility();
        }

        const headerRow = document.getElementById('edit-matrix-header-row');
        headerRow.colSpan = editSizes.length;
        headerRow.innerHTML = editSizes.map(s => `<th class="py-2 px-2 text-center border-r border-white/[0.06]">${s}</th>`).join('');

        const body = document.getElementById('edit-matrix-body');
        body.innerHTML = '';
        editColors.forEach(c => {
            const tr = document.createElement('tr');
            tr.className = 'border-t border-white/[0.06]';
            let imgHtml = '';
            if (c.image) {
                imgHtml = `<img src="${URL.createObjectURL(c.image)}" class="w-6 h-6 rounded object-cover inline-block align-middle ml-1">`;
            } else if (c.imageUrl) {
                imgHtml = `<img src="{{ asset('uploads/') }}/${c.imageUrl}" class="w-6 h-6 rounded object-cover inline-block align-middle ml-1">`;
            }
            let cellsHtml = `<td class="py-1.5 px-3 text-[10px] font-bold text-white border-r border-white/[0.06] whitespace-nowrap"><span class="w-3 h-3 rounded-full inline-block align-middle" style="background:${c.hex}"></span> ${c.name} ${imgHtml}</td>`;

            editSizes.forEach(s => {
                const key = c.name + '_' + s;
                const cell = editMatrix[key] || { enabled: false, stock: '', price: '' };
                const checked = cell.enabled ? 'checked' : '';
                const disabled = cell.enabled ? '' : 'opacity-40 pointer-events-none';
                cellsHtml += `<td class="py-1.5 px-1.5 text-center border-r border-white/[0.06] align-top">
                    <label class="flex items-center justify-center gap-1 cursor-pointer">
                        <input type="checkbox" onchange="editToggleCell('${c.name}','${s}',this)" ${checked} class="w-3 h-3 rounded bg-elvo-bg border-white/10 text-elvo-primary focus:ring-elvo-primary">
                        <span class="text-[7px] text-gray-500 uppercase font-bold">On</span>
                    </label>
                    <div class="matrix-cell-inputs ${disabled} mt-0.5 space-y-0.5">
                        <input type="number" value="${cell.stock}" onchange="editSetCellStock('${c.name}','${s}',this.value)" placeholder="Stok" class="w-full bg-elvo-bg border border-white/[0.06] rounded px-1.5 h-6 text-[9px] text-white text-right focus:border-elvo-primary outline-none" ${cell.enabled ? '' : 'disabled'}>
                        <input type="text" value="${cell.price ? Number(cell.price).toLocaleString('id-ID') : ''}" onfocus="this.value=this.value.replace(/\./g,'')" onchange="editSetCellPrice('${c.name}','${s}',this)" placeholder="Harga" class="w-full bg-elvo-bg border border-white/[0.06] rounded px-1.5 h-6 text-[9px] text-white text-right focus:border-elvo-primary outline-none" ${cell.enabled ? '' : 'disabled'}>
                    </div>
                </td>`;
            });

            tr.innerHTML = cellsHtml;
            body.appendChild(tr);
        });
        editUpdateProductStock();
    }

    function editToggleCell(color, size, cb) {
        const key = color + '_' + size;
        if (cb.checked) {
            editMatrix[key] = editMatrix[key] || { enabled: true, stock: '', price: '' };
            editMatrix[key].enabled = true;
        } else {
            if (editMatrix[key]) editMatrix[key].enabled = false;
        }
        editRenderMatrix();
        editUpdateProductStock();
    }

    function editSetCellStock(color, size, val) {
        const key = color + '_' + size;
        editMatrix[key] = editMatrix[key] || { enabled: true, stock: '', price: '' };
        editMatrix[key].stock = val;
        editUpdateProductStock();
    }

    function editSetCellPrice(color, size, input) {
        const key = color + '_' + size;
        editMatrix[key] = editMatrix[key] || { enabled: true, stock: '', price: '' };
        const raw = input.value.replace(/[^0-9]/g, '');
        editMatrix[key].price = raw;
        if (raw) input.value = Number(raw).toLocaleString('id-ID');
    }

    function editUpdateProductStock() {
        const hint = document.getElementById('edit-variant-hint');
        const usesSize = hint && hint.textContent.includes('Ukuran tersedia');
        const stockInput = document.getElementById('edit-product-stock');
        if (!stockInput) return;
        let total = 0;
        if (usesSize) {
            editColors.forEach(c => {
                editSizes.forEach(s => {
                    const key = c.name + '_' + s;
                    const cell = editMatrix[key];
                    if (cell && cell.enabled && cell.stock) total += Number(cell.stock);
                });
            });
        } else {
            editColors.forEach(c => {
                const key = c.name + '_';
                const cell = editMatrix[key] || { enabled: true, stock: '', price: '' };
                if (cell.stock) total += Number(cell.stock);
            });
        }
        stockInput.value = total;
    }

    function editRenderColorList() {
        const body = document.getElementById('edit-color-list-body');
        if (!body) return;
        body.innerHTML = '';
        if (editColors.length === 0) {
            body.innerHTML = '<tr><td colspan="5" class="py-6 text-center text-[9px] text-gray-600 italic">Belum ada warna.</td></tr>';
            return;
        }
        editColors.forEach(c => {
            const tr = document.createElement('tr');
            tr.className = 'border-t border-white/[0.06]';
            const key = c.name + '_';
            const cell = editMatrix[key] || { enabled: true, stock: '', price: '' };
            let imgHtml = '';
            if (c.image) {
                imgHtml = `<img src="${URL.createObjectURL(c.image)}" class="w-6 h-6 rounded object-cover inline-block">`;
            } else if (c.imageUrl) {
                imgHtml = `<img src="{{ asset('uploads/') }}/${c.imageUrl}" class="w-6 h-6 rounded object-cover inline-block">`;
            }
            tr.innerHTML = `<td class="py-2 px-3 text-[10px] font-bold text-white"><span class="w-3 h-3 rounded-full inline-block align-middle" style="background:${c.hex}"></span> ${c.name}</td>
                <td class="py-2 px-2 text-center"><input type="number" value="${cell.stock}" onchange="editSetCellStock('${c.name}','',this.value)" placeholder="Stok" class="w-16 bg-elvo-bg border border-white/[0.06] rounded px-2 h-7 text-[10px] text-white text-right focus:border-elvo-primary outline-none"></td>
                <td class="py-2 px-2 text-center"><input type="text" value="${cell.price ? Number(cell.price).toLocaleString('id-ID') : ''}" onfocus="this.value=this.value.replace(/\./g,'')" onchange="editSetCellPrice('${c.name}','',this)" placeholder="Harga" class="w-24 bg-elvo-bg border border-white/[0.06] rounded px-2 h-7 text-[10px] text-white text-right focus:border-elvo-primary outline-none"></td>
                <td class="py-2 px-2 text-center">${imgHtml ? imgHtml : '<span class="text-[8px] text-gray-500">none</span>'}</td>
                <td class="py-2 px-2 text-center"><button type="button" onclick="editRemoveColor('${c.name}')" class="text-red-400 hover:text-red-300 text-xs">&times;</button></td>`;
                body.appendChild(tr);
        });
        editUpdateProductStock();
    }

    function editToggle(selector, show) {
        const el = document.querySelector(selector);
        if (el) el.classList.toggle('hidden', !show);
    }

    function editUpdateVariantHint(parentName, usesSize) {
        const hint = document.getElementById('edit-variant-hint');
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

    function confirmDelete() {
        if (confirm('APAKAH ANDA YAKIN? Data produk "{{ $product->name }}" akan dihapus permanen dari sistem.')) {
            document.getElementById('delete-form').submit();
        }
    }

    function previewImage(input) {
        const preview = document.getElementById('currentImage');
        if (input.files && input.files[0]) {
            const reader = new FileReader();
            reader.onload = function(e) {
                if (preview) { preview.src = e.target.result; }
            }
            reader.readAsDataURL(input.files[0]);
        }
    }

    function generateSlug(text) {
        const slug = text.toLowerCase().replace(/[^\w ]+/g, '').replace(/ +/g, '-');
        document.getElementById('productSlug').value = slug;
    }

    function deleteImage(id, btn) {
        if (!confirm('Hapus foto ini?')) return;
        fetch('/admin/products/images/' + id, {
            method: 'DELETE',
            headers: { 'X-CSRF-TOKEN': '{{ csrf_token() }}', 'Accept': 'application/json' }
        }).then(r => r.json()).then(d => { if (d.success) btn.closest('.relative').remove(); });
    }

    function setPrimary(id, btn) {
        fetch('/admin/products/images/' + id + '/primary', {
            method: 'POST',
            headers: { 'X-CSRF-TOKEN': '{{ csrf_token() }}', 'Accept': 'application/json' }
        }).then(r => r.json()).then(d => { if (d.success) location.reload(); });
    }

    function previewNewGallery(input) {
        const container = document.getElementById('newGalleryPreview');
        for (const file of input.files) {
            const reader = new FileReader();
            reader.onload = function(e) {
                const d = document.createElement('div');
                d.className = 'w-[72px] h-[72px] rounded-xl overflow-hidden border border-white/10';
                d.innerHTML = '<img src="' + e.target.result + '" class="w-full h-full object-cover">';
                container.appendChild(d);
            }
            reader.readAsDataURL(file);
        }
    }

    document.querySelector('form[action*="products/update"]').addEventListener('submit', function(e) {
        const hint = document.getElementById('edit-variant-hint');
        const usesSize = hint && hint.textContent.includes('Ukuran tersedia');
        const existing = this.querySelector('input[name^="variants"]');
        if (existing) return;

        let idx = 0;
        const flatContainer = document.createElement('div');
        flatContainer.style.display = 'none';

        if (usesSize) {
            editColors.forEach(c => {
                editSizes.forEach(s => {
                    const key = c.name + '_' + s;
                    const cell = editMatrix[key];
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
            editColors.forEach(c => {
                const key = c.name + '_';
                const cell = editMatrix[key] || { enabled: true, stock: '', price: '' };
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
        const stockVal = document.getElementById('edit-product-stock')?.value || '0';
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
    input::-webkit-outer-spin-button,
    input::-webkit-inner-spin-button { -webkit-appearance: none; margin: 0; }
    .variant-table td { vertical-align: middle; }
</style>
@endsection