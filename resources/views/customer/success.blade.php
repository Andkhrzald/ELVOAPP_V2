@extends('layouts.customer')

@section('content')
<section class="min-h-screen flex items-center justify-center px-8 bg-[#0a0a0a]">
    <div class="text-center" data-aos="zoom-in">
        <!-- Icon Success -->
        <div class="mb-8 flex justify-center">
            <div class="w-24 h-24 bg-white rounded-full flex items-center justify-center shadow-[0_0_50px_rgba(255,255,255,0.2)]">
                <svg xmlns="http://www.w3.org/2000/svg" width="48" height="48" viewBox="0 0 24 24" fill="none" stroke="black" stroke-width="3" stroke-linecap="round" stroke-linejoin="round">
                    <polyline points="20 6 9 17 4 12" />
                </svg>
            </div>
        </div>

        <!-- Text Content -->
        <h1 class="text-5xl md:text-7xl font-black italic uppercase tracking-tighter text-white mb-4">
            Payment <span class="text-gray-700">Success.</span>
        </h1>
        <p class="text-gray-500 text-[10px] md:text-xs tracking-[0.5em] uppercase font-bold mb-12">
            Your order has been placed successfully
        </p>

        <!-- Action Button -->
        <div class="space-y-6">
            <a href="{{ url('/') }}" class="inline-block bg-white text-black px-12 py-4 rounded-full text-[10px] font-black uppercase tracking-[0.4em] hover:bg-gray-200 transition-all italic">
                Back to Home
            </a>
            <br>
            <p class="text-[9px] text-gray-700 uppercase tracking-widest mt-8">
                Order ID: #ELVO-{{ rand(1000, 9999) }}
            </p>
        </div>
    </div>
</section>
@endsection