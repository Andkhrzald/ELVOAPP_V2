@extends('layouts.app')

@section('content')
<div class="mb-6">
    <div class="flex items-center gap-2 mb-1">
        <h1 class="text-2xl font-bold text-white">Owner Dashboard</h1>
        <span class="px-2 py-0.5 text-[10px] font-bold text-yellow-500 bg-yellow-500/10 rounded-full uppercase tracking-wider">Owner Only</span>
    </div>
    <p class="text-sm text-gray-400">Panel khusus Owner — Manajemen akun & pengawasan sistem.</p>
</div>

<div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4 mb-6">
    <div class="p-5 bg-[#1a1a1a] rounded-xl border border-white/5 shadow-sm">
        <div class="flex items-center justify-between mb-2">
            <span class="text-xs font-bold text-gray-500 uppercase">Total Admin</span>
            <div class="p-2 bg-blue-500/10 rounded-lg text-blue-500">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"/></svg>
            </div>
        </div>
        <p class="text-2xl font-bold text-white">{{ $totalAdmins }}</p>
        <span class="text-xs text-blue-500 font-medium">Akun admin terdaftar</span>
    </div>

    <div class="p-5 bg-[#1a1a1a] rounded-xl border border-white/5 shadow-sm">
        <div class="flex items-center justify-between mb-2">
            <span class="text-xs font-bold text-gray-500 uppercase">Total Owner</span>
            <div class="p-2 bg-yellow-500/10 rounded-lg text-yellow-500">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/></svg>
            </div>
        </div>
        <p class="text-2xl font-bold text-white">{{ $totalOwners }}</p>
        <span class="text-xs text-yellow-500 font-medium">Pemilik toko</span>
    </div>

    <div class="p-5 bg-[#1a1a1a] rounded-xl border border-white/5 shadow-sm">
        <div class="flex items-center justify-between mb-2">
            <span class="text-xs font-bold text-gray-500 uppercase">Total Pesanan</span>
            <div class="p-2 bg-purple-500/10 rounded-lg text-purple-500">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"/></svg>
            </div>
        </div>
        <p class="text-2xl font-bold text-white">{{ number_format($totalOrders) }}</p>
        <span class="text-xs text-purple-500 font-medium">Semua status</span>
    </div>

    <div class="p-5 bg-[#1a1a1a] rounded-xl border border-white/5 shadow-sm">
        <div class="flex items-center justify-between mb-2">
            <span class="text-xs font-bold text-gray-500 uppercase">Total Revenue</span>
            <div class="p-2 bg-green-500/10 rounded-lg text-green-500">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
            </div>
        </div>
        <p class="text-2xl font-bold text-white">Rp {{ number_format($totalRevenue, 0, ',', '.') }}</p>
        <span class="text-xs text-green-500 font-medium">Selesai & dikirim</span>
    </div>
</div>

<div class="grid grid-cols-1 lg:grid-cols-3 gap-6 mb-6">
    <div class="lg:col-span-2 p-6 bg-[#1a1a1a] rounded-xl border border-white/5 shadow-sm">
        <div class="flex items-center justify-between mb-4">
            <h3 class="font-bold text-white text-lg">Manajemen Akses</h3>
            <a href="{{ route('admin.owner.manage-admins') }}" class="text-sm text-blue-400 hover:text-blue-300 transition">Kelola Admin →</a>
        </div>
        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
            <a href="{{ route('admin.owner.manage-admins') }}" class="p-4 bg-blue-500/5 rounded-xl border border-blue-500/10 hover:bg-blue-500/10 transition group">
                <div class="flex items-center gap-3 mb-2">
                    <div class="p-2 bg-blue-500/10 rounded-lg text-blue-500 group-hover:scale-110 transition">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z"/></svg>
                    </div>
                    <div>
                        <p class="font-semibold text-white">Tambah Admin Baru</p>
                        <p class="text-xs text-gray-500">Buat akun untuk staff admin</p>
                    </div>
                </div>
            </a>
            <a href="{{ route('admin.owner.audit-log') }}" class="p-4 bg-purple-500/5 rounded-xl border border-purple-500/10 hover:bg-purple-500/10 transition group">
                <div class="flex items-center gap-3 mb-2">
                    <div class="p-2 bg-purple-500/10 rounded-lg text-purple-500 group-hover:scale-110 transition">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"/></svg>
                    </div>
                    <div>
                        <p class="font-semibold text-white">Audit Log</p>
                        <p class="text-xs text-gray-500">Semua aktivitas sistem</p>
                    </div>
                </div>
            </a>
        </div>
    </div>

    <div class="p-6 bg-[#1a1a1a] rounded-xl border border-white/5 shadow-sm">
        <h3 class="font-bold text-white mb-4">Aktivitas Terkini (20)</h3>
        <div class="space-y-3 max-h-[400px] overflow-y-auto">
            @forelse($activities as $activity)
            <div class="flex gap-3">
                <div class="w-2 h-2 mt-2 rounded-full 
                    @if(str_contains($activity->action, 'created')) bg-blue-500
                    @elseif(str_contains($activity->action, 'deleted')) bg-red-500
                    @elseif(str_contains($activity->action, 'updated')) bg-green-500
                    @else bg-gray-500
                    @endif
                "></div>
                <div>
                    <p class="text-sm text-gray-200">{{ $activity->description }}</p>
                    <div class="flex items-center gap-2 text-xs text-gray-500">
                        <span>{{ $activity->user?->name ?? 'System' }}</span>
                        <span>•</span>
                        <span>{{ $activity->created_at->diffForHumans() }}</span>
                    </div>
                </div>
            </div>
            @empty
            <p class="text-sm text-gray-500 italic">Belum ada aktivitas.</p>
            @endforelse
        </div>
        <a href="{{ route('admin.owner.audit-log') }}" class="block w-full mt-4 py-2 text-sm font-semibold text-blue-400 bg-blue-500/10 rounded-lg hover:bg-blue-500 hover:text-white transition text-center">
            Lihat Semua Log
        </a>
    </div>
</div>
@endsection
