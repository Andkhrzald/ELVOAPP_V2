@extends('layouts.customer')

@section('content')
<!-- Header Section -->
<section class="pt-32 pb-10 px-8 text-center" data-aos="fade-down">
    <p class="text-[10px] font-black uppercase tracking-[0.5em] text-gray-500 mb-4">Official Collection</p>
    <h2 class="text-6xl md:text-8xl font-black italic uppercase tracking-tighter text-white">
        Our <span class="text-transparent" style="-webkit-text-stroke: 1px white;">Collection.</span>
    </h2>
</section>

<!-- Dashboard Produk -->
<section class="pb-32 px-8">
    <div class="container mx-auto">
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-x-8 gap-y-16">

            <!-- Loop Dummy Produk -->
            @for ($i = 1; $i <= 6; $i++)
                <div class="group cursor-pointer" data-aos="fade-up" data-aos-delay="{{ $i * 100 }}">
                <div class="relative aspect-[3/4] bg-[#0f0f0f] rounded-[30px] overflow-hidden mb-6 border border-white/5 group-hover:border-white/20 transition-all duration-500">
                    <!-- Placeholder Produk (Bisa lo ganti file asli nanti) -->
                    <div class="w-full h-full flex items-center justify-center">
                        <span class="text-white/5 text-9xl font-black italic uppercase">ELVO</span>
                    </div>

                    <!-- Overlay Button -->
                    <div class="absolute inset-0 bg-black/60 opacity-0 group-hover:opacity-100 transition-opacity duration-500 flex items-center justify-center">
                        <button class="bg-white text-black px-10 py-4 rounded-full text-[10px] font-black uppercase tracking-widest transform translate-y-8 group-hover:translate-y-0 transition-all duration-500 shadow-2xl">
                            Add to Cart
                        </button>
                    </div>
                </div>

                <div class="space-y-2">
                    <div class="flex justify-between items-start">
                        <h3 class="font-bold uppercase tracking-tight text-xl text-white leading-none">
                            Essential Tee <br>
                            <span class="text-gray-500 text-xs font-medium tracking-widest uppercase italic">V.{{ $i }} Series</span>
                        </h3>
                        <span class="font-black text-lg text-white">IDR 249K</span>
                    </div>
                    <p class="text-[9px] font-bold text-gray-600 uppercase tracking-[0.3em]">Premium Quality Experience</p>
                </div>
        </div>
        @endfor

    </div>
    </div>
</section>
@endsection