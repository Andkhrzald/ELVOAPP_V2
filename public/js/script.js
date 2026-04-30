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

    // ==========================================
    // 3. USER OVERLAY LOGIC (The New One)
    // ==========================================
    // Kita cari tombol user di navbar (tombol ke-2 di dalam kontainer icons)
   const navButtons = document.querySelectorAll('.flex.items-center.space-x-6 button');
    const userTrigger = navButtons[1]; // Target ikon User (Tombol tengah)
    
    const userOverlay = document.getElementById('user-overlay');
    const closeUser = document.getElementById('close-user');
    const userContent = document.getElementById('user-content');

    if (userTrigger && userOverlay) {
        userTrigger.addEventListener('click', () => {
            userOverlay.classList.remove('hidden');
            setTimeout(() => {
                userOverlay.classList.add('opacity-100');
                if (userContent) {
                    userContent.classList.remove('-translate-y-10');
                    userContent.classList.add('translate-y-0');
                }
            }, 10);
            document.body.style.overflow = 'hidden';
        });

        const hideUser = () => {
            userOverlay.classList.remove('opacity-100');
            if (userContent) {
                userContent.classList.add('-translate-y-10');
                userContent.classList.remove('translate-y-0');
            }
            setTimeout(() => {
                userOverlay.classList.add('hidden');
                document.body.style.overflow = 'auto';
            }, 500);
        };

        if (closeUser) closeUser.addEventListener('click', hideUser);

        // ESC to close
        document.addEventListener('keydown', (e) => {
            if (e.key === "Escape" && !userOverlay.classList.contains('hidden')) {
                hideUser();
            }
        });
    }
});