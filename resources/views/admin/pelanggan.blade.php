@extends('layouts.app')

@section('content')
<div class="min-h-screen bg-[#0f0f0f] text-gray-300 pb-12">

    {{-- Header --}}
    <div class="max-w-7xl mx-auto flex flex-col md:flex-row md:items-end justify-between mb-8 px-4 lg:px-0 pt-10 gap-4">
        <div>
            <h1 class="text-3xl font-bold text-white tracking-tight">Data Pelanggan</h1>
            <p class="text-[11px] font-semibold text-elvo-primary uppercase tracking-[0.2em] mt-1">Customer Management</p>
        </div>
        {{-- Search --}}
        <form action="{{ route('admin.pelanggan') }}" method="GET" class="flex gap-3">
            <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari nama, email, HP..."
                class="bg-elvo-surface border border-white/10 rounded-xl px-4 py-2.5 text-sm text-white placeholder-gray-600 w-64 focus:outline-none focus:border-elvo-primary/50">
            <button class="px-4 py-2.5 btn-primary text-xs">Cari</button>
        </form>
    </div>

    {{-- Stats --}}
    <div class="max-w-7xl mx-auto grid grid-cols-1 md:grid-cols-3 gap-6 mb-8 px-4 lg:px-0">
        <div class="p-6 bg-elvo-surface rounded-2xl border border-white/[0.06] card-hover">
            <p class="text-[10px] font-black text-gray-500 uppercase tracking-widest">Total Pelanggan</p>
            <p class="text-3xl font-black text-white mt-2">{{ $totalCustomers }}</p>
        </div>
        <div class="p-6 bg-elvo-surface rounded-2xl border border-white/[0.06] card-hover">
            <p class="text-[10px] font-black text-gray-500 uppercase tracking-widest">Pelanggan Aktif</p>
            <p class="text-3xl font-black text-green-500 mt-2">{{ $activeCustomers }}</p>
            <p class="text-[10px] text-gray-600 mt-1">Pernah selesaikan pesanan</p>
        </div>
        <div class="p-6 bg-elvo-surface rounded-2xl border border-white/[0.06] card-hover">
            <p class="text-[10px] font-black text-gray-500 uppercase tracking-widest">Total Pendapatan</p>
            <p class="text-3xl font-black text-white mt-2">Rp {{ number_format($totalRevenue, 0, ',', '.') }}</p>
            <p class="text-[10px] text-gray-600 mt-1">Dari pesanan selesai</p>
        </div>
    </div>

    {{-- Sort Tabs --}}
    <div class="max-w-7xl mx-auto px-4 lg:px-0 mb-6 flex gap-3">
        @foreach(['latest'=>'Terbaru','name'=>'Nama','orders'=>'Pesanan Terbanyak','spent'=>'Belanja Terbanyak'] as $key => $label)
        <a href="{{ route('admin.pelanggan', ['sort'=>$key, 'search'=>request('search')]) }}"
            class="px-4 py-2 rounded-xl text-xs font-bold transition {{ request('sort', 'latest') === $key ? 'bg-elvo-primary text-white' : 'bg-elvo-surface text-gray-400 border border-white/[0.06] hover:text-white' }}">
            {{ $label }}
        </a>
        @endforeach
    </div>

    {{-- Table --}}
    <div class="max-w-7xl mx-auto px-4 lg:px-0">
        <div class="bg-elvo-surface rounded-2xl border border-white/[0.06] overflow-hidden">
            <table class="w-full">
                <thead>
                    <tr class="border-b border-white/[0.06]">
                        <th class="text-left px-6 py-4 text-[10px] font-black text-gray-500 uppercase tracking-widest">Pelanggan</th>
                        <th class="text-left px-6 py-4 text-[10px] font-black text-gray-500 uppercase tracking-widest hidden md:table-cell">Kontak</th>
                        <th class="text-center px-6 py-4 text-[10px] font-black text-gray-500 uppercase tracking-widest">Pesanan</th>
                        <th class="text-right px-6 py-4 text-[10px] font-black text-gray-500 uppercase tracking-widest">Total Belanja</th>
                        <th class="text-center px-6 py-4 text-[10px] font-black text-gray-500 uppercase tracking-widest">Bergabung</th>
                        <th class="text-center px-6 py-4 text-[10px] font-black text-gray-500 uppercase tracking-widest">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($customers as $c)
                    <tr class="border-b border-white/[0.06] hover:bg-white/[0.02] transition">
                        <td class="px-6 py-4">
                            <div class="flex items-center gap-3">
                                <div class="w-10 h-10 rounded-full bg-elvo-primary/10 flex items-center justify-center text-elvo-primary font-bold text-sm">
                                    {{ strtoupper(substr($c->name, 0, 1)) }}
                                </div>
                                <div>
                                    <p class="text-sm font-bold text-white">{{ $c->name }}</p>
                                    <p class="text-[10px] text-gray-500">{{ $c->email }}</p>
                                </div>
                            </div>
                        </td>
                        <td class="px-6 py-4 hidden md:table-cell">
                            <p class="text-xs text-gray-400">{{ $c->phone ?? '-' }}</p>
                            <p class="text-[10px] text-gray-600 truncate max-w-[200px]">{{ $c->address ?? '-' }}</p>
                        </td>
                        <td class="px-6 py-4 text-center">
                            <span class="px-3 py-1 bg-elvo-primary/10 text-elvo-primary rounded-full text-xs font-bold">{{ $c->orders_count }}</span>
                        </td>
                        <td class="px-6 py-4 text-right">
                            <span class="text-sm font-bold text-white">Rp {{ number_format($c->total_spent ?? 0, 0, ',', '.') }}</span>
                        </td>
                        <td class="px-6 py-4 text-center text-xs text-gray-500">
                            {{ $c->created_at->format('d M Y') }}
                        </td>
                        <td class="px-6 py-4 text-center">
                            <a href="{{ route('admin.pelanggan.show', $c->id) }}" class="px-3 py-1.5 bg-white/5 border border-white/10 rounded-lg text-[10px] font-bold text-gray-400 hover:text-white hover:border-elvo-primary/30 transition">Detail</a>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="px-6 py-12 text-center text-gray-600 font-bold">Belum ada pelanggan terdaftar</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        {{-- Pagination --}}
        @if($customers->hasPages())
        <div class="mt-6">{{ $customers->withQueryString()->links() }}</div>
        @endif
    </div>
</div>
@endsection
