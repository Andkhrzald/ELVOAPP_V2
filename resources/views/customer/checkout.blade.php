@extends('layouts.customer')

@section('content')
<section class="pt-28 pb-16 px-6">
    <div class="container mx-auto max-w-6xl">
        <h2 class="text-2xl md:text-4xl font-black italic uppercase tracking-tighter text-white mb-1" data-aos="fade-down">Checkout</h2>
        <p class="text-[9px] text-gray-500 uppercase tracking-[0.5em] font-bold mb-8" data-aos="fade-down">Finalize your order</p>

        @if(session('error'))
        <div class="mb-6 p-3.5 bg-red-500/10 border border-red-500/20 rounded-2xl text-[10px] text-red-500 font-bold uppercase tracking-widest animate-fade-up">{{ session('error') }}</div>
        @endif

        <form action="{{ route('checkout.process') }}" method="POST" id="checkout-form">
            @csrf
            <input type="hidden" name="cart" id="cart-data">
            <input type="hidden" name="selected_bank" id="selected-bank-input" value="">

            <div class="grid grid-cols-1 lg:grid-cols-5 gap-6">

                {{-- Left Column --}}
                <div class="lg:col-span-3 space-y-6">

                    {{-- Data Pengirim --}}
                    <div class="bg-white/[0.03] backdrop-blur-xl border border-white/[0.06] rounded-2xl p-5" data-aos="fade-up">
                        <h3 class="text-[10px] font-black uppercase tracking-widest text-white mb-4">Data Pengirim</h3>
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                            <div>
                                <label class="text-[9px] font-black uppercase tracking-widest text-gray-500 mb-1.5 block">Nama</label>
                                <input type="text" name="name" value="{{ old('name', Auth::user()->name ?? '') }}" required
                                    class="w-full bg-white/[0.04] border border-white/[0.06] rounded-xl py-2.5 px-3.5 text-sm text-white outline-none transition-all placeholder:text-gray-600 focus:border-elvo-primary/50 focus:bg-white/[0.06]">
                            </div>
                            <div>
                                <label class="text-[9px] font-black uppercase tracking-widest text-gray-500 mb-1.5 block">Telepon</label>
                                <input type="text" name="phone" value="{{ old('phone', Auth::user()->phone ?? '') }}" required
                                    class="w-full bg-white/[0.04] border border-white/[0.06] rounded-xl py-2.5 px-3.5 text-sm text-white outline-none transition-all placeholder:text-gray-600 focus:border-elvo-primary/50 focus:bg-white/[0.06]">
                            </div>
                        </div>
                        <div class="mt-4">
                            <label class="text-[9px] font-black uppercase tracking-widest text-gray-500 mb-1.5 block">Alamat</label>
                            <textarea name="address" required rows="2"
                                class="w-full bg-white/[0.04] border border-white/[0.06] rounded-xl py-2.5 px-3.5 text-sm text-white outline-none transition-all placeholder:text-gray-600 focus:border-elvo-primary/50 focus:bg-white/[0.06] resize-none">{{ old('address', Auth::user()->address ?? '') }}</textarea>
                        </div>
                        <div class="mt-4">
                            <label class="text-[9px] font-black uppercase tracking-widest text-gray-500 mb-1.5 block">Patokan Jalan</label>
                            <input type="text" name="landmark" value="{{ old('landmark') }}" placeholder="Contoh: Sebelah Alfamart, Rumah No. 5"
                                class="w-full bg-white/[0.04] border border-white/[0.06] rounded-xl py-2.5 px-3.5 text-sm text-white outline-none transition-all placeholder:text-gray-600 focus:border-elvo-primary/50 focus:bg-white/[0.06]">
                        </div>
                    </div>

                    {{-- Metode Pembayaran --}}
                    <div class="bg-white/[0.03] backdrop-blur-xl border border-white/[0.06] rounded-2xl p-5" data-aos="fade-up" data-aos-delay="100">
                        <h3 class="text-[10px] font-black uppercase tracking-widest text-white mb-4">Metode Pembayaran</h3>

                        {{-- Pilih metode --}}
                        <div class="flex gap-3 mb-5">
                            <label class="flex-1 flex items-center gap-3 p-3.5 rounded-xl border border-white/[0.06] bg-white/[0.02] cursor-pointer transition-all hover:border-elvo-primary/30 has-[:checked]:border-elvo-primary has-[:checked]:bg-elvo-primary/5">
                                <input type="radio" name="payment_method" value="bank_transfer" checked class="sr-only peer">
                                <div class="w-4 h-4 rounded-full border-2 border-white/20 peer-checked:border-elvo-primary peer-checked:bg-elvo-primary flex items-center justify-center shrink-0">
                                    <div class="w-1.5 h-1.5 rounded-full bg-white opacity-0 peer-checked:opacity-100"></div>
                                </div>
                                <div>
                                    <span class="text-[10px] font-bold text-white">Bank Transfer</span>
                                    <p class="text-[7px] text-gray-500 uppercase tracking-wider">BCA / BRI / BNI / Mandiri</p>
                                </div>
                            </label>
                            <label class="flex-1 flex items-center gap-3 p-3.5 rounded-xl border border-white/[0.06] bg-white/[0.02] cursor-pointer transition-all hover:border-elvo-primary/30 has-[:checked]:border-elvo-primary has-[:checked]:bg-elvo-primary/5">
                                <input type="radio" name="payment_method" value="qris" class="sr-only peer">
                                <div class="w-4 h-4 rounded-full border-2 border-white/20 peer-checked:border-elvo-primary peer-checked:bg-elvo-primary flex items-center justify-center shrink-0">
                                    <div class="w-1.5 h-1.5 rounded-full bg-white opacity-0 peer-checked:opacity-100"></div>
                                </div>
                                <div>
                                    <span class="text-[10px] font-bold text-white">QRIS</span>
                                    <p class="text-[7px] text-gray-500 uppercase tracking-wider">GoPay / OVO / DANA</p>
                                </div>
                            </label>
                        </div>

                        {{-- Pilih Bank (hanya untuk bank_transfer) --}}
                        <div id="bank-selection">
                            <p class="text-[9px] font-black uppercase tracking-widest text-gray-500 mb-3">Pilih Bank:</p>
                            <div class="grid grid-cols-2 gap-3">
                                @php
                                $bankList = ['BCA', 'BRI', 'BNI', 'Mandiri'];
                                $bankColors = ['BCA' => 'bg-blue-600', 'BRI' => 'bg-blue-800', 'BNI' => 'bg-orange-600', 'Mandiri' => 'bg-yellow-700'];
                                @endphp
                                @foreach($bankList as $bank)
                                <button type="button" data-bank="{{ $bank }}"
                                    class="bank-btn flex items-center gap-3 p-3.5 rounded-xl border border-white/[0.06] bg-white/[0.02] hover:border-elvo-primary/30 hover:bg-white/[0.04] transition-all text-left">
                                    <span class="w-10 h-10 rounded-xl flex items-center justify-center text-white text-[9px] font-black uppercase shrink-0 {{ $bankColors[$bank] }}">{{ $bank }}</span>
                                    <div>
                                        <p class="text-[11px] font-bold text-white">Transfer {{ $bank }}</p>
                                        <p class="text-[7px] text-gray-500 uppercase tracking-wider">Virtual Account</p>
                                    </div>
                                </button>
                                @endforeach
                            </div>
                            <p class="text-[9px] text-gray-500 mt-3 italic" id="bank-hint">Pilih bank untuk melanjutkan.</p>
                        </div>
                    </div>
                </div>

                {{-- Right: Order Summary --}}
                <div class="lg:col-span-2">
                    <div class="bg-white/[0.03] backdrop-blur-xl border border-white/[0.06] rounded-2xl p-5 sticky top-28" data-aos="fade-up" data-aos-delay="200">
                        <h3 class="text-[10px] font-black uppercase tracking-widest text-white mb-4">Ringkasan Pesanan</h3>
                        <div id="checkout-items" class="space-y-3 mb-5">
                            <div class="text-center py-6 text-gray-600 text-[9px] uppercase tracking-widest font-bold">Loading...</div>
                        </div>
                        <div class="border-t border-white/[0.06] pt-3 space-y-1.5">
                            <div class="flex justify-between text-[9px]">
                                <span class="text-gray-500 font-bold uppercase tracking-widest">Subtotal</span>
                                <span class="text-white font-black" id="checkout-subtotal">IDR 0</span>
                            </div>
                            <div class="flex justify-between text-[9px]">
                                <span class="text-gray-500 font-bold uppercase tracking-widest">Ongkir</span>
                                <span class="text-white font-black" id="checkout-shipping">IDR {{ number_format(\App\Models\Setting::getValue('shipping_cost', '20000'), 0, ',', '.') }}</span>
                            </div>
                            <div class="flex justify-between text-sm pt-2 border-t border-white/[0.06]">
                                <span class="text-white font-bold uppercase tracking-widest">Total</span>
                                <span class="text-white font-black text-base" id="checkout-total">IDR 0</span>
                            </div>
                        </div>
                        <button type="submit" id="checkout-submit" disabled
                            class="w-full mt-4 bg-white text-black py-3.5 rounded-xl text-[10px] font-black uppercase tracking-[0.3em] hover:bg-gray-200 transition-all duration-300 italic disabled:opacity-50 disabled:cursor-not-allowed">
                            <span id="checkout-btn-text">Bayar Sekarang</span>
                            <svg id="checkout-btn-spinner" class="hidden animate-spin h-4 w-4 mx-auto text-black" fill="none" viewBox="0 0 24 24">
                                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"/>
                                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4z"/>
                            </svg>
                        </button>
                    </div>
                </div>
            </div>
        </form>
    </div>
