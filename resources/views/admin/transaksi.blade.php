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
            @foreach($history as $item)
            <tr class="bg-white border-b border-gray-100 hover:bg-gray-50 transition text-sm">
                <td class="px-6 py-4 font-mono text-blue-600 font-bold">#{{ $item['id'] }}</td>
                <td class="px-6 py-4 font-medium text-gray-900">{{ $item['name'] }}</td>
                <td class="px-6 py-4 text-gray-500">{{ $item['date'] }}</td>
                <td class="px-6 py-4 font-semibold text-gray-900">Rp {{ $item['total'] }}</td>
                <td class="px-6 py-4">
                    @if($item['status'] == 'selesai')
                        <span class="bg-green-100 text-green-700 text-xs font-bold px-2.5 py-1 rounded-full">Selesai</span>
                    @elseif($item['status'] == 'proses')
                        <span class="bg-blue-100 text-blue-700 text-xs font-bold px-2.5 py-1 rounded-full">Diproses</span>
                    @else
                        <span class="bg-red-100 text-red-700 text-xs font-bold px-2.5 py-1 rounded-full">Dibatalkan</span>
                    @endif
                </td>
                <td class="px-6 py-4 text-center">
                    {{-- Tambahkan onclick untuk memanggil Drawer yang tadi kita buat --}}
                <button onclick="toggleDrawer()" class="text-blue-600 hover:text-blue-800 font-bold">Detail</button>
            </td>
        </tr>
        @endforeach
    </tbody>
    </table>
</div>

{{-- DRAWER DETAIL TRANSAKSI --}}
<div id="drawer-detail" class="fixed inset-y-0 right-0 z-50 w-full max-w-md bg-white shadow-2xl transform translate-x-full transition-transform duration-300 ease-in-out border-l border-gray-100">
    <div class="h-full flex flex-col">
        {{-- Header Drawer --}}
        <div class="p-6 border-b flex justify-between items-center bg-gray-50">
            <div>
                <h2 class="text-xl font-bold text-gray-900">Detail Transaksi</h2>
                <p class="text-xs text-elvo font-mono mt-1" id="drawer-id">#TRX-99210</p>
            </div>
            <button onclick="toggleDrawer()" class="p-2 hover:bg-gray-200 rounded-full transition">
                <svg class="w-6 h-6 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                </svg>
            </button>
        </div>

        {{-- Content Drawer --}}
        <div class="flex-1 overflow-y-auto p-6 space-y-8">
            {{-- Status & Info Utama --}}
            <div class="flex items-center justify-between p-4 bg-blue-50 rounded-2xl border border-blue-100">
                <div>
                    <p class="text-xs text-blue-600 font-bold uppercase tracking-wider">Status Pesanan</p>
                    <p class="text-lg font-bold text-blue-900" id="drawer-status">Selesai</p>
                </div>
                <div class="h-12 w-12 bg-white rounded-xl flex items-center justify-center shadow-sm">
                    <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                </div>
            </div>

            {{-- Informasi Pelanggan --}}
            <section>
                <h3 class="text-sm font-bold text-gray-400 uppercase tracking-widest mb-4">Informasi Pelanggan</h3>
                <div class="grid gap-4">
                    <div class="flex justify-between">
                        <span class="text-gray-500">Nama Lengkap</span>
                        <span class="font-semibold text-gray-900" id="drawer-name">Budi Setiawan</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-gray-500">No. WhatsApp</span>
                        <span class="font-semibold text-gray-900">0812-3456-7890</span>
                    </div>
                </div>
            </section>

            {{-- Alamat Pengiriman --}}
            <section class="p-4 bg-gray-50 rounded-xl border border-gray-100">
                <h3 class="text-sm font-bold text-gray-900 mb-2">Alamat Pengiriman</h3>
                <p class="text-sm text-gray-600 leading-relaxed">
                    Jl. Merdeka No. 123, Kel. Kebon Jeruk, Kec. Palmerah, Kota Jakarta Barat, DKI Jakarta 11530
                </p>
            </section>
        </div>

        {{-- Footer Drawer: Tombol Aksi --}}
        <div class="p-6 border-t bg-white">
            <button onclick="window.print()" class="w-full bg-white border-2 border-gray-200 text-gray-700 py-4 rounded-xl font-bold hover:bg-gray-50 transition-all flex items-center justify-center gap-2">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z" />
                </svg>
                Cetak Invoice
            </button>
        </div>
    </div>
</div>

{{-- SCRIPT UNTUK INTERAKSI --}}
<script>
    function toggleDrawer() {
        const drawer = document.getElementById('drawer-detail');
        drawer.classList.toggle('translate-x-full');
    }
</script>
@endsection