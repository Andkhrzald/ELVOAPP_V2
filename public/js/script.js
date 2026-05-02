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
    const cartDrawer = document.getElementById('cart-drawer');
    const cartOverlay = document.getElementById('cart-overlay');
    const closeCart = document.getElementById('close-cart');

    if (cartTrigger && cartDrawer) {
        cartTrigger.addEventListener('click', () => {
            cartOverlay.classList.remove('hidden');
            setTimeout(() => cartOverlay.classList.add('opacity-100'), 10);
            cartDrawer.classList.remove('translate-x-full');
        });
    }

    if (closeCart) {
        closeCart.addEventListener('click', () => {
            cartOverlay.classList.remove('opacity-100');
            cartDrawer.classList.add('translate-x-full');
            setTimeout(() => cartOverlay.classList.add('hidden'), 500);
        });
    }

    // ==========================================
    // 3. USER AUTH & SWITCHER LOGIC
    // ==========================================
    const userTrigger = document.getElementById('user-trigger'); 
    const userOverlay = document.getElementById('user-overlay');
    const closeUser = document.getElementById('close-user');

    // Selector untuk Switcher Form
    const userMenu = document.getElementById('user-menu');
    const loginForm = document.getElementById('login-form');
    const registerForm = document.getElementById('register-form');
    const btnShowLogin = document.getElementById('show-login');
    const btnShowRegister = document.getElementById('show-register');
    const backBtns = document.querySelectorAll('.back-to-menu');

    // Fungsi Transisi Form
    const switchView = (from, to) => {
        if (!from || !to) return;
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

    if (userTrigger && userOverlay) {
        userTrigger.addEventListener('click', () => {
            userOverlay.classList.remove('hidden');
            setTimeout(() => userOverlay.classList.add('opacity-100'), 10);
            document.body.style.overflow = 'hidden';
        });

        if (btnShowLogin) {
            btnShowLogin.addEventListener('click', (e) => {
                e.preventDefault();
                switchView(userMenu, loginForm);
            });
        }

        if (btnShowRegister) {
            btnShowRegister.addEventListener('click', (e) => {
                e.preventDefault();
                switchView(userMenu, registerForm);
            });
        }

        backBtns.forEach(btn => {
            btn.addEventListener('click', () => {
                const currentVisible = !loginForm.classList.contains('hidden') ? loginForm : registerForm;
                switchView(currentVisible, userMenu);
            });
        });

        if (closeUser) {
            closeUser.addEventListener('click', () => {
                userOverlay.classList.remove('opacity-100'); 
                userOverlay.classList.add('opacity-0');
                
                setTimeout(() => {
                    userOverlay.classList.add('hidden');
                    // Reset ke menu utama
                    if(loginForm) loginForm.classList.add('hidden', 'opacity-0');
                    if(registerForm) registerForm.classList.add('hidden', 'opacity-0');
                    if(userMenu) {
                        userMenu.classList.remove('hidden', 'opacity-0');
                        userMenu.classList.add('opacity-100');
                    }
                    document.body.style.overflow = 'auto';
                }, 500);
            });
        }
    }
});