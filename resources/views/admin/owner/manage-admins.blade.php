@extends('layouts.app')

@section('content')
<div class="mb-6">
    <div class="flex items-center gap-2 mb-1">
        <h1 class="text-2xl font-bold text-white">Manajemen Admin</h1>
        <span class="px-2 py-0.5 text-[10px] font-bold text-yellow-500 bg-yellow-500/10 rounded-full uppercase tracking-wider">Owner Only</span>
    </div>
    <p class="text-sm text-gray-400">Kelola akun admin & owner yang memiliki akses ke dashboard.</p>
</div>

@if(session('success'))
<div class="mb-4 p-4 bg-green-500/10 border border-green-500/20 rounded-lg text-green-400 text-sm">
    {{ session('success') }}
</div>
@endif

@if(session('error'))
<div class="mb-4 p-4 bg-red-500/10 border border-red-500/20 rounded-lg text-red-400 text-sm">
    {{ session('error') }}
</div>
@endif

{{-- Form Tambah Admin --}}
<div class="p-6 bg-[#1a1a1a] rounded-xl border border-white/5 shadow-sm mb-6">
    <h3 class="font-bold text-white mb-4">Tambah Akun Baru</h3>
    <form action="{{ route('admin.owner.manage-admins.store') }}" method="POST" class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
        @csrf
        <div>
            <label class="block text-xs font-semibold text-gray-400 mb-1">Nama Lengkap</label>
            <input type="text" name="name" required
                class="w-full px-3 py-2 text-sm border border-white/10 rounded-lg bg-[#252525] text-white focus:ring-blue-500 focus:border-blue-500"
                placeholder="Nama admin">
        </div>
        <div>
            <label class="block text-xs font-semibold text-gray-400 mb-1">Email</label>
            <input type="email" name="email" required
                class="w-full px-3 py-2 text-sm border border-white/10 rounded-lg bg-[#252525] text-white focus:ring-blue-500 focus:border-blue-500"
                placeholder="admin@example.com">
        </div>
        <div>
            <label class="block text-xs font-semibold text-gray-400 mb-1">Password</label>
            <input type="password" name="password" required minlength="8"
                class="w-full px-3 py-2 text-sm border border-white/10 rounded-lg bg-[#252525] text-white focus:ring-blue-500 focus:border-blue-500"
                placeholder="Min. 8 karakter">
        </div>
        <div>
            <label class="block text-xs font-semibold text-gray-400 mb-1">Role</label>
            <select name="role" required
                class="w-full px-3 py-2 text-sm border border-white/10 rounded-lg bg-[#252525] text-white focus:ring-blue-500 focus:border-blue-500">
                <option value="admin">Admin</option>
                <option value="owner">Owner</option>
            </select>
        </div>
        <div>
            <label class="block text-xs font-semibold text-gray-400 mb-1">No. HP</label>
            <input type="text" name="phone"
                class="w-full px-3 py-2 text-sm border border-white/10 rounded-lg bg-[#252525] text-white focus:ring-blue-500 focus:border-blue-500"
                placeholder="08xxxx">
        </div>
        <div class="flex items-end">
            <button type="submit"
                class="w-full px-4 py-2 text-sm font-semibold text-white bg-blue-600 rounded-lg hover:bg-blue-700 transition">
                + Tambah Akun
            </button>
        </div>
    </form>
</div>

{{-- Daftar Admin --}}
<div class="p-6 bg-[#1a1a1a] rounded-xl border border-white/5 shadow-sm">
    <h3 class="font-bold text-white mb-4">Daftar Admin & Owner</h3>
    <div class="overflow-x-auto">
        <table class="w-full text-sm text-left">
            <thead>
                <tr class="text-gray-500 border-b border-white/10">
                    <th class="pb-3 font-semibold">Nama</th>
                    <th class="pb-3 font-semibold">Email</th>
                    <th class="pb-3 font-semibold">Role</th>
                    <th class="pb-3 font-semibold">Dibuat</th>
                    <th class="pb-3 font-semibold">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse($admins as $admin)
                <tr class="border-b border-white/5 hover:bg-white/5">
                    <td class="py-3 text-white">
                        <div class="flex items-center gap-2">
                            <div class="w-7 h-7 rounded-full bg-blue-600 flex items-center justify-center text-xs text-white font-bold">
                                {{ strtoupper(substr($admin->name, 0, 1)) }}
                            </div>
                            {{ $admin->name }}
                            @if($admin->id === auth()->id())
                            <span class="text-[10px] text-blue-400 font-semibold">(Anda)</span>
                            @endif
                        </div>
                    </td>
                    <td class="py-3 text-gray-400">{{ $admin->email }}</td>
                    <td class="py-3">
                        @if($admin->role === 'owner')
                        <span class="px-2 py-0.5 text-[10px] font-bold text-yellow-500 bg-yellow-500/10 rounded-full">OWNER</span>
                        @else
                        <span class="px-2 py-0.5 text-[10px] font-bold text-blue-500 bg-blue-500/10 rounded-full">ADMIN</span>
                        @endif
                    </td>
                    <td class="py-3 text-gray-400">{{ $admin->created_at->format('d/m/Y') }}</td>
                    <td class="py-3">
                        @if($admin->id !== auth()->id())
                        <form action="{{ route('admin.owner.manage-admins.destroy', $admin->id) }}" method="POST"
                            onsubmit="return confirm('Yakin hapus akun {{ $admin->name }}?')">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="text-red-400 hover:text-red-300 text-xs font-semibold">Hapus</button>
                        </form>
                        @else
                        <span class="text-gray-600 text-xs">—</span>
                        @endif
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="5" class="py-6 text-center text-gray-500 italic">Tidak ada admin.</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    <div class="mt-4">
        {{ $admins->links() }}
    </div>
</div>
@endsection
