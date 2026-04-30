@extends('layouts.app')

@section('content')
<div class="mb-5 flex justify-between items-center">
    <div>
        <h1 class="text-2xl font-bold text-gray-800">Pesanan Masuk</h1>
        <p class="text-sm text-gray-500">Segera proses pesanan pelanggan agar mereka senang!</p>
    </div>
</div>

{{-- Filter Status Tab --}}
<div class="flex border-b border-gray-200 mb-6">
    <button class="px-4 py-2 text-sm font-medium text-elvo border-b-2 border-elvo">Perlu Diproses (12)</button>
    <button class="px-4 py-2 text-sm font-medium text-gray-500 hover:text-gray-700">Belum Bayar (5)</button>
    <button class="px-4 py-2 text-sm font-medium text-gray-500 hover:text-gray-700">Dikirim (20)</button>
</div>

<div class="grid grid-cols-1 gap-4">
    {{-- Card Pesanan 1 --}}
    <div class="p-5 bg-white rounded-xl border border-gray-200 shadow-sm">
        <div class="flex justify-between items-start mb-4">
            <div class="flex gap-4">
                <div class="w-12 h-12 bg-blue-50 rounded-lg flex items-center justify-center text-elvo">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"/></svg>
                </div>
                <div>
                    <h3 class="font-bold text-gray-900">#TRX-99211 - Siti Aminah</h3>
                    <p class="text-xs text-gray-500">Dipesan pada: 21 Okt 2023 • 14:20 WIB</p>
                </div>
            </div>
            <span class="bg-orange-100 text-orange-600 text-xs font-bold px-3 py-1 rounded-full">Perlu Dikemas</span>
        </div>

        <div class="border-t border-b border-gray-50 py-3 my-3">
            <div class="flex justify-between text-sm">
                <span class="text-gray-600 italic">2x Produk Elvo Premium (XL), 1x Aksesoris</span>
                <span class="font-bold text-gray-900">Total: Rp 1.200.000</span>
            </div>
        </div>

        <div class="flex justify-end gap-3">
            <button class="text-sm text-gray-600 font-medium px-4 py-2">Lihat Detail</button>
            <button class="bg-elvo text-white px-4 py-2 rounded-lg text-sm font-medium hover:bg-opacity-90 transition">Konfirmasi & Kirim</button>
        </div>
    </div>

    {{-- Card Pesanan 2 --}}
    <div class="p-5 bg-white rounded-xl border border-gray-200 shadow-sm opacity-75">
        </div>
</div>
@endsection