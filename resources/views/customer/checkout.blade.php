@extends('layouts.customer')

@section('content')
<section class="pt-32 pb-24 px-8 bg-[#0a0a0a]">
    <div class="container mx-auto max-w-6xl">

        @if(session('error'))
        <div class="mb-8 p-4 bg-red-500/20 border border-red-500/30 text-red-400 rounded-xl text-sm font-bold">{{ session('error') }}</div>
        @endif

        <form action="{{ route('checkout.process') }}" method="POST" id="checkout-form" class="flex flex-col lg:flex-row gap-16">
            @csrf

            {{-- Hidden cart data --}}
            <input type="hidden" name="cart" id="cart-data">

            {{-- KIRI: Form Pengiriman --}}
            <div class="flex-1" data-aos="fade-right">
                <h2 class="text-4xl font-black italic uppercase tracking-tighter text-white mb-10">
                    Shipping <span class="text-gray-700">Information.</span>
                </h2>

                <div class="space-y-8">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                        <div class="group">
                            <label class="text-[10px] font-black uppercase tracking-[0.2em] text-gray-500">Full Name</label>
                            <input type="text" name="name" required value="{{ Auth::user()->name ?? '' }}"
                                placeholder="NAMA LENGKAP"
                                class="w-full bg-transparent border-b-2 border-white/10 py-3 text-white focus:outline-none focus:border-white transition-all uppercase font-bold tracking-widest text-sm">
                        </div>
                        <div class="group">
                            <label class="text-[10px] font-black uppercase tracking-[0.2em] text-gray-500">Phone Number</label>
                            <input type="text" name="phone" required value="{{ Auth::user()->phone ?? '' }}"
                                placeholder="+62 812..."
                                class="w-full bg-transparent border-b-2 border-white/10 py-3 text-white focus:outline-none focus:border-white transition-all font-bold tracking-widest text-sm">
                        </div>
                    </div>

                    <div class="group">
                        <label class="text-[10px] font-black uppercase tracking-[0.2em] text-gray-500">Shipping Address</label>
                        <textarea name="address" required rows="3" placeholder="ALAMAT LENGKAP, KOTA, KODE POS"
                            class="w-full bg-transparent border-b-2 border-white/10 py-3 text-white focus:outline-none focus:border-white transition-all uppercase font-bold tracking-widest text-sm resize-none">{{ Auth::user()->address ?? '' }}</textarea>
                    </div>

                    <div class="group">
                        <label class="text-[10px] font-black uppercase tracking-[0.2em] text-gray-500 mb-4 block">Payment Method</label>
                        <div class="grid grid-cols-2 gap-4">
                            <label class="cursor-pointer">
                                <input type="radio" name="payment_method" value="Transfer Bank" class="hidden peer" checked>
                                <div class="p-4 border border-white/10 rounded-2xl peer-checked:border-white peer-checked:bg-white peer-checked:text-black transition-all text-center">
                                    <span class="text-[10px] font-black uppercase tracking-widest">Bank Transfer</span>
                                </div>
                            </label>
                            <label class="cursor-pointer">
                                <input type="radio" name="payment_method" value="E-Wallet (QRIS)" class="hidden peer">
                                <div class="p-4 border border-white/10 rounded-2xl peer-checked:border-white peer-checked:bg-white peer-checked:text-black transition-all text-center">
                                    <span class="text-[10px] font-black uppercase tracking-widest">E-Wallet (QRIS)</span>
                                </div>
                            </label>
                        </div>
                    </div>
                </div>
            </div>

            {{-- KANAN: Order Summary --}}
            <div class="w-full lg:w-[400px]" data-aos="fade-left">
                <div class="bg-[#111111] p-8 rounded-[30px] border border-white/5 sticky top-32 shadow-2xl">
                    <h3 class="text-xl font-black italic uppercase tracking-widest text-white mb-8 border-b border-white/10 pb-4">Order Summary</h3>

                    {{-- Items dari Cart (JS render) --}}
                    <div id="cart-items" class="space-y-6 mb-8">
                        <p class="text-gray-600 text-xs italic">Loading cart...</p>
                    </div>

                    {{-- Totals --}}
                    <div class="space-y-4 pt-6 border-t border-white/5">
                        <div class="flex justify-between text-[10px] font-bold uppercase tracking-widest text-gray-500">
                            <span>Subtotal</span>
                            <span id="subtotal-display">IDR 0</span>
                        </div>
                        <div class="flex justify-between text-[10px] font-bold uppercase tracking-widest text-gray-500">
                            <span>Shipping Cost</span>
                            <span>IDR 20K</span>
                        </div>
                        <div class="flex justify-between text-lg font-black italic uppercase tracking-tighter text-white pt-2">
                            <span>Grand Total</span>
                            <span id="grandtotal-display">IDR 0</span>
                        </div>
                    </div>

                    {{-- Button --}}
                    <button type="submit" id="btn-checkout" disabled
                        class="w-full block text-center bg-white text-black font-black uppercase tracking-[0.3em] py-6 mt-10 hover:bg-gray-200 transition-all italic text-sm rounded-full disabled:opacity-30 disabled:cursor-not-allowed">
                        Confirm Payment
                    </button>

                    <p class="text-center text-[9px] text-gray-600 uppercase tracking-widest mt-6">
                        Secure SSL Encryption
                    </p>
                </div>
            </div>
        </form>
    </div>
</section>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const cart = JSON.parse(localStorage.getItem('elvo_cart') || '[]');
    const container = document.getElementById('cart-items');
    const cartInput = document.getElementById('cart-data');
    const btnCheckout = document.getElementById('btn-checkout');

    if (cart.length === 0) {
        container.innerHTML = '<p class="text-gray-500 text-xs italic text-center py-4">Keranjang kosong. <a href="/shop" class="text-white underline">Belanja dulu</a></p>';
        return;
    }

    // Set cart data ke hidden input
    cartInput.value = JSON.stringify(cart);
    btnCheckout.disabled = false;

    let subtotal = 0;
    let html = '';

    cart.forEach(item => {
        const itemTotal = item.price * item.qty;
        subtotal += itemTotal;
        html += `
        <div class="flex gap-4">
            <div class="w-16 h-16 bg-[#1a1a1a] rounded-xl overflow-hidden flex-shrink-0 border border-white/5">
                ${item.image ? `<img src="/storage/${item.image}" class="w-full h-full object-cover">` : '<div class="w-full h-full flex items-center justify-center text-white/10 font-black italic text-xs">ELVO</div>'}
            </div>
            <div class="flex-1">
                <h4 class="text-[10px] font-black text-white uppercase tracking-widest leading-tight">${item.name}</h4>
                <p class="text-[10px] text-gray-500 mt-1 uppercase">QTY: ${item.qty}</p>
            </div>
            <span class="text-xs font-bold text-white">IDR ${(itemTotal/1000).toFixed(0)}K</span>
        </div>`;
    });

    container.innerHTML = html;
    document.getElementById('subtotal-display').textContent = 'IDR ' + (subtotal/1000).toFixed(0) + 'K';
    document.getElementById('grandtotal-display').textContent = 'IDR ' + ((subtotal + 20000)/1000).toFixed(0) + 'K';
});

// Clear cart after successful submit
document.getElementById('checkout-form').addEventListener('submit', function() {
    localStorage.removeItem('elvo_cart');
});
</script>
@endsection