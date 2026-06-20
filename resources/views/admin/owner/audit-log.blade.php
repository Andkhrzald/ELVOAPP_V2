@extends('layouts.app')

@php
$actionMeta = [
    'order_created'    => ['label' => 'Pesanan Baru',    'icon' => 'M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 100 4 2 2 0 000-4z',                                           'color' => 'blue'],
    'order_confirmed'  => ['label' => 'Dikonfirmasi',    'icon' => 'M5 13l4 4L19 7',                                                                                       'color' => 'emerald'],
    'order_shipped'    => ['label' => 'Dikirim',         'icon' => 'M13 16V6a1 1 0 00-1-1H4a1 1 0 00-1 1v10l2-1m4 0l2 1m-2 0l2-1m-2 1V9m6 7V7m0 0l2-1m-2 1l-2-1m2 1v10',    'color' => 'indigo'],
    'order_completed'  => ['label' => 'Selesai',         'icon' => 'M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z',                                                         'color' => 'green'],
    'cancel_confirmed' => ['label' => 'Dibatalkan',      'icon' => 'M6 18L18 6M6 6l12 12',                                                                                 'color' => 'red'],
    'cancel_rejected'  => ['label' => 'Batal Ditolak',   'icon' => 'M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z', 'color' => 'amber'],
    'refund_confirmed' => ['label' => 'Refund',          'icon' => 'M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z', 'color' => 'pink'],
    'refund_rejected'  => ['label' => 'Refund Ditolak',  'icon' => 'M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z',                                 'color' => 'orange'],
    'admin_created'    => ['label' => 'Admin Baru',      'icon' => 'M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z',                'color' => 'purple'],
    'admin_deleted'    => ['label' => 'Admin Dihapus',   'icon' => 'M13 7a4 4 0 11-8 0 4 4 0 018 0zM9 14a6 6 0 00-6 6v1h12v-1a6 6 0 00-6-6zM21 12h-6',                    'color' => 'red'],
    'settings_updated' => ['label' => 'Pengaturan',      'icon' => 'M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z', 'color' => 'yellow'],
    'product_created'  => ['label' => 'Produk Baru',     'icon' => 'M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4',                                       'color' => 'teal'],
    'stock_updated'    => ['label' => 'Stok Diubah',     'icon' => 'M4 7v10c0 2.21 3.582 4 8 4s8-1.79 8-4V7M4 7c0 2.21 3.582 4 8 4s8-1.79 8-4M4 7c0-2.21 3.582-4 8-4s8 1.79 8 4', 'color' => 'orange'],
];

$colorMap = [
    'blue'    => 'bg-blue-500/10 text-blue-400 border-blue-500/20',
    'emerald' => 'bg-emerald-500/10 text-emerald-400 border-emerald-500/20',
    'indigo'  => 'bg-indigo-500/10 text-indigo-400 border-indigo-500/20',
    'green'   => 'bg-green-500/10 text-green-400 border-green-500/20',
    'red'     => 'bg-red-500/10 text-red-400 border-red-500/20',
    'amber'   => 'bg-amber-500/10 text-amber-400 border-amber-500/20',
    'pink'    => 'bg-pink-500/10 text-pink-400 border-pink-500/20',
    'orange'  => 'bg-orange-500/10 text-orange-400 border-orange-500/20',
    'purple'  => 'bg-purple-500/10 text-purple-400 border-purple-500/20',
    'yellow'  => 'bg-yellow-500/10 text-yellow-400 border-yellow-500/20',
    'teal'    => 'bg-teal-500/10 text-teal-400 border-teal-500/20',
    'gray'    => 'bg-gray-500/10 text-gray-400 border-gray-500/20',
];

$roleColors = [
    'owner'    => 'bg-amber-500 text-amber-900',
    'admin'    => 'bg-blue-500 text-blue-900',
    'customer' => 'bg-green-500 text-green-900',
];
@endphp

