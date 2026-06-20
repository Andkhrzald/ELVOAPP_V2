@extends('layouts.app')

@section('content')
<div class="mb-6">
    <div class="flex items-center gap-2 mb-1">
        <h1 class="text-2xl font-bold text-white">Riwayat Stok</h1>
        <span class="px-2 py-0.5 text-[10px] font-bold text-yellow-500 bg-yellow-500/10 rounded-full uppercase tracking-wider">Owner Only</span>
    </div>
    <p class="text-sm text-gray-400">Catatan mutasi stok produk.</p>
</div>

<div class="flex items-center justify-end mb-4">
    <a href="{{ route('admin.owner.stock-history.export') }}"
       class="px-5 py-2.5 text-xs font-bold bg-emerald-500/10 text-emerald-400 border border-emerald-500/20 rounded-xl hover:bg-emerald-500/20 transition-all flex items-center gap-2">
        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
        Export Excel
    </a>
</div>

{{-- Filter --}}
<div class="p-4 bg-elvo-surface rounded-xl border border-white/[0.06] mb-6">
    <form method="GET" class="grid grid-cols-2 md:grid-cols-5 gap-3 items-end">
        <div>
            <label class="block text-[10px] font-bold text-gray-500 uppercase tracking-widest mb-1">Produk</label>
            <select name="product_id" class="w-full px-3 py-2 text-sm border border-white/10 rounded-lg bg-[#252525] text-gray-300">
                <option value="">Semua Produk</option>
                @foreach($products as $p)
                <option value="{{ $p->id }}" {{ request('product_id') == $p->id ? 'selected' : '' }}>{{ $p->name }}</option>
                @endforeach
            </select>
        </div>
        <div>
            <label class="block text-[10px] font-bold text-gray-500 uppercase tracking-widest mb-1">Tipe</label>
            <select name="type" class="w-full px-3 py-2 text-sm border border-white/10 rounded-lg bg-[#252525] text-gray-300">
                <option value="">Semua</option>
                <option value="in" {{ request('type') == 'in' ? 'selected' : '' }}>Stok Masuk</option>
                <option value="out" {{ request('type') == 'out' ? 'selected' : '' }}>Stok Keluar</option>
                <option value="adjustment" {{ request('type') == 'adjustment' ? 'selected' : '' }}>Penyesuaian</option>
                <option value="order" {{ request('type') == 'order' ? 'selected' : '' }}>Pesanan</option>
                <option value="cancel" {{ request('type') == 'cancel' ? 'selected' : '' }}>Pembatalan</option>
            </select>
        </div>
        <div>
            <label class="block text-[10px] font-bold text-gray-500 uppercase tracking-widest mb-1">Dari</label>
            <input type="date" name="date_from" value="{{ request('date_from') }}" class="w-full px-3 py-2 text-sm border border-white/10 rounded-lg bg-[#252525] text-gray-300">
        </div>
        <div>
            <label class="block text-[10px] font-bold text-gray-500 uppercase tracking-widest mb-1">Sampai</label>
            <input type="date" name="date_to" value="{{ request('date_to') }}" class="w-full px-3 py-2 text-sm border border-white/10 rounded-lg bg-[#252525] text-gray-300">
        </div>
        <button type="submit" class="px-4 py-2 text-sm font-semibold btn-primary rounded-lg w-full">Filter</button>
    </form>
</div>

<div class="bg-elvo-surface rounded-xl border border-white/[0.06] shadow-[0_0_25px_rgba(0,0,0,0.2)] overflow-hidden">
    <div class="overflow-x-auto">
        <table class="w-full text-left text-sm">
            <thead>
                <tr class="bg-white/[0.02] border-b border-white/[0.06]">
                    <th class="px-6 py-4 text-[10px] font-black text-gray-500 uppercase tracking-widest">Waktu</th>
                    <th class="px-6 py-4 text-[10px] font-black text-gray-500 uppercase tracking-widest">Produk</th>
                    <th class="px-6 py-4 text-[10px] font-black text-gray-500 uppercase tracking-widest">Tipe</th>
                    <th class="px-6 py-4 text-[10px] font-black text-gray-500 uppercase tracking-widest text-center">Qty</th>
                    <th class="px-6 py-4 text-[10px] font-black text-gray-500 uppercase tracking-widest text-center">Stok Awal</th>
                    <th class="px-6 py-4 text-[10px] font-black text-gray-500 uppercase tracking-widest text-center">Stok Akhir</th>
                    <th class="px-6 py-4 text-[10px] font-black text-gray-500 uppercase tracking-widest">User</th>
                    <th class="px-6 py-4 text-[10px] font-black text-gray-500 uppercase tracking-widest">Keterangan</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-white/5">
                @forelse($mutations as $m)
                <tr class="hover:bg-white/[0.01]">
                    <td class="px-6 py-4 text-gray-400 whitespace-nowrap text-xs">{{ $m->created_at->format('d/m/Y H:i') }}</td>
                    <td class="px-6 py-4 text-white font-medium">{{ $m->product->name ?? '—' }}</td>
                    <td class="px-6 py-4">
                        @php
                        $typeStyles = [
                            'in' => 'bg-green-500/10 text-green-400',
                            'out' => 'bg-red-500/10 text-red-400',
                            'adjustment' => 'bg-yellow-500/10 text-yellow-400',
                            'order' => 'bg-blue-500/10 text-blue-400',
                            'cancel' => 'bg-purple-500/10 text-purple-400',
                        ];
                        $typeLabels = ['in' => 'Masuk', 'out' => 'Keluar', 'adjustment' => 'Adjust', 'order' => 'Pesanan', 'cancel' => 'Batal'];
                        @endphp
                        <span class="px-2 py-0.5 text-[10px] font-bold rounded-full {{ $typeStyles[$m->type] ?? 'bg-gray-500/10 text-gray-400' }}">
                            {{ $typeLabels[$m->type] ?? $m->type }}
                        </span>
                    </td>
                    <td class="px-6 py-4 text-center font-mono font-bold {{ $m->type === 'in' || $m->type === 'cancel' ? 'text-green-400' : 'text-red-400' }}">
                        {{ $m->type === 'in' || $m->type === 'cancel' ? '+' : '-' }}{{ $m->qty }}
                    </td>
                    <td class="px-6 py-4 text-center text-gray-400 font-mono">{{ $m->old_stock }}</td>
                    <td class="px-6 py-4 text-center text-white font-mono font-bold">{{ $m->new_stock }}</td>
                    <td class="px-6 py-4 text-gray-400">{{ $m->user?->name ?? 'System' }}</td>
                    <td class="px-6 py-4 text-gray-500 max-w-[200px] truncate">{{ $m->note ?? '—' }}</td>
                </tr>
                @empty
                <tr>
                    <td colspan="8" class="px-6 py-16 text-center">
                        <div class="flex flex-col items-center">
                            <div class="w-12 h-12 bg-white/5 rounded-full flex items-center justify-center mb-3">
                                <svg class="w-6 h-6 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/></svg>
                            </div>
                            <p class="text-gray-500 font-medium italic">Belum ada mutasi stok.</p>
                        </div>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    @if($mutations->hasPages())
    <div class="p-6 border-t border-white/[0.06]">{{ $mutations->withQueryString()->links() }}</div>
    @endif
</div>
@endsection