</section>

<script>
function getCart() { return JSON.parse(localStorage.getItem('elvo_cart') || '[]'); }
const SHIPPING_COST = {{ \App\Models\Setting::getValue('shipping_cost', '20000') }};

function formatPrice(num) {
    return 'IDR ' + Math.round(num).toString().replace(/\B(?=(\d{3})+(?!\d))/g, '.');
}

function renderCheckout() {
    const cart = getCart();
    const container = document.getElementById('checkout-items');
    const subtotalEl = document.getElementById('checkout-subtotal');
    const totalEl = document.getElementById('checkout-total');
    if (cart.length === 0) {
        container.innerHTML = '<div class="text-center py-6 text-gray-600 text-[9px] uppercase tracking-widest font-bold">Cart is empty</div>';
        subtotalEl.textContent = 'IDR 0';
        totalEl.textContent = 'IDR 0';
        document.getElementById('checkout-submit').disabled = true;
        return;
    }
    let html = '';
    let subtotal = 0;
    cart.forEach(item => {
        subtotal += item.price * item.qty;
        html += `
            <div class="flex items-center gap-3 pb-3 border-b border-white/[0.06] last:border-0">
                <div class="w-12 h-12 rounded-lg bg-[#1a1a1a] overflow-hidden border border-white/5 shrink-0">
                    ${item.image ? '<img src="/uploads/' + item.image + '" class="w-full h-full object-cover">' : ''}
                </div>
                <div class="flex-1 min-w-0">
                    <p class="text-[9px] font-bold uppercase tracking-widest truncate text-white">${item.name}</p>
                    <p class="text-[8px] text-gray-500">${item.qty} × ${formatPrice(item.price)}</p>
                </div>
                <span class="text-[9px] font-black text-white">${formatPrice(item.price * item.qty)}</span>
            </div>
        `;
    });
    container.innerHTML = html;
    subtotalEl.textContent = formatPrice(subtotal);
    totalEl.textContent = formatPrice(subtotal + SHIPPING_COST);
}

