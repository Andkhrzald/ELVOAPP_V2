@extends('layouts.app')

@section('content')
<div class="mb-5 flex justify-between items-center">
    <div>
        <h1 class="text-2xl font-bold text-gray-800">Riwayat Transaksi</h1>
        <p class="text-sm text-gray-500">Kelola dan pantau semua pesanan masuk Elvoapp</p>
    </div>
    <div class="flex gap-3">
        <button class="bg-white border border-gray-300 text-gray-700 px-4 py-2 rounded-lg text-sm font-medium hover:bg-gray-50 transition shadow-sm flex items-center gap-2">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"/></svg>
            Export Excel
        </button>
    </div>
</div>

{{-- Statistik Ringkas --}}
<div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-6">
    <div class="p-4 bg-white rounded-xl border border-gray-200 shadow-sm">
        <p class="text-sm text-gray-500">Total Transaksi</p>
        <p class="text-2xl font-bold text-gray-900">1,240</p>
    </div>
    <div class="p-4 bg-white rounded-xl border border-gray-200 shadow-sm">
        <p class="text-sm text-gray-500">Pendapatan Bulan Ini</p>
        <p class="text-2xl font-bold text-elvo">Rp 12.500.000</p>
    </div>
    <div class="p-4 bg-white rounded-xl border border-gray-200 shadow-sm">
        <p class="text-sm text-gray-500">Perlu Diproses</p>
        <p class="text-2xl font-bold text-orange-500">12 Pesanan</p>
    </div>
</div>

<div class="relative overflow-x-auto bg-white shadow-sm rounded-xl border border-gray-200">
    <table class="w-full text-sm text-left text-gray-600">
        <thead class="text-xs text-gray-700 uppercase bg-gray-50 border-b border-gray-200">
            <tr>
                <th scope="col" class="px-6 py-4 font-semibold">ID Transaksi</th>
                <th scope="col" class="px-6 py-4 font-semibold">Pelanggan</th>
                <th scope="col" class="px-6 py-4 font-semibold">Tanggal</th>
                <th scope="col" class="px-6 py-4 font-semibold">Total Harga</th>
                <th scope="col" class="px-6 py-4 font-semibold">Status</th>
                <th scope="col" class="px-6 py-4 font-semibold text-center">Aksi</th>
            </tr>
        </thead>
        <tbody>
            {{-- Contoh Transaksi Selesai --}}
            <tr class="bg-white border-b border-gray-100 hover:bg-gray-50 transition">
                <td class="px-6 py-4 font-mono text-elvo font-bold">#TRX-99210</td>
                <td class="px-6 py-4 font-medium text-gray-900">Budi Setiawan</td>
                <td class="px-6 py-4">20 Okt 2023</td>
                <td class="px-6 py-4 font-semibold">Rp 450.000</td>
                <td class="px-6 py-4">
                    <span class="bg-green-100 text-green-700 text-xs font-bold px-2.5 py-1 rounded-full">Selesai</span>
                </td>
                <td class="px-6 py-4 text-center">
                    <button class="text-elvo hover:text-elvo-hover font-medium">Detail</button>
                </td>
            </tr>

            {{-- Contoh Transaksi Proses --}}
            <tr class="bg-white border-b border-gray-100 hover:bg-gray-50 transition">
                <td class="px-6 py-4 font-mono text-elvo font-bold">#TRX-99211</td>
                <td class="px-6 py-4 font-medium text-gray-900">Siti Aminah</td>
                <td class="px-6 py-4">21 Okt 2023</td>
                <td class="px-6 py-4 font-semibold">Rp 1.200.000</td>
                <td class="px-6 py-4">
                    <span class="bg-blue-100 text-blue-700 text-xs font-bold px-2.5 py-1 rounded-full">Diproses</span>
                </td>
                <td class="px-6 py-4 text-center">
                    <button class="text-elvo hover:text-elvo-hover font-medium">Detail</button>
                </td>
            </tr>

            {{-- Contoh Transaksi Batal --}}
            <tr class="bg-white border-b border-gray-100 hover:bg-gray-50 transition">
                <td class="px-6 py-4 font-mono text-elvo font-bold">#TRX-99212</td>
                <td class="px-6 py-4 font-medium text-gray-900">Rehan Pratama</td>
                <td class="px-6 py-4">22 Okt 2023</td>
                <td class="px-6 py-4 font-semibold">Rp 75.000</td>
                <td class="px-6 py-4">
                    <span class="bg-red-100 text-red-700 text-xs font-bold px-2.5 py-1 rounded-full">Dibatalkan</span>
                </td>
                <td class="px-6 py-4 text-center">
                    <button class="text-elvo hover:text-elvo-hover font-medium">Detail</button>
                </td>
            </tr>
        </tbody>
    </table>
</div>
@endsection