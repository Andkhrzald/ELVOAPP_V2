@extends('layouts.app')

@section('content')
<div class="mb-6">
    <div class="flex items-center gap-2 mb-1">
        <h1 class="text-2xl font-bold animate-fade-up animate-fade-up text-white">Audit Log</h1>
        <span class="px-2 py-0.5 text-[10px] font-bold text-yellow-500 bg-yellow-500/10 rounded-full uppercase tracking-wider">Owner Only</span>
    </div>
    <p class="text-sm text-gray-400">Semua aktivitas yang terjadi di sistem ELVO.</p>
</div>

<div class="p-6 bg-elvo-surface rounded-xl border border-white/[0.06] shadow-[0_0_25px_rgba(0,0,0,0.2)] card-hover">
    <div class="overflow-x-auto">
        <table class="w-full text-sm text-left">
            <thead>
                <tr class="text-gray-500 border-b border-white/10">
                    <th class="pb-3 font-semibold">Waktu</th>
                    <th class="pb-3 font-semibold">User</th>
                    <th class="pb-3 font-semibold">Aksi</th>
                    <th class="pb-3 font-semibold">Deskripsi</th>
                </tr>
            </thead>
            <tbody>
                @forelse($activities as $activity)
                <tr class="border-b border-white/[0.06] hover:bg-white/5">
                    <td class="py-3 text-gray-400 whitespace-nowrap">{{ $activity->created_at->format('d/m/Y H:i') }}</td>
                    <td class="py-3">
                        <div class="flex items-center gap-2">
                            <div class="w-6 h-6 rounded-full bg-gray-700 flex items-center justify-center text-[10px] text-white font-bold">
                                {{ strtoupper(substr($activity->user?->name ?? 'S', 0, 1)) }}
                            </div>
                            <span class="text-white">{{ $activity->user?->name ?? 'System' }}</span>
                        </div>
                    </td>
                    <td class="py-3">
                        <span class="px-2 py-0.5 text-[10px] font-bold rounded-full
                            @if(str_contains($activity->action, 'created')) bg-elvo-primary/10 text-[#8b7df2]
                            @elseif(str_contains($activity->action, 'deleted')) bg-red-500/10 text-red-400
                            @elseif(str_contains($activity->action, 'updated') || str_contains($activity->action, 'shipped') || str_contains($activity->action, 'completed')) bg-green-500/10 text-green-400
                            @else bg-gray-500/10 text-gray-400
                            @endif
                        ">{{ $activity->action }}</span>
                    </td>
                    <td class="py-3 text-gray-300">{{ $activity->description }}</td>
                </tr>
                @empty
                <tr>
                    <td colspan="4" class="py-6 text-center text-gray-500 italic">Belum ada aktivitas.</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    <div class="mt-4">
        {{ $activities->links() }}
    </div>
</div>
@endsection
