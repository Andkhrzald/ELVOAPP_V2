@extends('layouts.app')

@section('content')
<div class="min-h-screen bg-[#0f0f0f] text-gray-300 pb-12">
    
    {{-- Header Section --}}
    <div class="max-w-7xl mx-auto flex flex-col md:flex-row md:items-end justify-between mb-8 px-4 lg:px-0 pt-10 gap-4">
        <div>
            <h1 class="text-3xl font-bold text-white tracking-tight">Riwayat Transaksi</h1>
            <p class="text-[11px] font-semibold text-blue-500 uppercase tracking-[0.2em] mt-1">Order Management / Sales History</p>
        </div>
        <div class="flex gap-3">
            <button class="bg-[#1a1a1a] border border-white/5 text-gray-300 px-5 py-2.5 rounded-xl text-xs font-bold hover:bg-white/10 hover:text-white transition-all flex items-center gap-2 shadow-xl">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"/></svg>
                EXPORT EXCEL
            </button>
        </div>
    </div>

    {{-- Statistik Ringkas --}}
    <div class="max-w-7xl mx-auto grid grid-cols-1 md:grid-cols-3 gap-6 mb-8 px-4 lg:px-0">
        <div class="p-6 bg-[#1a1a1a] rounded-2xl border border-white/5 shadow-2xl relative overflow-hidden group">
            <div class="relative z-10">
                <p class="text-[10px] font-black text-gray-500 uppercase tracking-widest">Total Transaksi</p>
                <p class="text-3xl font-black text-white mt-2">1,240</p>
            </div>
            <div class="absolute -right-4 -bottom-4 text-white/[0.02] group-hover:text-blue-500/5 transition-colors">
                <svg class="w-24 h-24" fill="currentColor" viewBox="0 0 24 24"><path d="M13 7h-2v4H7v2h4v4h2v-4h4v-2h-4V7zm-1-5C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm0 18c-4.41 0-8-3.59-8-8s3.59-8 8-8 8 3.59 8 8-3.59 8-8 8z"/></svg>
            </div>
        </div>

        <div class="p-6 bg-[#1a1a1a] rounded-2xl border border-white/5 shadow-2xl relative overflow-hidden group">
            <div class="relative z-10">
                <p class="text-[10px] font-black text-gray-500 uppercase tracking-widest">Pendapatan Bulan Ini</p>
                <p class="text-3xl font-black text-blue-500 mt-2">Rp 12.5M</p>
            </div>
            <div class="absolute -right-4 -bottom-4 text-white/[0.02] group-hover:text-blue-500/5 transition-colors">
                <svg class="w-24 h-24" fill="currentColor" viewBox="0 0 24 24"><path d="M11.8 10.9c-2.27-.59-3-1.2-3-2.15 0-1.09 1.01-1.85 2.7-1.85 1.78 0 2.44.85 2.5 2.1h2.21c-.07-1.72-1.12-3.3-3.21-3.81V3h-3v2.16c-1.94.42-3.5 1.68-3.5 3.61 0 2.31 1.91 3.46 4.7 4.13 2.5.6 3 1.48 3 2.41 0 .69-.49 1.79-2.7 1.79-2.06 0-2.87-.92-2.98-2.1h-2.2c.12 2.19 1.76 3.42 3.68 3.83V21h3v-2.15c1.95-.37 3.5-1.5 3.5-3.55 0-2.84-2.43-3.81-4.7-4.4z"/></svg>
            </div>
        </div>

        <div class="p-6 bg-[#1a1a1a] rounded-2xl border border-white/5 shadow-2xl relative overflow-hidden group border-l-4 border-l-orange-500/50">
            <div class="relative z-10">
                <p class="text-[10px] font-black text-gray-500 uppercase tracking-widest text-orange-500/80">Perlu Diproses</p>
                <p class="text-3xl font-black text-orange-500 mt-2">12 <span class="text-xs font-medium text-gray-600 tracking-normal">Pesanan</span></p>
            </div>
        </div>
    </div>

    {{-- Table Container --}}
    <div class="max-w-7xl mx-auto px-4 lg:px-0">
        <div class="bg-[#1a1a1a] rounded-3xl border border-white/5 shadow-2xl overflow-hidden">
            <div class="overflow-x-auto">
                <table class="w-full text-left">
                    <thead>
                        <tr class="bg-white/[0.02] border-b border-white/5">
                            <th class="px-6 py-5 text-[10px] font-black text-gray-500 uppercase tracking-widest">ID Transaksi</th>
                            <th class="px-6 py-5 text-[10px] font-black text-gray-500 uppercase tracking-widest">Pelanggan</th>
                            <th class="px-6 py-5 text-[10px] font-black text-gray-500 uppercase tracking-widest">Tanggal</th>
                            <th class="px-6 py-5 text-[10px] font-black text-gray-500 uppercase tracking-widest">Total</th>
                            <th class="px-6 py-5 text-[10px] font-black text-gray-500 uppercase tracking-widest">Status</th>
                            <th class="px-6 py-5 text-[10px] font-black text-gray-500 uppercase tracking-widest text-center">Detail</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-white/[0.03]">
                        @foreach($history as $item)
                        <tr class="hover:bg-white/[0.02] transition-colors group">
                            <td class="px-6 py-5">
                                <span class="font-mono text-blue-500 font-bold">#{{ $item['id'] }}</span>
                            </td>
                            <td class="px-6 py-5">
                                <div class="flex items-center gap-3">
                                    <div class="w-8 h-8 rounded-full bg-blue-500/10 flex items-center justify-center text-blue-500 text-xs font-bold">
                                        {{ substr($item['name'], 0, 1) }}
                                    </div>
                                    <span class="font-bold text-white text-sm tracking-tight">{{ $item['name'] }}</span>
                                </div>
                            </td>
                            <td class="px-6 py-5 text-xs text-gray-500 font-medium">{{ $item['date'] }}</td>
                            <td class="px-6 py-5">
                                <span class="text-sm font-bold text-white uppercase">Rp {{ $item['total'] }}</span>
                            </td>
                            <td class="px-6 py-5">
                                @if($item['status'] == 'selesai')
                                    <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full bg-green-500/10 text-green-500 text-[10px] font-black uppercase tracking-wider border border-green-500/20">
                                        <span class="w-1.5 h-1.5 rounded-full bg-green-500 animate-pulse"></span>
                                        Selesai
                                    </span>
                                @elseif($item['status'] == 'proses')
                                    <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full bg-blue-500/10 text-blue-500 text-[10px] font-black uppercase tracking-wider border border-blue-500/20">
                                        <span class="w-1.5 h-1.5 rounded-full bg-blue-500 animate-pulse"></span>
                                        Diproses
                                    </span>
                                @else
                                    <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full bg-red-500/10 text-red-500 text-[10px] font-black uppercase tracking-wider border border-red-500/20">
                                        Dibatalkan
                                    </span>
                                @endif
                            </td>
                            <td class="px-6 py-5 text-center">
                                <button onclick="toggleDrawer()" class="p-2 hover:bg-blue-500/10 rounded-lg text-gray-500 hover:text-blue-500 transition-all">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                                </button>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    {{-- DARK DRAWER DETAIL TRANSAKSI --}}
    <div id="drawer-detail" class="fixed inset-y-0 right-0 z-[60] w-full max-w-md bg-[#161616] shadow-[-20px_0_50px_rgba(0,0,0,0.5)] transform translate-x-full transition-transform duration-500 ease-in-out border-l border-white/5">
        <div class="h-full flex flex-col">
            {{-- Header --}}
            <div class="p-8 border-b border-white/5 flex justify-between items-center bg-[#1a1a1a]">
                <div>
                    <h2 class="text-xl font-bold text-white tracking-tight">Detail Transaksi</h2>
                    <p class="text-[10px] text-blue-500 font-black uppercase tracking-[0.2em] mt-1" id="drawer-id">TRX-992108821</p>
                </div>
                <button onclick="toggleDrawer()" class="p-2 hover:bg-white/5 rounded-xl text-gray-500 hover:text-white transition-all">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" /></svg>
                </button>
            </div>

            {{-- Content --}}
            <div class="flex-1 overflow-y-auto p-8 space-y-8">
                {{-- Status Banner --}}
                <div class="p-5 bg-blue-500/10 rounded-2xl border border-blue-500/20 flex items-center justify-between">
                    <div>
                        <p class="text-[10px] font-black text-blue-500/70 uppercase tracking-widest">Status Pesanan</p>
                        <p class="text-lg font-bold text-white mt-1" id="drawer-status">Selesai Berhasil</p>
                    </div>
                    <div class="h-12 w-12 bg-blue-500 rounded-2xl flex items-center justify-center shadow-lg shadow-blue-500/20">
                        <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                    </div>
                </div>

                {{-- Pelanggan --}}
                <section>
                    <h3 class="text-[10px] font-black text-gray-600 uppercase tracking-[0.2em] mb-4">Informasi Pelanggan</h3>
                    <div class="space-y-4 bg-white/[0.02] p-5 rounded-2xl border border-white/5">
                        <div class="flex justify-between items-center">
                            <span class="text-sm text-gray-500">Nama</span>
                            <span class="text-sm font-bold text-white" id="drawer-name">Budi Setiawan</span>
                        </div>
                        <div class="flex justify-between items-center">
                            <span class="text-sm text-gray-500">WhatsApp</span>
                            <span class="text-sm font-bold text-blue-500">0812-3456-7890</span>
                        </div>
                    </div>
                </section>

                {{-- Alamat --}}
                <section>
                    <h3 class="text-[10px] font-black text-gray-600 uppercase tracking-[0.2em] mb-4">Alamat Pengiriman</h3>
                    <div class="bg-white/[0.02] p-5 rounded-2xl border border-white/5">
                        <p class="text-sm text-gray-400 leading-relaxed font-medium">
                            Jl. Merdeka No. 123, Kel. Kebon Jeruk, Kec. Palmerah, Kota Jakarta Barat, DKI Jakarta 11530
                        </p>
                    </div>
                </section>
            </div>

            {{-- Footer --}}
            <div class="p-8 border-t border-white/5 bg-[#1a1a1a]">
                <button onclick="window.print()" class="w-full bg-white text-black py-4 rounded-2xl font-black text-xs uppercase tracking-widest hover:bg-blue-500 hover:text-white transition-all shadow-xl">
                    CETAK INVOICE
                </button>
            </div>
        </div>
    </div>

    {{-- Overlay Background --}}
    <div id="drawer-overlay" onclick="toggleDrawer()" class="fixed inset-0 bg-black/60 backdrop-blur-sm z-[50] hidden transition-opacity duration-500"></div>

</div>

<script>
    function toggleDrawer() {
        const drawer = document.getElementById('drawer-detail');
        const overlay = document.getElementById('drawer-overlay');
        
        if (drawer.classList.contains('translate-x-full')) {
            drawer.classList.remove('translate-x-full');
            overlay.classList.remove('hidden');
        } else {
            drawer.classList.add('translate-x-full');
            overlay.classList.add('hidden');
        }
    }
</script>

<style>
    /* Styling khusus print invoice agar tidak berantakan */
    @media print {
        body * { visibility: hidden; }
        #drawer-detail, #drawer-detail * { visibility: visible; }
        #drawer-detail { position: absolute; left: 0; top: 0; width: 100%; }
    }
</style>
@endsection