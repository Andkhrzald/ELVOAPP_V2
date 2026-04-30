document.addEventListener('DOMContentLoaded', function() {
    
    // === SEARCH BAR LOGIC ===
    const searchTrigger = document.getElementById('search-trigger');
    const closeSearch = document.getElementById('close-search');
    const searchOverlay = document.getElementById('search-overlay');
    const searchContent = document.getElementById('search-content');

    // Cek dulu apakah elemennya ada di halaman ini
    if (searchTrigger && searchOverlay) {
        searchTrigger.addEventListener('click', () => {
            searchOverlay.classList.remove('hidden');
            setTimeout(() => {
                searchOverlay.classList.add('opacity-100');
                searchContent.classList.remove('-translate-y-10');
                searchContent.classList.add('translate-y-0');
            }, 10);
            document.body.style.overflow = 'hidden';
        });

        const hideSearch = () => {
            searchOverlay.classList.remove('opacity-100');
            searchContent.classList.add('-translate-y-10');
            setTimeout(() => {
                searchOverlay.classList.add('hidden');
                document.body.style.overflow = 'auto';
            }, 500);
        };

        if (closeSearch) closeSearch.addEventListener('click', hideSearch);

        document.addEventListener('keydown', (e) => {
            if (e.key === "Escape" && !searchOverlay.classList.contains('hidden')) {
                hideSearch();
            }
        });
    }

    // === CART BAR LOGIC ===
    const cartTrigger = document.getElementById('cart-trigger');
    const closeCart = document.getElementById('close-cart');
    const cartDrawer = document.getElementById('cart-drawer');
    const cartOverlay = document.getElementById('cart-overlay');

    if (cartTrigger && cartDrawer) {
        cartTrigger.addEventListener('click', () => {
            cartDrawer.classList.remove('translate-x-full');
            cartOverlay.classList.remove('hidden');
            setTimeout(() => cartOverlay.classList.add('opacity-100'), 10);
            document.body.style.overflow = 'hidden';
        });

        const hideCart = () => {
            cartDrawer.classList.add('translate-x-full');
            cartOverlay.classList.remove('opacity-100');
            setTimeout(() => {
                cartOverlay.classList.add('hidden');
                document.body.style.overflow = 'auto';
            }, 500);
        };

        if (closeCart) closeCart.addEventListener('click', hideCart);
        if (cartOverlay) cartOverlay.addEventListener('click', hideCart);
    }
});