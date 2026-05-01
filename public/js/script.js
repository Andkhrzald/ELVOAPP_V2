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
   const cartTrigger = document.getElementById('cart-trigger'); // Sesuaikan dengan ID icon cart di navbar lo
const cartDrawer = document.getElementById('cart-drawer');
const cartOverlay = document.getElementById('cart-overlay');
const closeCart = document.getElementById('close-cart');

if (cartTrigger) {
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
  // === ELEMENT SELECTOR ===
// Lebih aman pakai ID langsung daripada urutan array [1]
const userTrigger = document.getElementById('user-trigger'); 
const userOverlay = document.getElementById('user-overlay');
const closeUser = document.getElementById('close-user');

// ... sisa selector switcher tetap sama ...

// === EVENT LISTENERS ===
if (userTrigger && userOverlay) {
    userTrigger.addEventListener('click', () => {
        userOverlay.classList.remove('hidden');
        // Gunakan requestAnimationFrame atau timeout kecil agar transisi opacity jalan
        setTimeout(() => userOverlay.classList.add('opacity-100'), 10);
        document.body.style.overflow = 'hidden';
    });

    // ... logic switchView tetap ...

    // Tutup Overlay
    if (closeUser) {
        closeUser.addEventListener('click', () => {
            // Tadi ada typo "s" setelah tanda kurung di kode lo, ini sudah diperbaiki:
            userOverlay.classList.remove('opacity-100'); 
            userOverlay.classList.add('opacity-0');
            
            setTimeout(() => {
                userOverlay.classList.add('hidden');
                // Reset view ke menu utama saat ditutup
                loginForm.classList.add('hidden', 'opacity-0');
                registerForm.classList.add('hidden', 'opacity-0');
                userMenu.classList.remove('hidden', 'opacity-0');
                userMenu.classList.add('opacity-100');
                document.body.style.overflow = 'auto';
            }, 500);
    }
}
 });