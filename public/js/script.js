document.addEventListener('DOMContentLoaded', function() {
    
    // ==========================================
    // 1. SEARCH BAR LOGIC
    // ==========================================
    const searchTrigger = document.getElementById('search-trigger');
    const closeSearch = document.getElementById('close-search');
    const searchOverlay = document.getElementById('search-overlay');
    const searchContent = document.getElementById('search-content');

    if (searchTrigger && searchOverlay) {
        searchTrigger.addEventListener('click', () => {
            searchOverlay.classList.remove('hidden');
            setTimeout(() => {
                searchOverlay.classList.add('opacity-100');
                if (searchContent) {
                    searchContent.classList.remove('-translate-y-10');
                    searchContent.classList.add('translate-y-0');
                }
            }, 10);
            document.body.style.overflow = 'hidden';
        });

        const hideSearch = () => {
            searchOverlay.classList.remove('opacity-100');
            if (searchContent) searchContent.classList.add('-translate-y-10');
            setTimeout(() => {
                searchOverlay.classList.add('hidden');
                document.body.style.overflow = 'auto';
            }, 500);
        };
        if (closeSearch) closeSearch.addEventListener('click', hideSearch);
    }

    // ==========================================
    // 2. CART BAR LOGIC
    // ==========================================
    const cartTrigger = document.getElementById('cart-trigger');
    const closeCart = document.getElementById('close-cart');
    const cartDrawer = document.getElementById('cart-drawer');
    const cartOverlay = document.getElementById('cart-overlay');

    if (cartTrigger && cartDrawer) {
        cartTrigger.addEventListener('click', () => {
            cartDrawer.classList.remove('translate-x-full');
            if (cartOverlay) {
                cartOverlay.classList.remove('hidden');
                setTimeout(() => cartOverlay.classList.add('opacity-100'), 10);
            }
            document.body.style.overflow = 'hidden';
        });

        const hideCart = () => {
            cartDrawer.classList.add('translate-x-full');
            if (cartOverlay) {
                cartOverlay.classList.remove('opacity-100');
                setTimeout(() => cartOverlay.classList.add('hidden'), 500);
            }
            document.body.style.overflow = 'auto';
        };
        if (closeCart) closeCart.addEventListener('click', hideCart);
        if (cartOverlay) cartOverlay.addEventListener('click', hideCart);
    }

    // === ELEMENT SELECTOR ===
    const navButtons = document.querySelectorAll('.flex.items-center.space-x-6 button');
    const userTrigger = navButtons[1]; // Tombol User di Navbar
    
    const userOverlay = document.getElementById('user-overlay');
    const closeUser = document.getElementById('close-user');
    
    // Switcher Elements
    const userMenu = document.getElementById('user-menu');
    const loginForm = document.getElementById('login-form');
    const registerForm = document.getElementById('register-form');
    
    const btnShowLogin = document.getElementById('show-login');
    const btnShowRegister = document.getElementById('show-register');
    const backBtns = document.querySelectorAll('.back-to-menu');

    // === FUNGSI TRANSISI UNIVERSAL ===
    const switchView = (from, to) => {
        from.classList.add('opacity-0', '-translate-y-5');
        setTimeout(() => {
            from.classList.add('hidden');
            to.classList.remove('hidden');
            setTimeout(() => {
                to.classList.remove('opacity-0', '-translate-y-5');
                to.classList.add('opacity-100', 'translate-y-0');
            }, 50);
        }, 400);
    };

    // === EVENT LISTENERS ===
    if (userTrigger && userOverlay) {
        // Buka Overlay dari Navbar
        userTrigger.addEventListener('click', () => {
            userOverlay.classList.remove('hidden');
            setTimeout(() => userOverlay.classList.add('opacity-100'), 10);
            document.body.style.overflow = 'hidden';
        });

        // Tombol Login di Klik
        if (btnShowLogin) {
            btnShowLogin.addEventListener('click', (e) => {
                e.preventDefault();
                switchView(userMenu, loginForm);
            });
        }

        // Tombol Register di Klik
        if (btnShowRegister) {
            btnShowRegister.addEventListener('click', (e) => {
                e.preventDefault();
                switchView(userMenu, registerForm);
            });
        }

        // Semua Tombol Back di Klik
        backBtns.forEach(btn => {
            btn.addEventListener('click', () => {
                const currentVisible = !loginForm.classList.contains('hidden') ? loginForm : registerForm;
                switchView(currentVisible, userMenu);
            });
        });

        // Tutup Overlay (Tombol Silang)
        if (closeUser) {
            closeUser.addEventListener('click', () => {s
                userOverlay.classList.add('opacity-0');
                setTimeout(() => {
                    userOverlay.classList.add('hidden');
                    // Reset ke menu utama pas ditutup biar rapi
                    loginForm.classList.add('hidden', 'opacity-0');
                    registerForm.classList.add('hidden', 'opacity-0');
                    userMenu.classList.remove('hidden', 'opacity-0');
                    document.body.style.overflow = 'auto';
                }, 500);
            });
        }
    }
});