@extends('layouts.app')

@section('content')
<div class="min-h-screen bg-[#0f0f0f] text-gray-300 pb-12">
    
    {{-- Header Section --}}
    <div class="max-w-6xl mx-auto flex items-end justify-between mb-10 px-4 lg:px-0 pt-6">
        <div>
            <p class="text-[10px] font-black text-blue-500 uppercase tracking-[0.3em] mb-2">Management / Inventory / Edit</p>
            <h1 class="text-4xl font-black text-white tracking-tighter">Edit Data Produk</h1>
        </div>
        <a href="{{ route('admin.products') }}" class="group flex items-center gap-3 text-[10px] font-black text-gray-500 hover:text-white transition-all uppercase tracking-widest bg-white/5 px-4 py-2 rounded-xl border border-white/5">
            <svg class="w-4 h-4 transition-transform group-hover:-translate-x-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path d="M15 19l-7-7 7-7" stroke-width="3" stroke-linecap="round" stroke-linejoin="round"/>
            </svg>
            Kembali Ke Katalog
        </a>
    </div>

    <div class="max-w-6xl mx-auto px-4 lg:px-0">
        <form action="{{ route('admin.products.update', $product->id) }}" method="POST" enctype="multipart/form-data" class="grid grid-cols-12 gap-8">
            @csrf
            @method('PUT')

            {{-- Kolom Kiri: Form Detail --}}
            <div class="col-span-12 lg:col-span-8 space-y-8">
                {{-- Main Info Card --}}
                <div class="bg-[#1a1a1a] p-10 rounded-[2.5rem] border border-white/5 shadow-2xl relative overflow-hidden">
                    <div class="absolute top-0 right-0 p-8 opacity-5">
                        <svg class="w-32 h-32" fill="currentColor" viewBox="0 0 24 24"><path d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                    </div>

                    <div class="space-y-10 relative z-10">
                        {{-- Nama Produk --}}
                        <div>
                            <label class="block text-[10px] font-black text-gray-500 uppercase tracking-[0.2em] mb-4">Identification / Nama Produk</label>
                            <input type="text" name="name" value="{{ old('name', $product->name) }}" id="productName" onkeyup="generateSlug(this.value)"
                                class="w-full bg-transparent px-0 py-2 text-3xl font-black text-white border-b-2 border-white/10 focus:border-blue-500 outline-none transition-all placeholder-white/10" required>
                        </div>

                        <div class="bg-[#121212] p-4 rounded-2xl border border-white/5 flex items-center gap-2">
                            <span class="text-[10px] font-black text-gray-600 uppercase tracking-widest">Slug URL:</span>
                            <span class="text-[10px] font-bold text-gray-500 font-mono">elvo.com/shop/</span>
                            <input type="text" name="slug" id="productSlug" value="{{ old('slug', $product->slug) }}" class="bg-transparent border-none p-0 focus:ring-0 text-[10px] font-black text-blue-500 font-mono w-full" readonly>
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                            {{-- Kategori --}}
                            <div class="md:col-span-1">
                                <label class="block text-[10px] font-black text-gray-500 uppercase tracking-[0.2em] mb-4">Classification / Kategori</label>
                                <div class="relative group">
                                    <select name="category_id" class="w-full bg-[#121212] py-4 px-6 rounded-2xl text-sm font-bold border border-white/5 focus:border-blue-500 outline-none cursor-pointer appearance-none transition-all">
                                        @foreach($categories as $cat)
                                            <option value="{{ $cat->id }}" {{ $product->category_id == $cat->id ? 'selected' : '' }} class="bg-[#1a1a1a]">
                                                {{ $cat->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                    <div class="absolute inset-y-0 right-6 flex items-center pointer-events-none text-gray-500 group-hover:text-blue-500 transition-colors">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M19 9l-7 7-7-7" stroke-width="3" stroke-linecap="round" stroke-linejoin="round"/></svg>
                                    </div>
                                </div>
                            </div>
                            {{-- Warna --}}
                            <div class="md:col-span-1">
                                <label class="block text-[10px] font-black text-gray-500 uppercase tracking-[0.2em] mb-4">Aesthetics / Warna</label>
                                <input type="text" name="color" value="{{ old('color', $product->color) }}" 
                                    class="w-full bg-[#121212] py-4 px-6 rounded-2xl text-sm font-bold border border-white/5 focus:border-blue-500 outline-none text-white transition-all" placeholder="Contoh: Onyx Black">
                            </div>
                            {{-- Berat --}}
                            <div class="md:col-span-1">
                                <label class="block text-[10px] font-black text-gray-500 uppercase tracking-[0.2em] mb-4">Weight / Berat (Gram)</label>
                                <div class="relative">
                                    <input type="number" name="weight" value="{{ old('weight', $product->weight) }}" 
                                        class="w-full bg-[#121212] py-4 px-6 rounded-2xl text-sm font-bold border border-white/5 focus:border-blue-500 outline-none text-white transition-all" required>
                                    <span class="absolute right-6 top-1/2 -translate-y-1/2 text-[10px] font-black text-gray-600">GR</span>
                                </div>
                            </div>
                        </div>

                        {{-- Deskripsi --}}
                        <div>
                            <label class="block text-[10px] font-black text-gray-500 uppercase tracking-[0.2em] mb-4">Manifesto / Deskripsi Produk</label>
                            <textarea name="description" rows="6" 
                                class="w-full bg-[#121212] px-8 py-6 text-sm text-gray-300 rounded-[2rem] border border-white/5 focus:border-blue-500 outline-none resize-none transition-all leading-relaxed shadow-inner" required>{{ old('description', $product->description) }}</textarea>
                        </div>
                    </div>
                </div>

                {{-- Media Card --}}
                <div class="bg-[#1a1a1a] p-10 rounded-[2.5rem] border border-white/5 shadow-2xl">
                    <label class="block text-[10px] font-black text-gray-500 uppercase tracking-[0.2em] mb-8 text-center md:text-left">Visual Assets / Media Produk</label>
                    <div class="grid grid-cols-1 md:flex items-center gap-10">
                        @if($product->image)
                            <div class="relative w-48 h-48 rounded-[2rem] overflow-hidden border-4 border-[#121212] shadow-2xl group flex-shrink-0 mx-auto md:mx-0">
                                <img id="currentImage" src="{{ asset('uploads/' . $product->image) }}" class="w-full h-full object-cover transition-transform duration-500 group-hover:scale-110">
                                <div class="absolute inset-0 bg-black/60 flex items-center justify-center opacity-0 group-hover:opacity-100 transition-all">
                                    <span class="text-[10px] font-black text-white uppercase tracking-widest border border-white/20 px-3 py-1 rounded-full">Current Image</span>
                                </div>
                            </div>
                        @endif
                        
                        <label class="flex-1 flex flex-col items-center justify-center h-48 border-2 border-dashed border-white/5 rounded-[2rem] cursor-pointer hover:bg-blue-500/[0.03] hover:border-blue-500/50 transition-all group">
                            <div class="bg-blue-500/10 p-4 rounded-full mb-4 group-hover:scale-110 transition-transform">
                                <svg class="w-8 h-8 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M12 4v16m8-8H4" stroke-width="3" stroke-linecap="round"/></svg>
                            </div>
                            <span class="text-[10px] font-black text-gray-400 uppercase tracking-widest group-hover:text-white">Ganti Gambar Produk</span>
                            <p class="text-[9px] text-gray-600 mt-2 uppercase font-bold">Recommended: 1000x1000px, PNG/JPG</p>
                            <input type="file" name="image" class="hidden" onchange="previewImage(this)" />
                        </label>
                    </div>
                </div>
            </div>

            {{-- Kolom Kanan: Pricing & Action --}}
            <div class="col-span-12 lg:col-span-4 space-y-8">
                {{-- Pricing Card --}}
                <div class="bg-blue-600 p-10 rounded-[2.5rem] shadow-2xl shadow-blue-900/30 relative overflow-hidden group">
                    {{-- Decorative SVG --}}
                    <svg class="absolute top-0 right-0 opacity-10 w-40 h-40 -mr-12 -mt-12 group-hover:scale-110 transition-transform duration-700" fill="currentColor" viewBox="0 0 24 24"><path d="M12 8c-1.657 0-3 1.343-3 3s1.343 3 3 3 3-1.343 3-3-1.343-3-3-3zm0 10c-4.418 0-8-3.582-8-8s3.582-8 8-8 8 3.582 8 8-3.582 8-8 8z"/></svg>
                    
                    <div class="relative z-10">
                        <label class="block text-[10px] font-black text-blue-100 uppercase tracking-[0.2em] mb-6">Valuation / Harga Jual</label>
                        <div class="flex items-center gap-4 text-white">
                            <span class="text-3xl font-black opacity-40">Rp</span>
                            <input type="number" name="price" value="{{ old('price', $product->price) }}" 
                                class="bg-transparent w-full text-6xl font-black outline-none border-none p-0 focus:ring-0 placeholder-blue-300 tracking-tighter" required>
                        </div>
                        <p class="text-[9px] text-blue-200 mt-4 uppercase font-bold tracking-widest">Base price in Indonesian Rupiah</p>
                    </div>
                </div>

                {{-- Inventory Card --}}
                <div class="bg-[#1a1a1a] p-10 rounded-[2.5rem] border border-white/5 shadow-2xl">
                    <div class="mb-10">
                        <label class="block text-[10px] font-black text-gray-500 uppercase tracking-[0.2em] mb-5">Warehouse / Stok Produk</label>
                        <div class="flex items-center gap-5 bg-[#121212] p-6 rounded-2xl border border-white/5 focus-within:border-blue-500 transition-all shadow-inner">
                            <svg class="w-6 h-6 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"/></svg>
                            <input type="number" name="stock" value="{{ old('stock', $product->stock) }}" 
                                class="bg-transparent w-full text-3xl font-black text-white outline-none border-none p-0 focus:ring-0" required>
                        </div>
                    </div>

                    <div class="space-y-4">
                        <button type="submit" class="w-full py-5 bg-white text-black text-[10px] font-black uppercase tracking-[0.3em] rounded-2xl hover:bg-blue-600 hover:text-white transition-all shadow-xl shadow-black/40">
                            Simpan Perubahan
                        </button>
                        <button type="reset" class="w-full py-2 text-[9px] font-black text-gray-600 uppercase tracking-[0.2em] hover:text-red-500 transition-colors">
                            Revert Changes
                        </button>
                    </div>
                </div>

                {{-- Visibility Status --}}
                <div class="bg-[#1a1a1a] p-8 rounded-[2rem] border border-white/5 shadow-2xl">
                    <div class="flex items-center justify-between mb-6">
                        <div>
                            <h4 class="text-[10px] font-black text-gray-500 uppercase tracking-[0.2em]">Live Visibility</h4>
                            <p class="text-[9px] text-gray-600 mt-1 uppercase">Tampilkan di etalase</p>
                        </div>
                        <label class="relative inline-flex items-center cursor-pointer">
                            <input type="hidden" name="is_active" value="0">
                            <input type="checkbox" name="is_active" value="1" {{ $product->is_active ? 'checked' : '' }} class="sr-only peer">
                            <div class="w-14 h-8 bg-gray-700 peer-focus:outline-none rounded-full peer peer-checked:after:translate-x-full rtl:peer-checked:after:-translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[4px] after:start-[4px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-6 after:w-6 after:transition-all peer-checked:bg-blue-600"></div>
                        </label>
                    </div>

                    <div class="flex items-center gap-4 p-4 rounded-xl {{ $product->is_active ? 'bg-green-500/5 border border-green-500/20' : 'bg-yellow-500/5 border border-yellow-500/20' }}">
                        <div class="w-2 h-2 rounded-full {{ $product->is_active ? 'bg-green-500 animate-pulse' : 'bg-yellow-500' }}"></div>
                        <span class="text-[10px] font-black uppercase tracking-widest {{ $product->is_active ? 'text-green-500' : 'text-yellow-500' }}">
                            {{ $product->is_active ? 'Produk Aktif & Live' : 'Produk Disembunyikan' }}
                        </span>
                    </div>
                </div>

                {{-- Danger Zone --}}
                <div class="p-6 border border-red-500/20 rounded-[2rem] bg-red-500/5">
                    <h5 class="text-[9px] font-black text-red-500 uppercase tracking-widest mb-2">Danger Zone</h5>
                    <p class="text-[9px] text-red-500/60 mb-4 uppercase leading-relaxed font-bold">Tindakan ini tidak dapat dibatalkan. Menghapus produk akan menghilangkan semua riwayat review terkait.</p>
                    <button type="button" onclick="confirmDelete()" class="text-[9px] font-black text-red-500 hover:text-white hover:bg-red-500 px-4 py-2 rounded-lg border border-red-500/30 transition-all uppercase tracking-tighter">
                        Delete Product Forever
                    </button>
                </div>
            </div>
        </form>
    </div>
</div>

{{-- Hidden Form for Delete --}}
<form id="delete-form" action="{{ route('admin.products.destroy', $product->id) }}" method="POST" class="hidden">
    @csrf
    @method('DELETE')
</form>

<script>
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
                if (preview) {
                    preview.src = e.target.result;
                }
            }
            reader.readAsDataURL(input.files[0]);
        }
    }

    function generateSlug(text) {
        const slug = text.toLowerCase()
            .replace(/[^\w ]+/g, '')
            .replace(/ +/g, '-');
        document.getElementById('productSlug').value = slug;
    }
</script>

<style>
    /* Menghilangkan spin button di input number */
    input::-webkit-outer-spin-button,
    input::-webkit-inner-spin-button {
        -webkit-appearance: none;
        margin: 0;
    }
</style>
@endsection