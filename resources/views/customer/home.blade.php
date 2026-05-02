@extends('layouts.customer')

@section('content')
<section class="relative min-h-[90vh] flex flex-col justify-center items-center text-center px-6 overflow-hidden">

    <!-- Background Layer (Posisikan paling atas di dalam section) -->
    <div class="absolute inset-0 z-0">
        <!-- Gambar Background -->
        <img src="{{ asset('img/bg.jpg') }}"
            class="w-full h-full object-cover grayscale opacity-40 transition-transform duration-[15s] hover:scale-110"
            alt="Background">

        <!-- Lapisan Gradasi/Gelap supaya teks ELVO tetap menonjol -->
        <div class="absolute inset-0 bg-gradient-to-b from-black/20 via-black/60 to-[#0a0a0a]"></div>
    </div>

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
        <p class="text-gray-300 text-xs md:text-sm tracking-[0.3em] uppercase font-medium mb-4">Local Brand Origin Jakarta</p>
        <p class="text-gray-400 text-sm md:text-base leading-relaxed italic opacity-80">
            "Selama lebih dari satu abad, H-D telah menyatukan orang-orang melalui keseruan berkendara. Semangat itu terus hidup dan lebih kuat dari sebelumnya"
        </p>
    </div>

    <div class="mt-12" data-aos="fade-up" data-aos-delay="500">
        <a href="{{ route('shop.index') }}" class="group relative inline-flex items-center justify-center px-12 py-4 overflow-hidden font-bold border border-white/60 rounded-full transition-all duration-300 hover:border-white">
            <span class="text-[10px] tracking-[0.4em] uppercase text-white">Shop Now</span>
        </a>
    </div>
</section>
<!-- Section About ELVOAPP -->
<section id="about" class="pt-10 pb-32">
</section>

<!-- Section About ELVOAPP -->
<section class="relative mb-24 px-4" data-aos="fade-up" data-aos-delay="200">
    <div class="max-w-6xl mx-auto">
        <div class="grid grid-cols-1 md:grid-cols-2 gap-12 items-center p-12 rounded-[40px] border border-white/5 bg-gradient-to-br from-white/[0.03] to-transparent backdrop-blur-xl">

            <div>
                <h2 class="text-[28px] font-black uppercase tracking-[0.5em] mb-8 text-white leading-tight">
                    About <br> <span class="text-gray-500">ELVOAPP</span>
                </h2>
                <div class="w-12 h-[2px] bg-white mb-8"></div>
                <p class="text-gray-400 text-[11px] leading-relaxed uppercase tracking-[0.2em] mb-10">
                    Kami hadir untuk mendefinisikan kembali gaya hidup digital melalui kurasi produk yang berani. ELVOAPP bukan sekadar platform belanja, tapi adalah manifestasi dari kreativitas dan kualitas yang kami bangun khusus untuk lo.
                </p>

                <div class="flex gap-8">
                    <div class="border-l border-white/10 pl-4">
                        <p class="text-white font-black text-[16px] tracking-widest">EST. 2025</p>
                        <p class="text-gray-600 text-[8px] uppercase font-bold tracking-widest">Established</p>
                    </div>
                    <div class="border-l border-white/10 pl-4">
                        <p class="text-white font-black text-[16px] tracking-widest">JKT</p>
                        <p class="text-gray-600 text-[8px] uppercase font-bold tracking-widest">Base Operation</p>
                    </div>
                </div>
            </div>

            <!-- Bagian Kanan: Bisa lo isi Image Brand atau Asset Figma lo -->
            <div class="relative group" data-aos="fade-left">
                <!-- Glow Effect di Belakang -->
                <div class="absolute inset-0 bg-white/5 rounded-[40px] blur-3xl group-hover:bg-white/10 transition-all duration-1000"></div>

                <div class="relative aspect-square rounded-[40px] border border-white/10 bg-black/40 backdrop-blur-sm overflow-hidden">

                    <!-- 3D Model Viewer -->
                    <model-viewer
                        src="{{ asset('3d/leather_jacket.glb') }}"
                        poster="{{ asset('images/jacket-placeholder.png') }}"
                        alt="ELVO Leather Jacket 3D"
                        auto-rotate
                        camera-controls
                        touch-action="pan-y"
                        shadow-intensity="2"
                        exposure="1"
                        class="w-full h-full cursor-grab active:cursor-grabbing"
                        style="--poster-color: transparent;">

                        <!-- Indikator Loading -->
                        <div slot="poster" class="flex items-center justify-center h-full">
                            <p class="text-[9px] text-gray-500 uppercase tracking-[0.4em] animate-pulse">Loading 3D Model...</p>
                        </div>
                    </model-viewer>

                </div>
            </div>
        </div>
    </div>
