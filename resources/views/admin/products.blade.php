@extends('layouts.app')

@section('content')
<div class="mb-5 flex justify-between items-center">
    <h1 class="text-2xl font-bold text-gray-800">Daftar Produk Elvo</h1>
    
    {{-- Update: Tambahkan onclick untuk buka modal --}}
    <button onclick="toggleModal('modal-tambah')" class="bg-white border border-gray-200 text-gray-700 hover:bg-gray-50 px-4 py-2 rounded-lg text-sm transition shadow-sm flex items-center gap-2 font-medium">
        <svg class="w-4 h-4 text-elvo" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
        </svg>
        Tambah Produk
    </button>
</div>

{{-- Tambahan: Notifikasi Sukses --}}
@if(session('success'))
    <div class="bg-green-100 border-l-4 border-green-500 text-green-700 p-4 mb-4 rounded shadow-sm flex justify-between items-center">
        <p>{{ session('success') }}</p>
        <button onclick="this.parentElement.remove()" class="font-bold">&times;</button>
    </div>
@endif

<div class="relative overflow-x-auto bg-white shadow-sm rounded-xl border border-gray-200">
    <table class="w-full text-sm text-left rtl:text-right text-gray-600">
        <thead class="text-xs text-gray-700 uppercase bg-gray-50 border-b border-gray-200">
            <tr>
                <th scope="col" class="p-4">
                    <div class="flex items-center">
                        <input id="table-checkbox" type="checkbox" class="w-4 h-4 text-elvo bg-gray-100 border-gray-300 rounded focus:ring-elvo">
                    </div>
                </th>
                <th scope="col" class="px-6 py-3 font-semibold">Nama Produk</th>
                <th scope="col" class="px-6 py-3 font-semibold">Warna</th>
                <th scope="col" class="px-6 py-3 font-semibold">Kategori</th>
                <th scope="col" class="px-6 py-3 font-semibold">Aksesoris</th>
                <th scope="col" class="px-6 py-3 font-semibold">Tersedia</th>
                <th scope="col" class="px-6 py-3 font-semibold">Harga</th>
                <th scope="col" class="px-6 py-3 font-semibold">Berat</th>
                <th scope="col" class="px-6 py-3 font-semibold text-center">Aksi</th>
            </tr>
        </thead>
        <tbody>
        @forelse($products as $p)
        <tr class="bg-white border-b border-gray-100 hover:bg-gray-50 transition">
            <td class="w-4 p-4">
                <div class="flex items-center">
                    <input id="table-checkbox-{{ $p->id }}" type="checkbox" class="w-4 h-4 text-elvo bg-gray-100 border-gray-300 rounded focus:ring-elvo">
                </div>
            </td>
            <th scope="row" class="px-6 py-4 font-medium text-gray-900 whitespace-nowrap">
                {{ $p->name }}
            </th>
            <td class="px-6 py-4">-</td>
            <td class="px-6 py-4">{{ $p->category->name ?? 'Uncategorized' }}</td>
            <td class="px-6 py-4 text-green-600 font-bold">Ya</td>
            <td class="px-6 py-4 {{ $p->stock > 0 ? 'text-gray-900' : 'text-red-600' }} font-bold">
                    {{ $p->stock  > 0 ? $p->stock : 'Habis' }}
            </td>
            <td class="px-6 py-4 font-semibold text-gray-900">Rp {{ number_format($p->price) }}</td>
            <td class="px-6 py-4">-</td>
            <td class="px-6 py-4 text-center">
                <a href="#" class="font-medium text-elvo hover:underline">Edit</a>
                <a href="#" class="font-medium text-red-600 hover:underline ms-3">Hapus</a>
            </td>
        </tr>
        @empty
        <tr>
            <td colspan="9" class="text-center py-10 text-gray-500">Belum ada produk. Klik Tambah Produk.</td>
        </tr>
        @endforelse
    </tbody>
    </table>
</div>

