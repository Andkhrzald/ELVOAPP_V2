@extends('layouts.customer')

@section('content')
<section class="relative min-h-[90vh] flex flex-col justify-center items-center text-center px-6 overflow-hidden">

    <div class="absolute left-10 top-1/2 -translate-y-1/2 flex flex-col space-y-8 items-center z-10 hidden md:flex">
        <a href="#" class="text-gray-600 hover:text-white transition-all duration-300 transform hover:scale-125">
            <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round">
                <rect width="20" height="20" x="2" y="2" rx="5" ry="5" />
                <path d="M16 11.37A4 4 0 1 1 12.63 8 4 4 0 0 1 16 11.37z" />
                <line x1="17.5" y1="6.5" x2="17.51" y2="6.5" />
            </svg>
        </a>
        <a href="#" class="text-gray-600 hover:text-white transition-all duration-300 transform hover:scale-125">
            <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round">
                <path d="M18 2h-3a5 5 0 0 0-5 5v3H7v4h3v8h4v-8h3l1-4h-4V7a1 1 0 0 1 1-1h3z" />
            </svg>
        </a>
        <a href="#" class="text-gray-600 hover:text-white transition-all duration-300 transform hover:scale-125">
            <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round">
                <path d="M21 15a2 2 0 0 1-2 2H7l-4 4V5a2 2 0 0 1 2-2h14a2 2 0 0 1 2 2z" />
            </svg>
        </a>
        <div class="w-[1px] h-24 bg-gradient-to-b from-gray-800 to-transparent"></div>
    </div>

    <h1 class="text-6xl md:text-9xl font-extrabold italic uppercase tracking-tighter mb-6 leading-none" data-aos="zoom-out-up">
        ELVO <span class="text-transparent" style="-webkit-text-stroke: 1px white;">STORE.</span>
    </h1>

    <div class="max-w-2xl" data-aos="fade-up" data-aos-delay="300">
        <p class="text-gray-400 text-xs md:text-sm tracking-[0.3em] uppercase font-medium mb-4">Local Brand Origin Jakarta</p>
        <p class="text-gray-500 text-sm md:text-base leading-relaxed italic opacity-80">
            "Dikembangkan oleh mahasiswa Bina Sarana Informatika dengan kualitas terbaik dan harga terjangkau."
        </p>
    </div>

    <div class="mt-12" data-aos="fade-up" data-aos-delay="500">
        <a href="#produk" class="group relative inline-flex items-center justify-center px-12 py-4 overflow-hidden font-bold border border-white/10 rounded-full transition-all duration-300 hover:border-white">
            <span class="text-[10px] tracking-[0.4em] uppercase text-white">Shop Now</span>
        </a>
    </div>
</section>

<section id="produk" class="pt-10 pb-32">
</section>
<section class="py-24 text-center">
    <h1 class="text-7xl font-extrabold italic uppercase tracking-tighter mb-4">New <span></span>Products.</h1>
    <p class="text-gray-500 tracking-widest uppercase text-[10px]">Premium Quality Experience</p>
