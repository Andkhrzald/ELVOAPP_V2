@extends('layouts.customer')

@section('content')
<div class="min-h-screen text-white pt-24 pb-12 px-6">
    <div class="max-w-4xl mx-auto">
        <h1 class="text-[20px] font-black uppercase tracking-[0.3em] mb-12">
            Riwayat Pesanan
        </h1>

        {{-- INI BAGIAN @IF YANG HARUS ADA SEBELUM @ELSE --}}
        @if($orders->isEmpty())
        <div class="flex flex-col items-center justify-center py-24 px-6 border border-white/5 rounded-3xl bg-white/[0.02] text-center" data-aos="zoom-in">
            <div class="relative mb-8 text-gray-700 animate-bounce">
                <svg class="w-32 h-32" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1">
                    <circle cx="8" cy="21" r="1" />
                    <circle cx="19" cy="21" r="1" />
                    <path d="M2.05 2.05h2l2.66 12.42a2 2 0 0 0 2 1.58h9.78a2 2 0 0 0 1.95-1.57l1.65-7.43H5.12" />
                </svg>
            </div>
            <h2 class="text-[18px] font-black uppercase tracking-[0.4em] mb-3">Keranjang Masih Sepi</h2>
            <p class="text-gray-500 text-[10px] uppercase tracking-widest mb-8">Lo belum checkout koleksi ELVOAPP nih,.</p>
            <a href="{{ route('shop.index') }}" class="px-8 py-3 bg-white text-black text-[10px] font-black uppercase tracking-widest rounded-full hover:bg-gray-200 transition">
                Gas Belanja Sekarang
            </a>
        </div>

        @else {{-- SEKARANG @ELSE BARU BOLEH MUNCUL --}}
        <div class="space-y-6">
            @foreach($orders as $order)
            <div class="bg-white/[0.03] border border-white/10 rounded-xl p-6" data-aos="fade-up">
                <div class="flex justify-between items-center mb-4">
                    <span class="text-[10px] font-bold uppercase tracking-widest text-gray-500">Order #{{ $order->id }}</span>
                    <span class="text-[10px] font-bold uppercase text-white">{{ $order->created_at->format('d M Y') }}</span>
                </div>

                @foreach($order->products as $product)
                <div class="flex items-center gap-4 py-2">
                    <div class="w-12 h-12 bg-white/5 rounded-lg overflow-hidden">
                        <img src="{{ asset('storage/' . $product->image) }}" class="w-full h-full object-cover">
                    </div>
                    <div class="flex-1">
                        <p class="text-[11px] font-black uppercase">{{ $product->name }}</p>
                        <p class="text-[9px] text-gray-500">Rp {{ number_format($product->price, 0, ',', '.') }}</p>
                    </div>
                </div>
                @endforeach

                <div class="mt-4 pt-4 border-t border-white/5 flex justify-between items-center">
                    <p class="text-[10px] text-gray-500 uppercase">Total</p>
                    <p class="text-[14px] font-black text-white">Rp {{ number_format($order->total_price, 0, ',', '.') }}</p>
                </div>
            </div>
            @endforeach
        </div>
        @endif {{-- JANGAN LUPA DITUTUP --}}
    </div>
</div>
@endsection