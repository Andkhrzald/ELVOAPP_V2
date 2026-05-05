@extends('layouts.app')

@section('content')
<div class="mb-5 flex justify-between items-center">
    <h1 class="text-2xl font-bold text-white">Daftar Produk Elvo</h1>
    
    <button onclick="toggleModal('modal-tambah')" class="bg-[#1a1a1a] border border-white/10 text-gray-300 hover:bg-[#252525] px-4 py-2 rounded-lg text-sm transition shadow-sm flex items-center gap-2 font-medium">
        <svg class="w-4 h-4 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
        </svg>
        Tambah Produk
    </button>
</div>

@if(session('success'))
    <div class="bg-green-500/10 border-l-4 border-green-500 text-green-400 p-4 mb-4 rounded shadow-sm flex justify-between items-center">
        <p class="text-sm font-medium">{{ session('success') }}</p>
        <button onclick="this.parentElement.remove()" class="font-bold">&times;</button>
    </div>
@endif

<div class="relative overflow-x-auto bg-[#1a1a1a] shadow-sm rounded-xl border border-white/5">
    <table class="w-full text-sm text-left text-gray-400">
        <thead class="text-xs text-gray-500 uppercase bg-[#252525]/50 border-b border-white/5">
            <tr>
                <th scope="col" class="p-4">
                    <div class="flex items-center">
                        <input id="table-checkbox" type="checkbox" class="w-4 h-4 text-blue-600 bg-[#121212] border-white/10 rounded focus:ring-blue-500">
                    </div>
                </th>
                <th scope="col" class="px-6 py-3 font-semibold">Nama Produk</th>
                <th scope="col" class="px-6 py-3 font-semibold">Warna</th>
                <th scope="col" class="px-6 py-3 font-semibold">Kategori</th>
                <th scope="col" class="px-6 py-3 font-semibold">Stok</th>
                <th scope="col" class="px-6 py-3 font-semibold">Harga</th>
                <th scope="col" class="px-6 py-3 font-semibold">Berat</th>
                <th scope="col" class="px-6 py-3 font-semibold text-center">Aksi</th>
            </tr>
        </thead>
        <tbody class="divide-y divide-white/5">
        @forelse($products as $p)
        <tr class="hover:bg-white/5 transition">
            <td class="w-4 p-4">
                <div class="flex items-center">
                    <input id="table-checkbox-{{ $p->id }}" type="checkbox" class="w-4 h-4 text-blue-600 bg-[#121212] border-white/10 rounded focus:ring-blue-500">
                </div>
            </td>
            <th scope="row" class="px-6 py-4 font-medium text-white whitespace-nowrap">
                {{ $p->name }}
            </th>
            <td class="px-6 py-4">{{ $p->color ?? '-' }}</td>
            <td class="px-6 py-4">
                <span class="px-2 py-1 bg-blue-500/10 text-blue-400 rounded-md text-xs">
                    {{ $p->category->name ?? 'Uncategorized' }}
                </span>
            </td>
            <td class="px-6 py-4 font-bold {{ $p->stock > 0 ? 'text-gray-200' : 'text-red-500' }}">
                    {{ $p->stock > 0 ? $p->stock : 'Habis' }}
            </td>
            <td class="px-6 py-4 font-semibold text-white">Rp {{ number_format($p->price) }}</td>
            <td class="px-6 py-4">{{ $p->weight ?? '-' }}g</td>
            <td class="px-6 py-4">
                <div class="flex justify-center gap-4">
                    <a href="{{ route('admin.products.edit', $p->id) }}" class="font-medium text-blue-400 hover:text-blue-300 transition">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                    </a>

                    <form action="{{ route('admin.products.destroy', $p->id) }}" method="POST" 
                        onsubmit="return confirm('Yakin ingin menghapus produk {{ $p->name }}?')">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="font-medium text-red-400 hover:text-red-300 transition">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                        </button>
                    </form>
                </div>
            </td>
        </tr>
        @empty
        <tr>
            <td colspan="8" class="text-center py-10 text-gray-500 italic text-base">Belum ada produk.</td>
        </tr>
        @endforelse
    </tbody>
    </table>
</div>