</section>
<section class="pt-10 pb-32">
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
                    Get <span class="text-gray-700">The Look.</span>
                </h2>
            </div>
            <div class="flex gap-4" data-aos="fade-left">
                <a href="{{ route('shop.index') }}" class="px-6 py-2 border border-white/10 rounded-full text-[10px] font-bold uppercase tracking-widest hover:bg-white hover:text-black transition-all text-gray-500">
                    All
                </a>
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
            <!-- Product Item 2 (Contoh) -->
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
            <!-- Product Item 3 (Contoh) -->
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

<section>
    <div class="container mx-auto px-6">
</section>

<section class="bg-[#0b0b0b] text-white pt-[100px] pb-20 border-t border-white/5">
    <div class="container mx-auto px-8">
        <div class="grid grid-cols-1 md:grid-cols-4 gap-12">
            <!-- Isi konten Shop, Corporate Info, dll lo di sini -->
        </div>
    </div>
</section>

<section clas text-white text-white py-20 mt-[100px] border-t border-white/10">
    <div class="container mx-auto px-6">
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-12">

            <!-- Column 1: SHOP -->
            <div>
                <h4 class="text-[10px] font-black uppercase tracking-[0.3em] mb-8 text-gray-500">Shop</h4>
                <ul class="space-y-4">
                    <li><a href="#" class="text-[11px] font-bold uppercase tracking-widest hover:text-gray-400 transition">Wanita</a></li>
                    <li><a href="#" class="text-[11px] font-bold uppercase tracking-widest hover:text-gray-400 transition">Pria</a></li>
                    <li><a href="#" class="text-[11px] font-bold uppercase tracking-widest hover:text-gray-400 transition">Aksesoris</a></li>
                    <li><a href="#" class="text-[11px] font-bold uppercase tracking-widest text-red-500 hover:text-red-400 transition">Sale</a></li>
                </ul>
            </div>

            <!-- Column 2: CORPORATE INFO -->
            <div>
                <h4 class="text-[10px] font-black uppercase tracking-[0.3em] mb-8 text-gray-500">Corporate Info</h4>
                <ul class="space-y-4">
                    <li><a href="#" class="text-[11px] font-bold uppercase tracking-widest hover:text-gray-400 transition">Berkarir di ELVO</a></li>
                    <li><a href="#" class="text-[11px] font-bold uppercase tracking-widest hover:text-gray-400 transition">Sustainability</a></li>
                    <li><a href="#" class="text-[11px] font-bold uppercase tracking-widest hover:text-gray-400 transition">Investor Relations</a></li>
                    <li><a href="#" class="text-[11px] font-bold uppercase tracking-widest hover:text-gray-400 transition">Tata Kelola</a></li>
                </ul>
            </div>

            <!-- Column 3: HELP -->
            <div>
                <h4 class="text-[10px] font-black uppercase tracking-[0.3em] mb-8 text-gray-500">Help</h4>
                <ul class="space-y-4">
                    <li><a href="#" class="text-[11px] font-bold uppercase tracking-widest hover:text-gray-400 transition">Customer Service</a></li>
                    <li><a href="#" class="text-[11px] font-bold uppercase tracking-widest hover:text-gray-400 transition">Lokasi Toko</a></li>
                    <li><a href="#" class="text-[11px] font-bold uppercase tracking-widest hover:text-gray-400 transition">Kontak</a></li>
                    <li><a href="#" class="text-[11px] font-bold uppercase tracking-widest hover:text-gray-400 transition underline underline-offset-8 decoration-red-500/50">Kebijakan Privasi</a></li>
                </ul>
            </div>

            <!-- Column 4: NEWSLETTER & APPS -->
            <div>
                <h4 class="text-[10px] font-black uppercase tracking-[0.3em] mb-8 text-gray-500">Jadi Member</h4>
                <p class="text-[11px] leading-relaxed text-gray-400 mb-6 tracking-wide">
                    Bergabunglah sekarang dan dapatkan diskon 10% untuk pembelian pertama anda!
                </p>
                <a href="login" class="inline-block text-[11px] font-black uppercase tracking-[0.2em] border-b-2 border-white pb-1 hover:text-gray-400 hover:border-gray-400 transition mb-10">
                    Bergabung Sekarang &rarr;
                </a>

                <h4 class="text-[10px] font-black uppercase tracking-[0.3em] mb-6 text-gray-500">Unduh Aplikasi</h4>
                <div class="flex items-center gap-4">
                    <!-- App Store -->
                    <a href="#" class="hover:opacity-70 transition">
                        <img src="https://upload.wikimedia.org/wikipedia/commons/3/3c/Download_on_the_App_Store_Badge.svg" alt="App Store" class="h-8">
                    </a>
                    <!-- Play Store -->
                    <a href="#" class="hover:opacity-70 transition">
                        <img src="https://upload.wikimedia.org/wikipedia/commons/7/78/Google_Play_Store_badge_EN.svg" alt="Play Store" class="h-8">
                    </a>
                </div>
            </div>

        </div>
    </div>
</section>

@endsection