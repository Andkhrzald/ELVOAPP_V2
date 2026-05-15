@extends('layouts.app')

@section('content')
<div class="min-h-screen bg-[#0f0f0f] text-gray-300 pb-12">

    {{-- Back + Header --}}
    <div class="max-w-7xl mx-auto px-4 lg:px-0 pt-10 mb-8">
        <a href="{{ route('admin.pelanggan') }}" class="text-xs text-blue-500 hover:text-blue-400 font-bold uppercase tracking-widest mb-4 inline-block">← Kembali</a>

        <div class="flex items-center gap-6">
            <div class="w-16 h-16 rounded-2xl bg-blue-500/10 flex items-center justify-center text-blue-500 text-2xl font-black">
                {{ strtoupper(substr($customer->name, 0, 1)) }}
            </div>
            <div>
                <h1 class="text-2xl font-bold text-white">{{ $customer->name }}</h1>
                <p class="text-sm text-gray-500">{{ $customer->email }} · {{ $customer->phone ?? 'No phone' }}</p>
                <p class="text-xs text-gray-600">Bergabung {{ $customer->created_at->format('d M Y') }}</p>
            </div>
        </div>
    </div>

    {{-- Stats --}}
    <div class="max-w-7xl mx-auto grid grid-cols-1 md:grid-cols-3 gap-6 mb-8 px-4 lg:px-0">
        <div class="p-6 bg-[#1a1a1a] rounded-2xl border border-white/5">
            <p class="text-[10px] font-black text-gray-500 uppercase tracking-widest">Total Pesanan</p>
            <p class="text-3xl font-black text-white mt-2">{{ $customer->orders_count }}</p>
        </div>
        <div class="p-6 bg-[#1a1a1a] rounded-2xl border border-white/5">
            <p class="text-[10px] font-black text-gray-500 uppercase tracking-widest">Total Belanja</p>
            <p class="text-3xl font-black text-white mt-2">Rp {{ number_format($customer->total_spent ?? 0, 0, ',', '.') }}</p>
        </div>
        <div class="p-6 bg-[#1a1a1a] rounded-2xl border border-white/5">
            <p class="text-[10px] font-black text-gray-500 uppercase tracking-widest">Alamat</p>
            <p class="text-sm text-gray-300 mt-2">{{ $customer->address ?? 'Belum diisi' }}</p>
        </div>
    </div>

    {{-- Order History --}}
    <div class="max-w-7xl mx-auto px-4 lg:px-0">
        <h2 class="text-lg font-bold text-white mb-4">Riwayat Pesanan</h2>
        <div class="space-y-4">
            @forelse($orders as $order)
            <div class="bg-[#1a1a1a] rounded-2xl border border-white/5 p-6">
                <div class="flex flex-col md:flex-row md:items-center justify-between gap-3 mb-4">
                    <div>
                        <span class="text-xs font-mono text-gray-500">#{{ $order->order_number }}</span>
                        <span class="text-xs text-gray-600 ml-2">{{ $order->created_at->format('d M Y H:i') }}</span>
                    </div>
                    @php
                    $statusBadgeClasses = [
                        'pending'      => 'bg-orange-500/10 text-orange-500',
                        'proses'       => 'bg-blue-500/10 text-blue-500',
                        'dikirim'      => 'bg-purple-500/10 text-purple-500',
                        'selesai'      => 'bg-green-500/10 text-green-500',
                        'batal'        => 'bg-red-500/10 text-red-500',
                        'minta_batal'  => 'bg-yellow-500/10 text-yellow-500',
                        'minta_refund' => 'bg-amber-500/10 text-amber-500',
                        'refund'       => 'bg-pink-500/10 text-pink-500',
                    ];
                    $badgeClass = $statusBadgeClasses[$order->status] ?? 'bg-gray-500/10 text-gray-500';
                    @endphp
                    <span class="px-3 py-1 {{ $badgeClass }} rounded-full text-[10px] font-bold uppercase">{{ str_replace('_', ' ', $order->status) }}</span>
                </div>
                @foreach($order->items as $item)
                <p class="text-sm text-gray-400">{{ $item->quantity }}x {{ $item->product_name }} — <span class="text-white font-bold">Rp {{ number_format($item->subtotal, 0, ',', '.') }}</span></p>
                @endforeach
                <p class="mt-3 pt-3 border-t border-white/5 text-right text-sm font-black text-white">Total: Rp {{ number_format($order->total_price, 0, ',', '.') }}</p>
            </div>
            @empty
            <p class="text-gray-600 font-bold py-8 text-center">Belum ada pesanan dari pelanggan ini.</p>
            @endforelse
        </div>
    </div>
</div>
@endsection