@section('content')
<div class="space-y-6">
    {{-- Header --}}
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3">
        <div>
            <div class="flex items-center gap-2 mb-1">
                <h1 class="text-2xl font-bold text-white">Audit Log</h1>
                <span class="px-2 py-0.5 text-[10px] font-bold text-yellow-500 bg-yellow-500/10 rounded-full uppercase tracking-wider border border-yellow-500/20">Owner Only</span>
            </div>
            <p class="text-sm text-gray-400">Jejak aktivitas seluruh sistem ELVO — lacak setiap perubahan.</p>
        </div>
    </div>

    {{-- Stats Cards --}}
    <div class="grid grid-cols-2 md:grid-cols-4 gap-3">
        <div class="p-4 bg-elvo-surface rounded-xl border border-white/[0.06] shadow-[0_0_20px_rgba(0,0,0,0.15)]">
            <div class="flex items-center justify-between mb-1">
                <span class="text-[10px] font-bold text-gray-500 uppercase tracking-wider">Hari Ini</span>
                <div class="w-7 h-7 rounded-lg bg-blue-500/10 flex items-center justify-center">
                    <svg class="w-3.5 h-3.5 text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                </div>
            </div>
            <p class="text-xl font-bold text-white">{{ number_format($todayCount) }}</p>
        </div>
        <div class="p-4 bg-elvo-surface rounded-xl border border-white/[0.06] shadow-[0_0_20px_rgba(0,0,0,0.15)]">
            <div class="flex items-center justify-between mb-1">
                <span class="text-[10px] font-bold text-gray-500 uppercase tracking-wider">Minggu Ini</span>
                <div class="w-7 h-7 rounded-lg bg-emerald-500/10 flex items-center justify-center">
                    <svg class="w-3.5 h-3.5 text-emerald-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/></svg>
                </div>
            </div>
            <p class="text-xl font-bold text-white">{{ number_format($weekCount) }}</p>
        </div>
        <div class="p-4 bg-elvo-surface rounded-xl border border-white/[0.06] shadow-[0_0_20px_rgba(0,0,0,0.15)]">
            <div class="flex items-center justify-between mb-1">
                <span class="text-[10px] font-bold text-gray-500 uppercase tracking-wider">Bulan Ini</span>
                <div class="w-7 h-7 rounded-lg bg-purple-500/10 flex items-center justify-center">
                    <svg class="w-3.5 h-3.5 text-purple-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                </div>
            </div>
            <p class="text-xl font-bold text-white">{{ number_format($monthCount) }}</p>
        </div>
        <div class="p-4 bg-elvo-surface rounded-xl border border-white/[0.06] shadow-[0_0_20px_rgba(0,0,0,0.15)]">
            <div class="flex items-center justify-between mb-1">
                <span class="text-[10px] font-bold text-gray-500 uppercase tracking-wider">Total Log</span>
                <div class="w-7 h-7 rounded-lg bg-amber-500/10 flex items-center justify-center">
                    <svg class="w-3.5 h-3.5 text-amber-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"/></svg>
                </div>
            </div>
            <p class="text-xl font-bold text-white">{{ number_format($activities->total()) }}</p>
        </div>
    </div>

    {{-- Filter Section --}}
    <div class="p-5 bg-elvo-surface rounded-xl border border-white/[0.06] shadow-[0_0_20px_rgba(0,0,0,0.15)]">
        <form method="GET" class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-5 gap-3 items-end">
            <div class="lg:col-span-2">
                <label class="block text-[10px] font-bold text-gray-500 uppercase tracking-widest mb-1.5">Pencarian</label>
                <div class="relative">
                    <svg class="absolute left-3 top-1/2 -translate-y-1/2 w-4 h-4 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
                    <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari user atau deskripsi..."
                        class="w-full bg-elvo-bg border border-white/[0.06] rounded-xl pl-10 pr-4 py-2.5 text-sm text-white placeholder-gray-600 focus:border-elvo-primary outline-none transition-all">
                </div>
            </div>
            <div>
                <label class="block text-[10px] font-bold text-gray-500 uppercase tracking-widest mb-1.5">Dari</label>
                <input type="date" name="date_from" value="{{ request('date_from') }}"
                    class="w-full bg-elvo-bg border border-white/[0.06] rounded-xl px-4 py-2.5 text-sm text-white focus:border-elvo-primary outline-none transition-all">
            </div>
            <div>
                <label class="block text-[10px] font-bold text-gray-500 uppercase tracking-widest mb-1.5">Sampai</label>
                <input type="date" name="date_to" value="{{ request('date_to') }}"
                    class="w-full bg-elvo-bg border border-white/[0.06] rounded-xl px-4 py-2.5 text-sm text-white focus:border-elvo-primary outline-none transition-all">
            </div>
            <div>
                <label class="block text-[10px] font-bold text-gray-500 uppercase tracking-widest mb-1.5">Jenis Aksi</label>
                <select name="action"
                    class="w-full bg-elvo-bg border border-white/[0.06] rounded-xl px-4 py-2.5 text-sm text-white focus:border-elvo-primary outline-none transition-all appearance-none">
                    <option value="">Semua Aksi</option>
                    @foreach($actionTypes as $type)
                        <option value="{{ $type }}" {{ request('action') == $type ? 'selected' : '' }}>
                            {{ $actionMeta[$type]['label'] ?? $type }}
                        </option>
                    @endforeach
                </select>
            </div>
            <div class="flex gap-2 pt-1 lg:col-span-5">
                <button type="submit"
                    class="px-6 py-2.5 btn-primary text-xs font-black uppercase tracking-widest rounded-xl">
                    <span class="flex items-center gap-2">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z"/></svg>
                        Filter
                    </span>
                </button>
                @if(request()->anyFilled(['search', 'date_from', 'date_to', 'action']))
                <a href="{{ route('admin.owner.audit-log') }}"
                    class="px-6 py-2.5 btn-ghost text-xs font-black uppercase tracking-widest rounded-xl text-gray-400 hover:text-white transition-all">
                    Reset
                </a>
                @endif
            </div>
        </form>
    </div>

    {{-- Table --}}
    <div class="bg-elvo-surface rounded-xl border border-white/[0.06] shadow-[0_0_25px_rgba(0,0,0,0.2)] overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left">
                <thead>
                    <tr class="bg-white/[0.02] border-b border-white/[0.06]">
                        <th class="px-5 py-4 text-[10px] font-black text-gray-500 uppercase tracking-widest w-[200px]">User</th>
                        <th class="px-5 py-4 text-[10px] font-black text-gray-500 uppercase tracking-widest w-[140px]">Aksi</th>
                        <th class="px-5 py-4 text-[10px] font-black text-gray-500 uppercase tracking-widest">Deskripsi</th>
                        <th class="px-5 py-4 text-[10px] font-black text-gray-500 uppercase tracking-widest w-[130px] text-right">Waktu</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-white/5">
                    @forelse($activities as $activity)
                    @php
                        $meta = $actionMeta[$activity->action] ?? ['label' => $activity->action, 'icon' => 'M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z', 'color' => 'gray'];
                        $colorClass = $colorMap[$meta['color']] ?? $colorMap['gray'];
                        $user = $activity->user;
                        $roleClass = $roleColors[$user->role ?? 'customer'] ?? 'bg-gray-500 text-gray-900';
                        $initials = $user ? strtoupper(substr($user->name, 0, 1)) : '?';
                    @endphp
                    <tr class="hover:bg-white/[0.01] transition-colors group">
                        <td class="px-5 py-3.5">
                            <div class="flex items-center gap-3">
                                <div class="relative shrink-0">
                                    <div class="w-9 h-9 rounded-xl {{ $roleClass }} flex items-center justify-center text-sm font-bold shadow-lg">
                                        {{ $initials }}
                                    </div>
                                    @if($user && $user->role === 'owner')
                                    <div class="absolute -top-1 -right-1 w-3.5 h-3.5 bg-amber-500 rounded-full border-2 border-elvo-surface flex items-center justify-center">
                                        <svg class="w-2 h-2 text-amber-900" fill="currentColor" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/></svg>
                                    </div>
                                    @endif
                                </div>
                                <div class="min-w-0">
                                    <p class="text-sm font-semibold text-white truncate">{{ $user->name ?? 'System' }}</p>
                                    <p class="text-[10px] text-gray-500 font-mono truncate">{{ $user->email ?? 'system@elvo' }}</p>
                                </div>
                            </div>
                        </td>
                        <td class="px-5 py-3.5">
                            <span class="inline-flex items-center gap-1.5 px-2.5 py-1 text-[10px] font-bold rounded-lg border {{ $colorClass }}">
                                <svg class="w-3.5 h-3.5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="{{ $meta['icon'] }}"/>
                                </svg>
                                {{ $meta['label'] }}
                            </span>
                        </td>
                        <td class="px-5 py-3.5">
                            <p class="text-sm text-gray-300 max-w-lg truncate group-hover:text-white transition-colors">{{ $activity->description }}</p>
                            @if($activity->model_type)
                            <span class="text-[9px] text-gray-600 font-mono">{{ $activity->model_type }}{{ $activity->model_id ? ' #'.$activity->model_id : '' }}</span>
                            @endif
                        </td>
                        <td class="px-5 py-3.5 text-right">
                            <div class="flex flex-col items-end">
                                <span class="text-sm text-gray-300 font-medium whitespace-nowrap" title="{{ $activity->created_at->format('d/m/Y H:i:s') }}">
                                    {{ $activity->created_at->diffForHumans() }}
                                </span>
                                <span class="text-[10px] text-gray-600 font-mono whitespace-nowrap">
                                    {{ $activity->created_at->format('d/m/Y H:i') }}
                                </span>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="4" class="px-5 py-20 text-center">
                            <div class="flex flex-col items-center">
                                <div class="w-16 h-16 bg-white/5 rounded-2xl flex items-center justify-center mb-4">
                                    <svg class="w-8 h-8 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/></svg>
                                </div>
                                <p class="text-gray-500 font-semibold mb-1">Tidak ada aktivitas ditemukan</p>
                                <p class="text-xs text-gray-600">Coba ubah filter atau reset pencarian</p>
                            </div>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        {{-- Footer with pagination --}}
        @if($activities->hasPages() || $activities->total() > 0)
        <div class="flex items-center justify-between px-5 py-4 border-t border-white/[0.06]">
            <p class="text-xs text-gray-500">
                Menampilkan <span class="font-semibold text-gray-400">{{ $activities->firstItem() ?? 0 }}</span>
                – <span class="font-semibold text-gray-400">{{ $activities->lastItem() ?? 0 }}</span>
                dari <span class="font-semibold text-gray-400">{{ number_format($activities->total()) }}</span> log
            </p>
            <div class="flex items-center gap-1">
                {{ $activities->links() }}
            </div>
        </div>
        @endif
    </div>
</div>
@endsection

@push('scripts')
<style>
.pagination .page-link {
    @apply px-3 py-1.5 text-xs font-bold rounded-lg bg-white/5 text-gray-400 hover:bg-elvo-primary/20 hover:text-white transition-all border border-white/5;
}
.pagination .page-item.active .page-link {
    @apply bg-elvo-primary/20 text-elvo-primary border-elvo-primary/30;
}
.pagination .page-item.disabled .page-link {
    @apply opacity-30 cursor-not-allowed;
}
</style>
@endpush
