@extends('layouts.customer')

@section('content')
<section class="min-h-screen flex items-center justify-center px-8 pt-28 pb-20 bg-[#0a0a0a]">
    <div class="text-center" data-aos="zoom-in">
        <div class="mb-8 flex justify-center">
            <div class="w-20 h-20 bg-white rounded-full flex items-center justify-center shadow-[0_0_60px_rgba(255,255,255,0.15)]">
                <svg xmlns="http://www.w3.org/2000/svg" width="40" height="40" viewBox="0 0 24 24" fill="none" stroke="black" stroke-width="3" stroke-linecap="round" stroke-linejoin="round">
                    <polyline points="20 6 9 17 4 12" />
                </svg>
            </div>
        </div>
        <h1 class="text-4xl md:text-5xl font-black italic uppercase tracking-tighter text-white mb-2">
            Payment <span class="text-gray-700">Success.</span>
        </h1>
        <p class="text-gray-500 text-[10px] tracking-[0.5em] uppercase font-bold mb-8">Your order has been placed</p>

        @if($order)
        <div class="inline-block bg-white/[0.03] border border-white/[0.08] rounded-2xl px-8 py-5 mb-8">
            <p class="text-[9px] text-gray-500 uppercase tracking-widest mb-1">Order Number</p>
            <p class="text-xl font-black text-white tracking-widest">{{ $order->order_number }}</p>
        </div>
        @endif

        <div class="space-y-4">
            <a href="{{ route('history.index') }}" class="inline-block bg-white text-black px-10 py-4 rounded-full text-[10px] font-black uppercase tracking-[0.4em] hover:bg-gray-200 transition-all italic">
                Lihat Pesanan Saya
            </a>
            <br>
            <a href="{{ route('shop.index') }}" class="inline-block text-gray-500 text-[10px] uppercase tracking-widest hover:text-white transition mt-4">
                Lanjut Belanja →
            </a>
        </div>
    </div>
</section>
@endsection
