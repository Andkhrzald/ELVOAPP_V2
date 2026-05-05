@extends('layouts.app')

@section('content')
{{-- Layout Container: Mengikuti struktur padding sidebar --}}
<div class="min-h-screen bg-[#0f0f0f] text-gray-300 pb-12">
    
    {{-- Header Section --}}
    <div class="max-w-6xl mx-auto flex items-end justify-between mb-8 px-4 lg:px-0 pt-10">
        <div>
            <h1 class="text-3xl font-bold text-white tracking-tight">Edit Produk</h1>
            <p class="text-[11px] font-semibold text-blue-500 uppercase tracking-[0.2em] mt-1">Management / Inventory / Update Product</p>
        </div>
        <a href="{{ route('admin.products') }}" class="group flex items-center gap-2 text-xs font-bold text-gray-500 hover:text-white transition-all">
            <svg class="w-4 h-4 transition-transform group-hover:-translate-x-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path d="M15 19l-7-7 7-7" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"/>
            </svg>
            KEMBALI
        </a>
    </div>

    <div class="max-w-6xl mx-auto px-4 lg:px-0">
        <form action="{{ route('admin.products.update', $product->id) }}" method="POST" enctype="multipart/form-data" class="grid grid-cols-12 gap-8">
            @csrf
            @method('PUT')

            {{-- Kolom Kiri: Form Detail --}}
            <div class="col-span-12 lg:col-span-8 space-y-6">
                {{-- Main Info Card --}}
                <div class="bg-[#1a1a1a] p-8 rounded-2xl border border-white/5 shadow-2xl">
                    <div class="space-y-8">
                        {{-- Nama Produk --}}
                        <div>
                            <label class="block text-[10px] font-bold text-gray-500 uppercase tracking-widest mb-3">Nama Produk</label>
                            <input type="text" name="name" value="{{ old('name', $product->name) }}" 
                                class="w-full bg-transparent px-0 py-2 text-2xl font-bold text-white border-b-2 border-white/10 focus:border-blue-500 outline-none transition-all placeholder-white/10">
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-10">
                            {{-- Kategori --}}
                            <div>
                                <label class="block text-[10px] font-bold text-gray-500 uppercase tracking-widest mb-3">Kategori</label>
                                <div class="relative">
                                    <select name="category_id" class="w-full bg-[#121212] py-3 px-4 rounded-xl text-sm font-medium border border-white/5 focus:border-blue-500 outline-none cursor-pointer appearance-none">
                                        @foreach($categories as $cat)
                                            <option value="{{ $cat->id }}" {{ $product->category_id == $cat->id ? 'selected' : '' }} class="bg-[#1a1a1a]">
                                                {{ $cat->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                    <div class="absolute inset-y-0 right-4 flex items-center pointer-events-none text-gray-500">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M19 9l-7 7-7-7" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/></svg>
                                    </div>
                                </div>
                            </div>
                            {{-- Berat --}}
                            <div>
                                <label class="block text-[10px] font-bold text-gray-500 uppercase tracking-widest mb-3">Berat (Gram)</label>
                                <input type="number" name="weight" value="{{ old('weight', $product->weight) }}" 
                                    class="w-full bg-[#121212] py-3 px-4 rounded-xl text-sm font-medium border border-white/5 focus:border-blue-500 outline-none text-white">
                            </div>
                        </div>

                        {{-- Deskripsi --}}
                        <div>
                            <label class="block text-[10px] font-bold text-gray-500 uppercase tracking-widest mb-3">Deskripsi Produk</label>
                            <textarea name="description" rows="5" 
                                class="w-full bg-[#121212] px-5 py-4 text-sm text-gray-300 rounded-2xl border border-white/5 focus:border-blue-500 outline-none resize-none transition-all leading-relaxed">{{ old('description', $product->description) }}</textarea>
                        </div>
                    </div>
                </div>

                {{-- Media Card --}}
                <div class="bg-[#1a1a1a] p-8 rounded-2xl border border-white/5 shadow-2xl">
                    <label class="block text-[10px] font-bold text-gray-500 uppercase tracking-widest mb-5">Media Produk</label>
                    <div class="grid grid-cols-1 md:flex items-center gap-8">
                        @if($product->image)
                            <div class="relative w-40 h-40 rounded-2xl overflow-hidden border-4 border-[#121212] shadow-xl group">
                                <img src="{{ asset('storage/' . $product->image) }}" class="w-full h-full object-cover transition-transform group-hover:scale-110">
                                <div class="absolute inset-0 bg-black/40 flex items-center justify-center opacity-0 group-hover:opacity-100 transition-all">
                                    <span class="text-[10px] font-bold text-white uppercase">Preview</span>
                                </div>
                            </div>
                        @endif
                        
                        <label class="flex-1 flex flex-col items-center justify-center h-40 border-2 border-dashed border-white/5 rounded-2xl cursor-pointer hover:bg-white/[0.02] hover:border-blue-500/50 transition-all group">
                            <div class="bg-blue-500/10 p-3 rounded-full mb-3 group-hover:scale-110 transition-transform">
                                <svg class="w-6 h-6 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M12 4v16m8-8H4" stroke-width="2.5" stroke-linecap="round"/></svg>
                            </div>
                            <span class="text-[10px] font-black text-gray-500 uppercase tracking-widest">Ganti Gambar</span>
                            <p class="text-[9px] text-gray-600 mt-1 uppercase">PNG, JPG up to 2MB</p>
                            <input type="file" name="image" class="hidden" />
                        </label>
                    </div>
                </div>
            </div>

            {{-- Kolom Kanan: Pricing & Action --}}
            <div class="col-span-12 lg:col-span-4 space-y-6">
                {{-- Pricing Card --}}
                <div class="bg-blue-600 p-8 rounded-3xl shadow-xl shadow-blue-900/20 relative overflow-hidden">
                    {{-- Decorative SVG --}}
                    <svg class="absolute top-0 right-0 opacity-10 w-32 h-32 -mr-10 -mt-10" fill="currentColor" viewBox="0 0 24 24"><path d="M12 8c-1.657 0-3 1.343-3 3s1.343 3 3 3 3-1.343 3-3-1.343-3-3-3zm0 10c-4.418 0-8-3.582-8-8s3.582-8 8-8 8 3.582 8 8-3.582 8-8 8z"/></svg>
                    
                    <div class="relative z-10">
                        <label class="block text-[10px] font-black text-blue-200 uppercase tracking-[0.2em] mb-4">Harga Jual (IDR)</label>
                        <div class="flex items-center gap-3 text-white">
                            <span class="text-2xl font-medium opacity-70">Rp</span>
                            <input type="number" name="price" value="{{ old('price', $product->price) }}" 
                                class="bg-transparent w-full text-5xl font-black outline-none border-none p-0 focus:ring-0 placeholder-blue-300">
                        </div>
                    </div>
                </div>

                {{-- Inventory Card --}}
                <div class="bg-[#1a1a1a] p-8 rounded-2xl border border-white/5 shadow-2xl">
                    <div class="mb-8">
                        <label class="block text-[10px] font-bold text-gray-500 uppercase tracking-widest mb-4">Stok Gudang</label>
                        <div class="flex items-center gap-4 bg-[#121212] p-4 rounded-xl border border-white/5 focus-within:border-blue-500 transition-all">
                            <svg class="w-5 h-5 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/></svg>
                            <input type="number" name="stock" value="{{ old('stock', $product->stock) }}" 
                                class="bg-transparent w-full text-xl font-bold text-white outline-none border-none p-0 focus:ring-0">
                        </div>
                    </div>

                    <div class="space-y-4">
                        <button type="submit" class="w-full py-4 bg-white text-black text-xs font-black uppercase tracking-widest rounded-xl hover:bg-blue-500 hover:text-white transition-all shadow-xl shadow-black/20">
                            UPDATE PRODUK
                        </button>
                        <button type="reset" class="w-full py-2 text-[10px] font-bold text-gray-600 uppercase tracking-widest hover:text-red-500 transition-colors">
                            Reset Input
                        </button>
                    </div>
                </div>

                {{-- Product Status --}}
                <div class="p-6 bg-[#1a1a1a] rounded-2xl border border-white/5 flex items-center justify-between">
                    <div>
                        <h4 class="text-[10px] font-black text-gray-500 uppercase">Status</h4>
                        <span class="text-xs font-bold text-green-500 flex items-center gap-2 mt-1">
                            <span class="w-2 h-2 rounded-full bg-green-500 animate-pulse"></span>
                            Live In Catalog
                        </span>
                    </div>
                    <div class="bg-green-500/10 p-2 rounded-lg">
                        <svg class="w-5 h-5 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/></svg>
                    </div>
                </div>
            </div>
        </form>
    </div>
</div>

<style>
    /* Menghilangkan spin button di input number */
    input::-webkit-outer-spin-button,
    input::-webkit-inner-spin-button {
        -webkit-appearance: none;
        margin: 0;
    }
</style>
@endsection