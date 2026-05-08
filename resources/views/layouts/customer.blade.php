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
    <script type="module" src="https://ajax.googleapis.com/ajax/libs/model-viewer/3.5.0/model-viewer.min.js"></script>

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
            /* background: rgba(11, 11, 11, 0.8); */
            backdrop-filter: blur(12px);
            -webkit-backdrop-filter: blur(9px);
        }
    </style>
</head>

<body>
    <nav class="fixed top-0 w-full z-50 border-b border-white/5 glass-nav">
        <div class="container mx-auto px-8 py-6 flex items-center justify-between">
            <a href="{{ route('home') }}" class="text-2xl font-extrabold italic tracking-tighter uppercase">
                ELVO.
            </a>
            <div class="hidden md:flex space-x-12 text-[10px] font-bold tracking-[0.3em] uppercase text-gray-300">

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

                <a href="#produk" class="hover:text-white transition duration-300 py-2">Collections</a>
                <a href="#about" class="hover:text-white transition duration-300 py-2">About</a>
                <a href="{{ route('shop.index') }}" class="hover:text-white transition duration-300 py-2">store</a>
            </div>
            <div class="flex items-center space-x-6 text-gray-400">
                <!-- Tombol Search -->
                <button id="search-trigger" class="hover:text-white transition">
                    <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <circle cx="11" cy="11" r="8" />
                        <path d="m21 21-4.3-4.3" />
                    </svg>
                </button>

                <!-- Tombol Cart -->
                <button id="cart-trigger" class="hover:text-white transition relative">
                    <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <circle cx="9" cy="21" r="1" />
                        <circle cx="20" cy="21" r="1" />
                        <path d="M1 1h4l2.68 13.39a2 2 0 0 0 2 1.61h9.72a2 2 0 0 0 2-1.61L23 6H6" />
                    </svg>
                    <span class="absolute -top-1 -right-1 w-2 h-2 bg-white rounded-full"></span>
                </button>
                {{-- LOGIC AUTH START --}}
                @auth
                <div class="relative group">
                    <button class="text-[10px] font-black uppercase tracking-widest text-white flex items-center gap-2">
                        HI, {{ explode(' ', Auth::user()->name)[0] }}
                        <svg class="w-3 h-3 transition-transform group-hover:rotate-180" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path d="M19 9l-7 7-7-7"></path>
                        </svg>
                    </button>

                    {{-- Dropdown Menu --}}
                    <div class="absolute right-0 mt-2 w-48 bg-[#151515] border border-white/5 rounded-xl py-3 opacity-0 invisible group-hover:opacity-100 group-hover:visible transition-all duration-300 shadow-2xl">
                        <a href="{{ route('history.index') }}" class="flex items-center gap-3 px-5 py-2.5 text-[10px] font-bold text-gray-300 hover:text-white hover:bg-white/5 uppercase tracking-widest transition">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 2 3 6v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2V6l-3-4Z"/><line x1="3" y1="6" x2="21" y2="6"/><path d="M16 10a4 4 0 0 1-8 0"/></svg>
                            Pesanan Saya
                        </a>
                        <a href="{{ route('riwayat.index') }}" class="flex items-center gap-3 px-5 py-2.5 text-[10px] font-bold text-gray-300 hover:text-white hover:bg-white/5 uppercase tracking-widest transition">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"/></svg>
                            Riwayat Transaksi
                        </a>
                        <div class="border-t border-white/5 my-2"></div>
                        <form action="{{ route('logout') }}" method="POST">
                            @csrf
                            <button type="submit" class="w-full flex items-center gap-3 px-5 py-2.5 text-[10px] font-bold text-red-500 hover:bg-white/5 uppercase tracking-widest transition">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/></svg>
                                Sign Out
                            </button>
                        </form>
                    </div>
                </div>
                @else
                {{-- Tombol User (Trigger Overlay) --}}
                <button id="user-trigger" class="hover:text-white transition">
                    <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M19 21v-2a4 4 0 0 0-4-4H9a4 4 0 0 0-4 4v2" />
                        <circle cx="12" cy="7" r="4" />
                    </svg>
                </button>
                @endauth
                {{-- LOGIC AUTH END --}}

                {{-- Tombol Pesanan Saya (icon paperbag) --}}
                <a href="{{ route('history.index') }}"
                    class="relative {{ request()->routeIs('history.index') ? 'text-white' : 'text-gray-400' }} hover:text-white transition flex items-center group"
                    title="Pesanan Saya">
                    <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M6 2 3 6v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2V6l-3-4Z"></path>
                        <line x1="3" y1="6" x2="21" y2="6"></line>
                        <path d="M16 10a4 4 0 0 1-8 0"></path>
                    </svg>
                    {{-- Tooltip --}}
                    <span class="absolute -bottom-8 left-1/2 -translate-x-1/2 px-2 py-1 bg-white text-black text-[8px] font-black uppercase tracking-widest rounded opacity-0 group-hover:opacity-100 transition-opacity whitespace-nowrap pointer-events-none">My Orders</span>
                </a>
            </div>
        </div>
    </nav>

    <main class="">
        @yield('content')
    </main>

    <footer class="py-10 border-t border-white/5 text-center mt-20">
        <div class="text-xl font-bold italic tracking-tighter mb-4 uppercase">ELVO.</div>
        <p class="text-[9px] text-gray-600 tracking-[0.4em] uppercase">
            © 2026 ELVO APP V2. FOR RESEARCH PURPOSES ONLY.
        </p>
    </footer>

    <!-- ==========================================
         CART DRAWER (Z-110 & Z-120)
         ========================================== -->
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
                </div>
            </div>

            <div class="mt-auto pt-8 border-t border-white/10">
                <div class="flex justify-between items-center mb-6">
                    <span class="text-[10px] font-bold uppercase tracking-widest text-gray-400">Total</span>
                    <span class="text-lg font-black italic">IDR 185.000</span>
                </div>
                <a href="{{ route('checkout') }}" class="block w-full bg-white text-black text-center py-4 rounded-full text-[10px] font-black uppercase tracking-[0.3em] hover:bg-gray-200 transition-all duration-300">
                    Checkout Now
                </a>
            </div>
        </div>
    </div>

    <!-- ==========================================
         USER OVERLAY (LOGIN MENU) - Z-200
         ========================================== -->
    <div id="user-overlay" class="fixed inset-0 bg-black/95 backdrop-blur-xl z-[200] hidden opacity-0 transition-all duration-500 flex flex-col items-center justify-center px-6">
        <button id="close-user" class="absolute top-10 right-10 text-gray-400 hover:text-white transition-all transform hover:rotate-90">
            <svg xmlns="http://www.w3.org/2000/svg" width="32" height="32" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                <path d="M18 6 6 18" />
                <path d="m6 6 12 12" />
            </svg>
        </button>

        <div class="w-full max-w-md text-center">
            <p class="text-[10px] font-black uppercase tracking-[0.5em] text-gray-500 mb-10">Account Access</p>
            <div class="flex flex-col gap-8">
                <a href="{{ route('login') }}" class="group relative inline-block text-center">
                    <span class="text-5xl md:text-7xl font-black italic uppercase tracking-tighter text-white transition-all group-hover:tracking-normal">Login</span>
                    <span class="block h-1 w-0 bg-white transition-all group-hover:w-full mt-2"></span>
                </a>
                <a href="{{ route('register') }}" class="group relative inline-block text-center">
                    <span class="text-5xl md:text-7xl font-black italic uppercase tracking-tighter text-gray-600 transition-all hover:text-white hover:tracking-normal">Register</span>
                </a>
            </div>
        </div>
    </div>

    <!-- SCRIPT LOGIC -->
    <script src="https://unpkg.com/aos@2.3.1/dist/aos.js"></script>
    <script>
        AOS.init({
            duration: 1000,
            once: true
        });

        // Logic Cart Drawer
        const cartTrigger = document.getElementById('cart-trigger');
        const cartDrawer = document.getElementById('cart-drawer');
        const cartOverlay = document.getElementById('cart-overlay');
        const closeCart = document.getElementById('close-cart');

        cartTrigger.addEventListener('click', () => {
            cartOverlay.classList.remove('hidden');
            setTimeout(() => cartOverlay.classList.add('opacity-100'), 10);
            cartDrawer.classList.remove('translate-x-full');
        });

        closeCart.addEventListener('click', () => {
            cartOverlay.classList.remove('opacity-100');
            cartDrawer.classList.add('translate-x-full');
            setTimeout(() => cartOverlay.classList.add('hidden'), 500);
        });

        // Logic User Overlay (Jika lo mau tombol user tetap munculin menu login/regis)
        const userTrigger = document.querySelector('button:has(svg circle[cx="12"])'); // Menargetkan tombol user
        const userOverlay = document.getElementById('user-overlay');
        const closeUser = document.getElementById('close-user');

        if (userTrigger) {
            userTrigger.addEventListener('click', () => {
                userOverlay.classList.remove('hidden');
                setTimeout(() => userOverlay.classList.add('opacity-100'), 10);
            });
        }

        closeUser.addEventListener('click', () => {
            userOverlay.classList.remove('opacity-100');
            setTimeout(() => userOverlay.classList.add('hidden'), 500);
        });
    </script>
</body>

</html>