</section>
<section id="produk">
    <div class="container mx-auto px-8 pb-32">
        <div class="grid grid-cols-1 md:grid-cols-3 gap-12">

            <div class="group cursor-pointer text-center" data-aos="fade-up" data-aos-delay="100">
                <div class="aspect-square bg-[#151515] rounded-[30px] mb-6 flex items-center justify-center border border-white/5 group-hover:border-white/20 transition-all duration-500">
                    <span class="text-white/5 text-6xl font-black italic">S</span>
                </div>
                <h3 class="text-sm font-bold uppercase tracking-tight mb-1">Signature Black</h3>
                <p class="text-gray-500 text-[11px]">IDR 185.000</p>
            </div>

            <div class="group cursor-pointer text-center" data-aos="fade-up" data-aos-delay="300">
                <div class="aspect-square bg-[#151515] rounded-[30px] mb-6 flex items-center justify-center border border-white/5 group-hover:border-white/20 transition-all duration-500">
                    <span class="text-white/5 text-6xl font-black italic">A</span>
                </div>
                <h3 class="text-sm font-bold uppercase tracking-tight mb-1">Arctic Menthol</h3>
                <p class="text-gray-500 text-[11px]">IDR 125.000</p>
            </div>

            <div class="group cursor-pointer text-center" data-aos="fade-up" data-aos-delay="500">
                <div class="aspect-square bg-[#151515] rounded-[30px] mb-6 flex items-center justify-center border border-white/5 group-hover:border-white/20 transition-all duration-500">
                    <span class="text-white/5 text-6xl font-black italic">E</span>
                </div>
                <h3 class="text-sm font-bold uppercase tracking-tight mb-1">Essential Pod</h3>
                <p class="text-gray-500 text-[11px]">IDR 250.000</p>
            </div>
            <div class="group cursor-pointer text-center" data-aos="fade-up" data-aos-delay="100">
                <div class="aspect-square bg-[#151515] rounded-[30px] mb-6 flex items-center justify-center border border-white/5 group-hover:border-white/20 transition-all duration-500">
                    <span class="text-white/5 text-6xl font-black italic">S</span>
                </div>
                <h3 class="text-sm font-bold uppercase tracking-tight mb-1">Signature Black</h3>
                <p class="text-gray-500 text-[11px]">IDR 185.000</p>
            </div>

            <div class="group cursor-pointer text-center" data-aos="fade-up" data-aos-delay="300">
                <div class="aspect-square bg-[#151515] rounded-[30px] mb-6 flex items-center justify-center border border-white/5 group-hover:border-white/20 transition-all duration-500">
                    <span class="text-white/5 text-6xl font-black italic">A</span>
                </div>
                <h3 class="text-sm font-bold uppercase tracking-tight mb-1">Arctic Menthol</h3>
                <p class="text-gray-500 text-[11px]">IDR 125.000</p>
            </div>

            <div class="group cursor-pointer text-center" data-aos="fade-up" data-aos-delay="500">
                <div class="aspect-square bg-[#151515] rounded-[30px] mb-6 flex items-center justify-center border border-white/5 group-hover:border-white/20 transition-all duration-500">
                    <span class="text-white/5 text-6xl font-black italic">E</span>
                </div>
                <h3 class="text-sm font-bold uppercase tracking-tight mb-1">Essential Pod</h3>
                <p class="text-gray-500 text-[11px]">IDR 250.000</p>
            </div>

        </div>
    </div>
</section>
<!-- Section Shop / Collection -->
<section id="shop" class="py-24 text-white">
    <div class="container mx-auto px-8">

        <!-- Header Section -->
        <div class="flex flex-col md:flex-row md:items-end justify-between mb-16 gap-6">
            <div data-aos="fade-right">
                <p class="text-[10px] font-black uppercase tracking-[0.5em] text-gray-500 mb-4">Official Collection</p>
                <h2 class="text-5xl md:text-7xl font-black italic uppercase tracking-tighter">
                    Shop <span class="text-gray-700">The Look.</span>
                </h2>
            </div>
            <div class="flex gap-4" data-aos="fade-left">
                <button class="px-6 py-2 border border-white/10 rounded-full text-[10px] font-bold uppercase tracking-widest hover:bg-white hover:text-black transition-all">All</button>
                <button class="px-6 py-2 border border-white/10 rounded-full text-[10px] font-bold uppercase tracking-widest hover:bg-white hover:text-black transition-all text-gray-500">Apparel</button>
                <button class="px-6 py-2 border border-white/10 rounded-full text-[10px] font-bold uppercase tracking-widest hover:bg-white hover:text-black transition-all text-gray-500">Accessories</button>
            </div>
        </div>

        <!-- Product Grid -->
        <div class="grid grid-cols-1 md:grid-cols-3 lg:grid-cols-4 gap-x-8 gap-y-16">

            <!-- Product Item 1 (Contoh) -->
            <div class="group cursor-pointer" data-aos="fade-up">
                <div class="relative aspect-[3/4] bg-[#0f0f0f] rounded-[30px] overflow-hidden mb-6">
                    <!-- Placeholder Gambar Produk -->
                    <img src="https://images.unsplash.com/photo-1551028719-00167b16eac5?q=80&w=1000&auto=format&fit=crop"
                        alt="Product"
                        class="w-full h-full object-cover grayscale group-hover:grayscale-0 group-hover:scale-110 transition-all duration-700">

                    <!-- Overlay Button -->
                    <div class="absolute inset-0 bg-black/40 opacity-0 group-hover:opacity-100 transition-opacity duration-500 flex items-center justify-center">
                        <button class="bg-white text-black px-8 py-3 rounded-full text-[10px] font-black uppercase tracking-widest transform translate-y-4 group-hover:translate-y-0 transition-transform duration-500">
                            Add to Cart
                        </button>
                    </div>
                </div>
                <div>
                    <div class="flex justify-between items-start mb-2">
                        <h3 class="font-bold uppercase tracking-tight text-lg leading-tight">Essential Tee <br><span class="text-gray-500 text-sm">V1 Black</span></h3>
                        <span class="font-black text-lg">IDR 249K</span>
                    </div>
                    <p class="text-[10px] font-bold text-gray-600 uppercase tracking-widest">ELVO Premium Apparel</p>
                </div>
            </div>

            <!-- Lo bisa duplikat Product Item di atas buat produk lainnya -->

        </div>
    </div>
</section>

@endsection