{{-- --- MODAL TAMBAH PRODUK --- --}}
<div id="modal-tambah" class="fixed inset-0 z-[60] hidden items-center justify-center bg-black/60 backdrop-blur-sm">
    <div class="relative p-6 w-full max-w-2xl max-h-[90vh] overflow-y-auto bg-[#1a1a1a] rounded-2xl shadow-2xl border border-white/10 animate-slide-in">
        <div class="flex items-center justify-between mb-5 border-b border-white/5 pb-3">
            <h3 class="text-xl font-bold text-white">Tambah Produk Baru</h3>
            <button onclick="toggleModal('modal-tambah')" class="text-gray-400 hover:text-white transition text-2xl">&times;</button>
        </div>

        <form action="{{ route('admin.products.store') }}" method="POST" enctype="multipart/form-data">
            @csrf
            <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                <div class="md:col-span-2">
                    <label class="block mb-2 text-sm font-medium text-gray-300">Nama Produk</label>
                    <input type="text" name="name" class="w-full p-2.5 bg-[#121212] border border-white/10 rounded-lg focus:ring-blue-500 focus:border-blue-500 text-white placeholder-gray-600" placeholder="Contoh: Hoodie Elvo Blue" required>
                </div>

                <div>
                    <label class="block mb-2 text-sm font-medium text-gray-300">Kategori</label>
                    <select name="category_id" class="w-full p-2.5 bg-[#121212] border border-white/10 rounded-lg text-white focus:ring-blue-500" required>
                        <option value="" class="bg-[#1a1a1a]">Pilih Kategori</option>
                        <option value="1" class="bg-[#1a1a1a]">Hoodie</option>
                        <option value="2" class="bg-[#1a1a1a]">Aksesoris</option>
                        <option value="3" class="bg-[#1a1a1a]">T-Shirt</option>
                    </select>
                </div>

                <div>
                    <label class="block mb-2 text-sm font-medium text-gray-300">Warna</label>
                    <input type="text" name="color" class="w-full p-2.5 bg-[#121212] border border-white/10 rounded-lg text-white placeholder-gray-600" placeholder="Biru / Hitam">
                </div>

                <div>
                    <label class="block mb-2 text-sm font-medium text-gray-300">Stok Awal</label>
                    <input type="number" name="stock" class="w-full p-2.5 bg-[#121212] border border-white/10 rounded-lg text-white" required>
                </div>

                <div>
                    <label class="block mb-2 text-sm font-medium text-gray-300">Berat (Gram)</label>
                    <input type="number" name="weight" class="w-full p-2.5 bg-[#121212] border border-white/10 rounded-lg text-white placeholder-gray-600" placeholder="500" required>
                </div>

                <div class="md:col-span-2">
                    <label class="block mb-2 text-sm font-medium text-gray-300">Deskripsi Produk</label>
                    <textarea name="description" rows="3" class="w-full p-2.5 bg-[#121212] border border-white/10 rounded-lg focus:ring-blue-500 text-white placeholder-gray-600" placeholder="Detail bahan, ukuran, dll..." required></textarea>
                </div>

                <div class="md:col-span-2">
                    <label class="block mb-2 text-sm font-medium text-gray-300">Harga (Rp)</label>
                    <input type="number" name="price" class="w-full p-2.5 bg-[#121212] border border-white/10 rounded-lg text-white placeholder-gray-600" placeholder="150000" required>
                </div>

                <div class="md:col-span-2">
                    <label class="block mb-2 text-sm font-medium text-gray-300">Foto Produk</label>
                    <div class="mt-1 flex justify-center px-6 pt-5 pb-6 border-2 border-white/5 border-dashed rounded-xl bg-[#121212]">
                        <div class="space-y-1 text-center">
                            <svg class="mx-auto h-12 w-12 text-gray-500" stroke="currentColor" fill="none" viewBox="0 0 48 48">
                                <path d="M28 8H12a4 4 0 00-4 4v20m32-12v8m0 0v8a4 4 0 01-4 4H12a4 4 0 01-4-4v-4m32-4l-3.172-3.172a4 4 0 00-5.656 0L28 28M8 32l9.172-9.172a4 4 0 015.656 0L28 28m0 0l4 4m4-24h8m-4-4v8m-12 4h.02" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
                            </svg>
                            <div class="flex text-sm text-gray-400">
                                <label class="relative cursor-pointer bg-transparent rounded-md font-medium text-blue-500 hover:text-blue-400 focus-within:outline-none">
                                    <span>Upload a file</span>
                                    <input type="file" name="image" class="sr-only">
                                </label>
                            </div>
                            <p class="text-xs text-gray-500">PNG, JPG up to 2MB</p>
                        </div>
                    </div>
                </div>
            </div>

            <div class="flex justify-end gap-3 mt-6 pt-4 border-t border-white/5">
                <button type="button" onclick="toggleModal('modal-tambah')" class="px-5 py-2.5 text-sm font-medium text-gray-400 bg-transparent rounded-lg border border-white/10 hover:bg-white/5 transition">Batal</button>
                <button type="submit" class="px-5 py-2.5 text-sm font-medium text-white bg-blue-600 rounded-lg hover:bg-blue-500 shadow-lg shadow-blue-900/20 transition">Simpan Produk</button>
            </div>
        </form>
    </div>
</div>

<script>
    function toggleModal(modalId) {
        const modal = document.getElementById(modalId);
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
</script>

<style>
    .animate-slide-in {
        animation: slideIn 0.25s ease-out;
    }
    @keyframes slideIn {
        from { transform: scale(0.95); opacity: 0; }
        to { transform: scale(1); opacity: 1; }
    }
</style>
@endsection