// Payment method toggle — show/hide bank selection
document.querySelectorAll('input[name="payment_method"]').forEach(el => {
    el.addEventListener('change', function() {
        document.getElementById('bank-selection').classList.toggle('hidden', this.value !== 'bank_transfer');
        document.getElementById('selected-bank-input').value = '';
        document.getElementById('checkout-submit').disabled = true;
        document.getElementById('bank-hint').textContent = this.value === 'bank_transfer' ? 'Pilih bank untuk melanjutkan.' : '';
    });
});

// Bank selection — enable submit
document.querySelectorAll('.bank-btn').forEach(btn => {
    btn.addEventListener('click', function() {
        const bank = this.dataset.bank;
        document.getElementById('selected-bank-input').value = bank;
        document.querySelectorAll('.bank-btn').forEach(b => {
            b.classList.remove('border-elvo-primary', 'bg-elvo-primary/5');
            b.classList.add('border-white/[0.06]', 'bg-white/[0.02]');
        });
        this.classList.remove('border-white/[0.06]', 'bg-white/[0.02]');
        this.classList.add('border-elvo-primary', 'bg-elvo-primary/5');
        document.getElementById('checkout-submit').disabled = false;
        document.getElementById('bank-hint').textContent = 'Bank ' + bank + ' dipilih.';
    });
});

// Submit: serialize cart
document.getElementById('checkout-form').addEventListener('submit', function(e) {
    const cart = getCart();
    if (cart.length === 0) { e.preventDefault(); alert('Cart is empty!'); return; }
    document.getElementById('cart-data').value = JSON.stringify(cart);
    localStorage.removeItem('elvo_cart');
    const btn = document.getElementById('checkout-submit');
    const text = document.getElementById('checkout-btn-text');
    const spinner = document.getElementById('checkout-btn-spinner');
    btn.disabled = true;
    text.classList.add('hidden');
    spinner.classList.remove('hidden');
});

document.addEventListener('DOMContentLoaded', renderCheckout);
</script>
@endsection
