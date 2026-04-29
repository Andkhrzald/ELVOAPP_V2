@extends('layouts.customer')

@section('content')
<section class="min-h-[80vh] flex flex-col justify-center items-center text-center px-6">
    <h1 class="text-6xl md:text-8xl font-extrabold italic uppercase tracking-tighter mb-6 leading-none">
        ELVO <span class="text-transparent" style="-webkit-text-stroke: 1px white;">STORE.</span>
    </h1>

    <p class="max-w-xl text-gray-500 text-sm md:text-base tracking-[0.2em] uppercase leading-relaxed mb-10">
        brand local asal jakarta yang di kembangkan oleh mahasiswa bina sarana informatika dengan kualitas terbaik dan harga terjangkau
    </p>

    <a href="#collection" class="border border-white/20 px-10 py-4 rounded-full text-[10px] font-bold tracking-[0.3em] uppercase hover:bg-white hover:text-black transition-all duration-500">
        Explore Collection
    </a>
</section>

<div id="collection" class="container mx-auto px-8 pb-32">
    <div class="grid grid-cols-1 md:grid-cols-3 gap-12">
    </div>
</div>
<section class="py-24 text-center">
    <h1 class="text-7xl font-extrabold italic uppercase tracking-tighter mb-4">New <span></span>Products.</h1>
    <p class="text-gray-500 tracking-widest uppercase text-[10px]">Premium Quality Experience</p>
</section>

<section id="">
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