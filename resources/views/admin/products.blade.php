@extends('layouts.app')

@section('content')
<div class="mb-5 flex justify-between items-center">
    <h1 class="text-2xl font-bold text-gray-800">Daftar Produk Elvo</h1>
    
    <button class="bg-white border border-gray-200 text-gray-700 hover:bg-gray-50 px-4 py-2 rounded-lg text-sm transition shadow-sm flex items-center gap-2 font-medium">
        <svg class="w-4 h-4 text-elvo" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
        </svg>
        Tambah Produk
    </button>
</div>

<div class="relative overflow-x-auto bg-white shadow-sm rounded-xl border border-gray-200">
    <table class="w-full text-sm text-left rtl:text-right text-gray-600">
        <thead class="text-xs text-gray-700 uppercase bg-gray-50 border-b border-gray-200">
            <tr>
                <th scope="col" class="p-4">
                    <div class="flex items-center">
                        <input id="table-checkbox" type="checkbox" class="w-4 h-4 text-elvo bg-gray-100 border-gray-300 rounded focus:ring-elvo">
                        <label for="table-checkbox" class="sr-only">checkbox</label>
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
            {{-- Baris 1 --}}
            <tr class="bg-white border-b border-gray-100 hover:bg-gray-50 transition">
                <td class="w-4 p-4">
                    <div class="flex items-center">
                        <input id="table-checkbox-2" type="checkbox" class="w-4 h-4 text-elvo bg-gray-100 border-gray-300 rounded focus:ring-elvo">
                    </div>
                </td>
                <th scope="row" class="px-6 py-4 font-medium text-gray-900 whitespace-nowrap">Apple MacBook Pro 17"</th>
                <td class="px-6 py-4">Silver</td>
                <td class="px-6 py-4">Laptop</td>
                <td class="px-6 py-4 text-green-600 font-bold">Ya</td>
                <td class="px-6 py-4 text-green-600 font-bold">Ya</td>
                <td class="px-6 py-4 font-semibold text-gray-900">$2999</td>
                <td class="px-6 py-4">3.0 lb.</td>
                <td class="px-6 py-4 text-center">
                    <a href="#" class="font-medium text-elvo hover:underline">Edit</a>
                    <a href="#" class="font-medium text-red-600 hover:underline ms-3">Hapus</a>
                </td>
            </tr>
            {{-- Baris 2 --}}
            <tr class="bg-white border-b border-gray-100 hover:bg-gray-50 transition">
                <td class="w-4 p-4">
                    <div class="flex items-center">
                        <input id="table-checkbox-3" type="checkbox" class="w-4 h-4 text-elvo bg-gray-100 border-gray-300 rounded focus:ring-elvo">
                    </div>
                </td>
                <th scope="row" class="px-6 py-4 font-medium text-gray-900 whitespace-nowrap">Microsoft Surface Pro</th>
                <td class="px-6 py-4">White</td>
                <td class="px-6 py-4">Laptop PC</td>
                <td class="px-6 py-4 text-red-600 font-bold">Tidak</td>
                <td class="px-6 py-4 text-green-600 font-bold">Ya</td>
                <td class="px-6 py-4 font-semibold text-gray-900">$1999</td>
                <td class="px-6 py-4">1.0 lb.</td>
                <td class="px-6 py-4 text-center">
                    <a href="#" class="font-medium text-elvo hover:underline">Edit</a>
                    <a href="#" class="font-medium text-red-600 hover:underline ms-3">Hapus</a>
                </td>
            </tr>
            {{-- Baris 3 --}}
            <tr class="bg-white border-b border-gray-100 hover:bg-gray-50 transition">
                <td class="w-4 p-4">
                    <div class="flex items-center">
                        <input id="table-checkbox-4" type="checkbox" class="w-4 h-4 text-elvo bg-gray-100 border-gray-300 rounded focus:ring-elvo">
                    </div>
                </td>
                <th scope="row" class="px-6 py-4 font-medium text-gray-900 whitespace-nowrap">Magic Mouse 2</th>
                <td class="px-6 py-4">Black</td>
                <td class="px-6 py-4">Accessories</td>
                <td class="px-6 py-4 text-green-600 font-bold">Ya</td>
                <td class="px-6 py-4 text-red-600 font-bold">Tidak</td>
                <td class="px-6 py-4 font-semibold text-gray-900">$99</td>
                <td class="px-6 py-4">0.2 lb.</td>
                <td class="px-6 py-4 text-center">
                    <a href="#" class="font-medium text-elvo hover:underline">Edit</a>
                    <a href="#" class="font-medium text-red-600 hover:underline ms-3">Hapus</a>
                </td>
            </tr>
        </tbody>
    </table>
</div>
@endsection