@extends('layouts.app')

@section('content')
{{-- 1. PINDAHKAN NOTIFIKASI KE DALAM SINI --}}
@if(session('success'))
    <div class="bg-green-100 border-l-4 border-green-500 text-green-700 p-4 mb-4 rounded shadow-sm flex justify-between items-center" role="alert">
        <div>
            <p class="font-bold">Sukses!</p>
            <p>{{ session('success') }}</p>
        </div>
        <button onclick="this.parentElement.remove()" class="text-green-700 font-bold">&times;</button>
    </div>
@endif

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
    {{-- Card Pesanan --}}
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
            <button onclick="toggleDrawer()" class="text-sm text-gray-600 font-medium px-4 py-2">Lihat Detail</button>
            <button onclick="toggleModal()" class="bg-blue-600 text-white px-4 py-2 rounded-lg text-sm font-medium hover:bg-opacity-90 transition">Konfirmasi & Kirim</button>
        </div>
    </div>
</div>

{{-- DRAWER DETAIL --}}
<div id="drawer-detail" class="fixed inset-y-0 right-0 z-50 hidden">
    <div class="w-screen max-w-md shadow-2xl border-l border-gray-200 animate-slide-in">
        <div class="flex h-full flex-col bg-white">
            <div class="flex-1 overflow-y-auto px-4 py-6 sm:px-6">
                <div class="flex items-start justify-between border-b pb-4">
                    <h2 class="text-lg font-bold text-gray-900">Detail Pesanan #TRX-99211</h2>
                    <button type="button" class="text-gray-400 hover:text-gray-500" onclick="toggleDrawer()">
                        <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" /></svg>
                    </button>
                </div>

                <div class="mt-8">
                    <div class="flow-root">
                        <ul role="list" class="-my-6 divide-y divide-gray-200">
                            <li class="flex py-6">
                                <div class="h-24 w-24 flex-shrink-0 overflow-hidden rounded-md border border-gray-200">
                                    <img src="https://via.placeholder.com/150" class="h-full w-full object-cover object-center">
                                </div>
                                <div class="ml-4 flex flex-1 flex-col">
                                    <div class="flex justify-between text-base font-medium text-gray-900">
                                        <h3>Elvo Premium Hoodie</h3>
                                        <p class="ml-4">Rp 450.000</p>
                                    </div>
                                    <p class="mt-1 text-sm text-gray-500">Warna: Hitam | Size: XL</p>
                                </div>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>

            <div class="border-t border-gray-200 px-4 py-6 sm:px-6 bg-white">
                <div class="flex justify-between text-base font-bold text-gray-900">
                    <p>Total Pembayaran</p>
                    <p>Rp 1.200.000</p>
                </div>
                <div class="mt-6">
                    <button onclick="toggleModal()" class="w-full flex items-center justify-center rounded-md border border-transparent bg-elvo px-6 py-3 text-base font-medium text-white shadow-sm">Konfirmasi Pengiriman</button>
                </div>
            </div>
        </div>
    </div>
</div>

{{-- MODAL KONFIRMASI --}}
<div id="modal-konfirmasi" class="fixed inset-0 z-[60] hidden">
    <div class="fixed inset-0 bg-black bg-opacity-25" onclick="toggleModal()"></div>
    <div class="flex items-center justify-center min-h-screen p-4">
        
        {{-- UPDATE BAGIAN FORM ACTION INI --}}
        <form action="{{ route('admin.orders.confirm', $order->id) }}" method="POST" class="relative bg-white rounded-xl shadow-xl max-w-lg w-full p-6 z-10">
            @csrf
            <h3 class="text-lg font-bold text-gray-900">Konfirmasi Pengiriman?</h3>
            <p class="text-sm text-gray-500 mt-2">Pastikan barang sudah dipacking. Masukkan nomor resi di bawah:</p>
            
            <input type="text" name="resi" required placeholder="Masukkan Nomor Resi" 
                   class="mt-4 w-full px-3 py-2 border border-gray-300 rounded-md focus:ring-elvo focus:border-elvo outline-none">
            
            <div class="mt-6 flex justify-end gap-3">
                <button type="button" onclick="toggleModal()" class="px-4 py-2 text-gray-700 font-medium hover:bg-gray-100 rounded-lg">Batal</button>
                <button type="submit" class="px-4 py-2 bg-blue-600  text-white rounded-lg font-medium hover:bg-opacity-90">Proses Sekarang</button>
            </div>
        </form>
    </div>
</div>

<script>
    function toggleDrawer() {
        const drawer = document.getElementById('drawer-detail');
        drawer.classList.toggle('hidden');
        // Tambahkan ini supaya drawer tampil rapi
        drawer.classList.toggle('flex'); 
    }
    
    function toggleModal() {
        document.getElementById('modal-konfirmasi').classList.toggle('hidden');
    }
</script>

<style>
    .animate-slide-in { animation: slideIn 0.3s ease-out; }
    @keyframes slideIn {
        from { transform: translateX(100%); }
        to { transform: translateX(0); }
    }
</style>
@endsection