{{-- --- MODAL TAMBAH PRODUK (Hidden by Default) --- --}}
<div id="modal-tambah" class="fixed inset-0 z-[60] hidden items-center justify-center bg-gray-900/50 backdrop-blur-sm">
    <div class="relative p-6 w-full max-w-2xl max-h-[90vh] overflow-y-auto bg-white rounded-2xl shadow-2xl">
        <div class="flex items-center justify-between mb-5 border-b pb-3">
            <h3 class="text-xl font-bold text-gray-800">Tambah Produk Baru</h3>
            <button onclick="toggleModal('modal-tambah')" class="text-gray-400 hover:text-gray-600 text-2xl">&times;</button>
        </div>

        <form action="{{ route('admin.products.store') }}" method="POST" enctype="multipart/form-data">
            @csrf
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div class="md:col-span-2">
                    <label class="block mb-2 text-sm font-medium text-gray-700">Nama Produk</label>
                    <input type="text" name="name" class="w-full p-2.5 bg-gray-50 border border-gray-300 rounded-lg focus:ring-blue-500 focus:border-blue-500" placeholder="Contoh: Hoodie Elvo Blue" required>
                </div>

                <div>
                    <label class="block mb-2 text-sm font-medium text-gray-700">Kategori</label>
                    <select name="category_id" class="w-full p-2.5 bg-gray-50 border border-gray-300 rounded-lg" required>
                        <option value="">Pilih Kategori</option>
                        <option value="1">Hoodie</option>
                        <option value="2">Aksesoris</option>
                        <option value="3">T-Shirt</option>
                    </select>
                </div>

                <div>
                    <label class="block mb-2 text-sm font-medium text-gray-700">Warna</label>
                    <input type="text" name="color" class="w-full p-2.5 bg-gray-50 border border-gray-300 rounded-lg" placeholder="Biru / Hitam / Mix">
                </div>

                <div>
                    <label class="block mb-2 text-sm font-medium text-gray-700">Stok Awal</label>
                    <input type="number" name="stock" class="w-full p-2.5 bg-gray-50 border border-gray-300 rounded-lg" required>
                </div>

                <div>
                    <label class="block mb-2 text-sm font-medium text-gray-700">Berat (Gram)</label>
                    <input type="number" name="weight" class="w-full p-2.5 bg-gray-50 border border-gray-300 rounded-lg" placeholder="Contoh: 500" required>
                </div>

                {{-- Tambahkan ini di dalam grid atau sebelum penutup form --}}
                <div class="md:col-span-2">
                    <label class="block mb-2 text-sm font-medium text-gray-700">Deskripsi Produk</label>
                    <textarea name="description" rows="3" class="w-full p-2.5 bg-gray-50 border border-gray-300 rounded-lg focus:ring-blue-500 focus:border-blue-500" placeholder="Jelaskan detail bahan, ukuran, dll..." required></textarea>
                </div>

                <div class="md:col-span-2">
                    <label class="block mb-2 text-sm font-medium text-gray-700">Harga (Rp)</label>
                    <input type="number" name="price" class="w-full p-2.5 bg-gray-50 border border-gray-300 rounded-lg" placeholder="150000" required>
                </div>

                <div class="md:col-span-2">
                    <label class="block mb-2 text-sm font-medium text-gray-700">Foto Produk</label>
                    <input type="file" name="image" class="w-full text-sm text-gray-900 border border-gray-300 rounded-lg cursor-pointer bg-gray-50 p-2">
                </div>
            </div>

            <div class="flex justify-end gap-3 mt-6 pt-4 border-t">
                <button type="button" onclick="toggleModal('modal-tambah')" class="px-5 py-2.5 text-sm font-medium text-gray-500 bg-white rounded-lg border border-gray-200 hover:bg-gray-100">Batal</button>
                <button type="submit" class="px-5 py-2.5 text-sm font-medium text-white bg-blue-600 rounded-lg hover:bg-blue-700 shadow-md">Simpan Produk</button>
            </div>
        </form>
    </div>
</div>

{{-- --- SCRIPT UNTUK MODAL --- --}}
<script>
    function toggleModal(modalId) {
        const modal = document.getElementById(modalId);
        if (modal.classList.contains('hidden')) {
            modal.classList.remove('hidden');
            modal.classList.add('flex');
            document.body.style.overflow = 'hidden'; // Agar dashboard tidak bisa di-scroll saat modal buka
        } else {
            modal.classList.add('hidden');
            modal.classList.remove('flex');
            document.body.style.overflow = 'auto';
        }
    }
</script>

<style>
    .animate-slide-in {
        animation: slideIn 0.3s ease-out;
    }
    @keyframes slideIn {
        from { transform: translateY(-20px); opacity: 0; }
        to { transform: translateY(0); opacity: 1; }
    }
</style>
@endsection