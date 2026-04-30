<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ELVO STORE</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="{{ asset('js/script.js') }}" defer></script>
    <link rel="icon" type="image/png" href="{{ asset('img/elvo_logo1.png') }}">
    <link href="https://unpkg.com/aos@2.3.1/dist/aos.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;800&display=swap" rel="stylesheet">

    <style>
        html {
            scroll-behavior: smooth;
        }

        body {
            font-family: 'Plus Jakarta Sans', sans-serif;
            background-color: #0b0b0b;
            color: white;
        }

        /* Efek blur halus untuk navbar saat di-scroll */
        .glass-nav {
            background: rgba(11, 11, 11, 0.8);
            backdrop-filter: blur(12px);
            -webkit-backdrop-filter: blur(12px);
        }
    </style>
</head>

<body>
    <nav class="fixed top-0 w-full z-50 border-b border-white/5 glass-nav">
        <div class="container mx-auto px-8 py-6 flex items-center justify-between">
            <a href="{{ route('home') }}" class="text-2xl font-extrabold italic tracking-tighter uppercase">
                ELVO.
            </a>
            <div class="hidden md:flex space-x-12 text-[10px] font-bold tracking-[0.3em] uppercase text-gray-500">

                <div class="relative group">
                    <a href="#shop" class="hover:text-white transition duration-300 flex items-center py-2">
                        Shop
                        <svg class="ml-1 w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                        </svg>
                    </a>

                    <div class="absolute left-0 mt-0 w-48 bg-[#151515] border border-white/5 rounded-xl py-4 opacity-0 invisible group-hover:opacity-100 group-hover:visible transition-all duration-300 translate-y-2 group-hover:translate-y-0 shadow-2xl">
                        <a href="#collection" class="block px-6 py-2 text-gray-400 hover:text-white hover:bg-white/5 transition italic">Baju</a>
                        <a href="#" class="block px-6 py-2 text-gray-400 hover:text-white hover:bg-white/5 transition italic">Celana</a>
                        <a href="#" class="block px-6 py-2 text-gray-400 hover:text-white hover:bg-white/5 transition italic">Aksesoris</a>
                    </div>
                </div>

                <a href="#collection" class="hover:text-white transition duration-300 py-2">Collections</a>
                <a href="#" class="hover:text-white transition duration-300 py-2">About</a>
                <a href="#" class="hover:text-white transition duration-300 py-2">store</a>
            </div>
            <div class="flex items-center space-x-6 text-gray-400">
                <!-- Tombol Search di Navbar -->
                <button id="search-trigger" class="hover:text-white transition">
                    <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <circle cx="11" cy="11" r="8" />
                        <path d="m21 21-4.3-4.3" />
                    </svg>
                </button>

                <button class="hover:text-white transition">
                    <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M19 21v-2a4 4 0 0 0-4-4H9a4 4 0 0 0-4 4v2" />
                        <circle cx="12" cy="7" r="4" />
                    </svg>
                </button>

                <button id="cart-trigger" class="hover:text-white transition relative">
                    <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M6 2 3 6v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2V6l-3-4Z" />
                        <path d="M3 6h18" />
                        <path d="M16 10a4 4 0 0 1-8 0" />
                    </svg>
                    <span class="absolute -top-1 -right-1 w-2 h-2 bg-white rounded-full"></span>
                </button>
            </div>
        </div>
    </nav>

    <main class="pt-24">
        @yield('content')
    </main>

    <footer class="py-20 border-t border-white/5 text-center mt-20">
        <div class="text-xl font-bold italic tracking-tighter mb-4 uppercase">ELVO.</div>
        <p class="text-[9px] text-gray-600 tracking-[0.4em] uppercase">
            © 2026 ELVO APP V2. FOR RESEARCH PURPOSES ONLY.
        </p>
    </footer>
    <script src="https://unpkg.com/aos@2.3.1/dist/aos.js"></script>
    <script>
        AOS.init({
            duration: 1000, // Kecepatan animasi (1 detik)
            once: true, // Animasi cuma jalan sekali pas di-scroll ke bawah
        });
    </script>
    <!-- Search Overlay -->
    <div id="search-overlay" class="fixed inset-0 bg-black/90 backdrop-blur-md z-[150] hidden opacity-0 transition-all duration-500 flex flex-col items-center justify-center px-6">
        <!-- Close Button -->
        <button id="close-search" class="absolute top-10 right-10 text-gray-400 hover:text-white transition-all transform hover:rotate-90">
            <svg xmlns="http://www.w3.org/2000/svg" width="32" height="32" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                <path d="M18 6 6 18" />
                <path d="m6 6 12 12" />
            </svg>
        </button>

        <!-- Search Input Area -->
        <div class="w-full max-w-3xl transform -translate-y-10 transition-all duration-500" id="search-content">
            <p class="text-[10px] font-black uppercase tracking-[0.5em] text-gray-500 mb-6 text-center">Search for Products</p>
            <form action="#" method="GET" class="relative">
                <input type="text" placeholder="TYPE SOMETHING..." class="w-full bg-transparent border-b-2 border-white/10 py-6 text-4xl md:text-6xl font-black italic uppercase tracking-tighter text-white focus:outline-none focus:border-white transition-colors placeholder:text-white/10">
                <button type="submit" class="absolute right-0 bottom-6 text-white hover:scale-125 transition-transform">
                    <svg xmlns="http://www.w3.org/2000/svg" width="30" height="30" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                        <circle cx="11" cy="11" r="8" />
                        <path d="m21 21-4.3-4.3" />
                    </svg>
                </button>
            </form>

            <!-- Quick Links / Suggestions -->
            <div class="mt-10 flex flex-wrap justify-center gap-6 text-[10px] font-bold uppercase tracking-widest text-gray-400">
                <span>Trending:</span>
                <a href="#" class="hover:text-white underline underline-offset-4 decoration-white/20">Signature Series</a>
                <a href="#" class="hover:text-white underline underline-offset-4 decoration-white/20">ELVO Store Essentials</a>
                <a href="#" class="hover:text-white underline underline-offset-4 decoration-white/20">BSI Special Edition</a>
            </div>
        </div>
    </div>

    <!-- User Overlay (Full Screen) -->
    <div id="user-overlay" class="fixed inset-0 bg-black/95 backdrop-blur-xl z-[200] hidden opacity-0 transition-all duration-500 flex flex-col items-center justify-center px-6">
        <!-- Close Button -->
        <button id="close-user" class="absolute top-10 right-10 text-gray-400 hover:text-white transition-all transform hover:rotate-90">
            <svg xmlns="http://www.w3.org/2000/svg" width="32" height="32" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                <path d="M18 6 6 18" />
                <path d="m6 6 12 12" />
            </svg>
        </button>

        <div class="w-full max-w-md transform -translate-y-10 transition-all duration-500 text-center" id="user-content">
            <!-- Header Estetik -->
            <p class="text-[10px] font-black uppercase tracking-[0.5em] text-gray-500 mb-10">Account Access</p>

            <!-- Menu Utama -->
            <div class="flex flex-col gap-8">
                <a href="#" class="group relative inline-block">
                    <span class="text-5xl md:text-7xl font-black italic uppercase tracking-tighter text-white transition-all group-hover:italic group-hover:tracking-normal">Login</span>
                    <span class="block h-1 w-0 bg-white transition-all group-hover:w-full"></span>
                </a>

                <a href="#" class="group relative inline-block">
                    <span class="text-5xl md:text-7xl font-black italic uppercase tracking-tighter text-white transition-all group-hover:italic group-hover:tracking-normal">Register</span>
                    <span class="block h-1 w-0 bg-white transition-all group-hover:w-full"></span>
                </a>
            </div>

            <!-- Footer Overlay -->
            <div class="mt-20 pt-10 border-t border-white/10">
                <p class="text-[9px] font-bold uppercase tracking-widest text-gray-600">
                    Join the ELVO. community for exclusive drops
                </p>
            </div>
        </div>
    </div>

    <!-- LOGIC SCRIPT -->


    <div id="cart-overlay" class="fixed inset-0 bg-black/60 backdrop-blur-sm z-[110] hidden opacity-0 transition-opacity duration-500"></div>

    <div id="cart-drawer" class="fixed top-0 right-0 h-full w-full md:w-[400px] bg-[#0f0f0f] z-[120] translate-x-full transition-transform duration-500 border-l border-white/5 shadow-2xl">
        <div class="flex flex-col h-full p-8">
            <div class="flex items-center justify-between mb-10">
                <h2 class="text-xl font-bold italic uppercase tracking-tighter">Your Cart</h2>
                <button id="close-cart" class="text-gray-400 hover:text-white transition">
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M18 6 6 18" />
                        <path d="m6 6 12 12" />
                    </svg>
                </button>
            </div>

            <div class="flex-grow overflow-y-auto space-y-6 pr-2 custom-scrollbar">
                <div class="flex items-center space-x-4 border-b border-white/5 pb-6">
                    <div class="w-20 h-20 bg-[#1a1a1a] rounded-xl flex-shrink-0 border border-white/5"></div>
                    <div class="flex-grow">
                        <h4 class="text-[10px] font-bold uppercase tracking-widest mb-1">Signature Black</h4>
                        <p class="text-[10px] text-gray-500 tracking-wider font-medium uppercase">IDR 185.000</p>
                    </div>
                    <button class="text-gray-600 hover:text-red-500 transition">
                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="none" stroke="currentColor" stroke-width="2">
                            <path d="M3 6h18m-2 0v14c0 1-1 2-2 2H7c-1 0-2-1-2-2V6m3 0V4c0-1 1-2 2-2h4c1 0 2 1 2 2v2" />
                        </svg>
                    </button>
                </div>
            </div>

            <div class="mt-auto pt-8 border-t border-white/10">
                <div class="flex justify-between items-center mb-6">
                    <span class="text-[10px] font-bold uppercase tracking-widest text-gray-400">Total</span>
                    <span class="text-lg font-black italic">IDR 185.000</span>
                </div>
                <button class="w-full bg-white text-black py-4 rounded-full text-[10px] font-black uppercase tracking-[0.3em] hover:bg-gray-200 transition-all duration-300">
                    Checkout Now
                </button>
            </div>
        </div>
    </div>
</body>


</html>