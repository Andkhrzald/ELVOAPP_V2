@extends('layouts.customer')

@section('content')
<section class="py-24 text-center">
    <h1 class="text-7xl font-extrabold italic uppercase tracking-tighter mb-4">Focus on Flavor.</h1>
    <p class="text-gray-500 tracking-widest uppercase text-[10px]">Premium Quality Experience</p>
</section>

<section id="produk">
    <div class="container mx-auto px-8 pb-32">
        <div class="grid grid-cols-1 md:grid-cols-3 gap-12">

            <div class="group cursor-pointer text-center">
                <div class="aspect-square bg-[#151515] rounded-[30px] mb-6 flex items-center justify-center border border-white/5 group-hover:border-white/20 transition-all duration-500">
                    <span class="text-white/5 text-6xl font-black italic">S</span>
                </div>
                <h3 class="text-sm font-bold uppercase tracking-tight mb-1">Signature Black</h3>
                <p class="text-gray-500 text-[11px]">IDR 185.000</p>
            </div>

            <div class="group cursor-pointer text-center">
                <div class="aspect-square bg-[#151515] rounded-[30px] mb-6 flex items-center justify-center border border-white/5 group-hover:border-white/20 transition-all duration-500">
                    <span class="text-white/5 text-6xl font-black italic">A</span>
                </div>
                <h3 class="text-sm font-bold uppercase tracking-tight mb-1">Arctic Menthol</h3>
                <p class="text-gray-500 text-[11px]">IDR 125.000</p>
            </div>

            <div class="group cursor-pointer text-center">
                <div class="aspect-square bg-[#151515] rounded-[30px] mb-6 flex items-center justify-center border border-white/5 group-hover:border-white/20 transition-all duration-500">
                    <span class="text-white/5 text-6xl font-black italic">E</span>
                </div>
                <h3 class="text-sm font-bold uppercase tracking-tight mb-1">Essential Pod</h3>
                <p class="text-gray-500 text-[11px]">IDR 250.000</p>
            </div>

        </div>
    </div>
</section>
@endsection