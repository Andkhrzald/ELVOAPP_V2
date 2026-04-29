<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ELVO STORE</title>
    <script src="https://cdn.tailwindcss.com"></script>
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

            <div class="hidden md:flex space-x-12 text-[10px] font-bold tracking-[0.3em] uppercase text-gray-400">
                <a href="#produk" class="hover:text-white transition duration-300">Shop</a>
                <a href="#collection" class="hover:text-white transition duration-300">Collections</a>
                <a href="#" class="hover:text-white transition duration-300">About</a>
            </div>
            <div class="flex items-center space-x-6 text-gray-400">
                <button class="hover:text-white transition">
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

                <button class="hover:text-white transition relative">
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

</body>

</html>