@extends('layouts.customer')

@section('content')
<div class="min-h-screen text-white pt-28 pb-20 px-6">
    <div class="max-w-4xl mx-auto">

        {{-- Header --}}
        <div class="text-center mb-10" data-aos="fade-down">
            <div class="w-16 h-16 mx-auto rounded-2xl bg-elvo-primary/10 flex items-center justify-center mb-4">
                <svg class="w-8 h-8 text-elvo-primary" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
            </div>
            <h1 class="text-2xl md:text-4xl font-black italic uppercase tracking-tighter">Selesaikan Pembayaran</h1>
            <p class="text-[9px] text-gray-500 uppercase tracking-[0.5em] font-bold mt-2">Order #{{ $order->order_number }}</p>
        </div>

        {{-- Status Notification --}}
        <div id="payment-status" class="hidden mb-6 p-4 rounded-2xl text-[10px] font-bold uppercase tracking-widest text-center"></div>

        <div class="grid grid-cols-1 lg:grid-cols-5 gap-6">

            {{-- Left: Payment Info --}}
            <div class="lg:col-span-3 space-y-6">

                @if($order->payment_method === 'bank_transfer' && $order->selected_bank && $order->va_number)

                {{-- VA Hero --}}
                <div class="bg-white/[0.03] backdrop-blur-xl border border-white/[0.06] rounded-2xl p-6" data-aos="fade-up">
                    <div class="flex items-start justify-between mb-3">
                        <p class="text-[8px] font-black uppercase tracking-widest text-gray-500">Nomor Virtual Account</p>
                        @if($order->va_expires_at)
                        <div class="text-right">
                            <p class="text-[7px] text-gray-600 uppercase tracking-widest">Sisa Waktu</p>
                            <p id="countdown" class="text-[11px] font-mono font-black text-elvo-primary"></p>
                        </div>
                        @endif
                    </div>
                    <div class="flex items-center gap-2 mb-1">
                        <span class="w-8 h-8 rounded-lg flex items-center justify-center text-white text-[8px] font-black uppercase shrink-0
                            {{ $order->selected_bank === 'BCA' ? 'bg-blue-600' : ($order->selected_bank === 'BRI' ? 'bg-blue-800' : ($order->selected_bank === 'BNI' ? 'bg-orange-600' : 'bg-yellow-700')) }}">
                            {{ $order->selected_bank }}
                        </span>
                        <span class="text-2xl md:text-3xl font-mono font-black tracking-[0.15em] text-white select-all">{{ $order->va_number }}</span>
                        <button onclick="copyVA()" class="ml-auto px-4 py-2 bg-elvo-primary text-white rounded-xl text-[9px] font-black uppercase tracking-widest hover:bg-[#6a5cd8] transition shrink-0">Salin</button>
                    </div>
                    <p class="text-[9px] text-gray-500">Transfer sejumlah <span class="text-white font-bold">Rp {{ number_format($order->total_price, 0, ',', '.') }}</span> ke nomor VA di atas</p>
                </div>

                {{-- Payment Guide --}}
                <div class="bg-white/[0.03] backdrop-blur-xl border border-white/[0.06] rounded-2xl p-5" data-aos="fade-up" data-aos-delay="100">
                    <h3 class="text-[10px] font-black uppercase tracking-widest text-white mb-4">Panduan Pembayaran</h3>
                    <div class="space-y-1.5">

                        {{-- M-Banking --}}
                        <div class="border border-white/[0.06] rounded-xl overflow-hidden">
                            <button onclick="toggleGuide(this)" class="w-full flex items-center justify-between px-4 py-3 bg-white/[0.02] hover:bg-white/[0.04] transition text-left">
                                <div class="flex items-center gap-2.5">
                                    <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 18h.01M8 21h8a2 2 0 002-2V5a2 2 0 00-2-2H8a2 2 0 00-2 2v14a2 2 0 002 2z"/></svg>
                                    <span class="text-[10px] font-bold text-white uppercase tracking-widest">Mobile Banking</span>
                                </div>
                                <svg class="w-3.5 h-3.5 text-gray-500 transition-transform duration-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/></svg>
                            </button>
                            <div class="guide-content hidden px-4 pb-4 text-[9px] text-gray-400 leading-relaxed space-y-1.5">
                                <p>1. Buka aplikasi {{ $order->selected_bank }} Mobile</p>
                                <p>2. Pilih menu <span class="text-white font-bold">Transfer → Virtual Account</span></p>
                                <p>3. Masukkan nomor VA: <span class="text-white font-bold font-mono">{{ $order->va_number }}</span></p>
                                <p>4. Masukkan nominal: <span class="text-white font-bold">Rp {{ number_format($order->total_price, 0, ',', '.') }}</span></p>
                                <p>5. Konfirmasi & masukkan PIN</p>
                                <p>6. Simpan bukti transfer</p>
                            </div>
                        </div>

                        {{-- ATM --}}
                        <div class="border border-white/[0.06] rounded-xl overflow-hidden">
                            <button onclick="toggleGuide(this)" class="w-full flex items-center justify-between px-4 py-3 bg-white/[0.02] hover:bg-white/[0.04] transition text-left">
                                <div class="flex items-center gap-2.5">
                                    <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"/></svg>
                                    <span class="text-[10px] font-bold text-white uppercase tracking-widest">ATM {{ $order->selected_bank }}</span>
                                </div>
                                <svg class="w-3.5 h-3.5 text-gray-500 transition-transform duration-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/></svg>
                            </button>
                            <div class="guide-content hidden px-4 pb-4 text-[9px] text-gray-400 leading-relaxed space-y-1.5">
                                <p>1. Masukkan kartu ATM & PIN</p>
                                <p>2. Pilih <span class="text-white font-bold">Transaksi Lainnya → Transfer → Virtual Account</span></p>
                                <p>3. Masukkan nomor VA: <span class="text-white font-bold font-mono">{{ $order->va_number }}</span></p>
                                <p>4. Masukkan nominal: <span class="text-white font-bold">Rp {{ number_format($order->total_price, 0, ',', '.') }}</span></p>
                                <p>5. Konfirmasi & simpan struk sebagai bukti</p>
                            </div>
                        </div>

                        {{-- Internet Banking --}}
                        <div class="border border-white/[0.06] rounded-xl overflow-hidden">
                            <button onclick="toggleGuide(this)" class="w-full flex items-center justify-between px-4 py-3 bg-white/[0.02] hover:bg-white/[0.04] transition text-left">
                                <div class="flex items-center gap-2.5">
                                    <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.75 17L9 20l-1 1h8l-1-1-.75-3M3 13h18M5 17h14a2 2 0 002-2V5a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/></svg>
                                    <span class="text-[10px] font-bold text-white uppercase tracking-widest">Internet Banking</span>
                                </div>
                                <svg class="w-3.5 h-3.5 text-gray-500 transition-transform duration-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/></svg>
                            </button>
                            <div class="guide-content hidden px-4 pb-4 text-[9px] text-gray-400 leading-relaxed space-y-1.5">
                                <p>1. Login ke {{ $order->selected_bank }} Internet Banking</p>
                                <p>2. Pilih menu <span class="text-white font-bold">Transfer → Virtual Account</span></p>
                                <p>3. Masukkan nomor VA: <span class="text-white font-bold font-mono">{{ $order->va_number }}</span></p>
                                <p>4. Masukkan nominal: <span class="text-white font-bold">Rp {{ number_format($order->total_price, 0, ',', '.') }}</span></p>
                                <p>5. Konfirmasi & masukkan kode OTP</p>
                                <p>6. Screenshot halaman konfirmasi sebagai bukti</p>
                            </div>
                        </div>

                        {{-- Indomaret / Retail --}}
                        <div class="border border-white/[0.06] rounded-xl overflow-hidden">
                            <button onclick="toggleGuide(this)" class="w-full flex items-center justify-between px-4 py-3 bg-white/[0.02] hover:bg-white/[0.04] transition text-left">
                                <div class="flex items-center gap-2.5">
                                    <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"/></svg>
                                    <span class="text-[10px] font-bold text-white uppercase tracking-widest">Indomaret / Retail</span>
                                </div>
                                <svg class="w-3.5 h-3.5 text-gray-500 transition-transform duration-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/></svg>
                            </button>
                            <div class="guide-content hidden px-4 pb-4 text-[9px] text-gray-400 leading-relaxed space-y-1.5">
                                <p>1. Datang ke gerai Indomaret terdekat</p>
                                <p>2. Katakan ingin <span class="text-white font-bold">Pembayaran Virtual Account</span></p>
                                <p>3. Berikan nomor VA: <span class="text-white font-bold font-mono">{{ $order->va_number }}</span></p>
                                <p>4. Bayar sejumlah <span class="text-white font-bold">Rp {{ number_format($order->total_price, 0, ',', '.') }}</span> di kasir</p>
                                <p>5. Simpan struk sebagai bukti pembayaran</p>
                            </div>
                        </div>
                    </div>
                </div>

                @elseif($order->payment_method === 'qris')
                {{-- QRIS Payment --}}
                <div class="bg-white/[0.03] backdrop-blur-xl border border-white/[0.06] rounded-2xl p-6 text-center" data-aos="fade-up">
                    <p class="text-[8px] font-black uppercase tracking-widest text-gray-500 mb-4">Scan QRIS</p>
                    @php $qrisPath = \App\Models\Setting::getValue('payment_qris', ''); @endphp
                    @if($qrisPath)
                    <div class="w-48 h-48 mx-auto bg-white rounded-2xl p-2 mb-4">
                        <img src="{{ asset('storage/' . $qrisPath) }}" class="w-full h-full object-contain rounded-xl">
                    </div>
                    @else
                    <div class="w-48 h-48 mx-auto bg-white rounded-2xl p-2 mb-4 flex items-center justify-center">
                        <svg class="w-32 h-32 text-gray-300" viewBox="0 0 24 24" fill="currentColor"><path d="M1 1h10v10H1V1zm2 2v6h6V3H3zm11-2h10v10H14V1zm2 2v6h6V3h-6zM1 14h10v10H1V14zm2 2v6h6v-6H3zm11-2h10v10H14V14zm2 2v6h6v-6h-6z"/></svg>
                    </div>
                    @endif
                    <p class="text-[9px] text-gray-400 mb-2">Scan QR di atas dengan aplikasi pembayaran</p>
                    <p class="text-[9px] text-gray-500">Nominal: <span class="text-white font-bold">Rp {{ number_format($order->total_price, 0, ',', '.') }}</span></p>
                </div>
                @endif

                {{-- Cek Status Pembayaran --}}
                <div class="text-center space-y-3" data-aos="fade-up" data-aos-delay="200">
                    <button onclick="checkPayment()" id="check-btn"
                        class="px-8 py-3.5 bg-white text-black rounded-xl text-[10px] font-black uppercase tracking-[0.3em] hover:bg-gray-200 transition-all duration-300 italic">
                        <span id="check-btn-text">Cek Status Pembayaran</span>
                        <svg id="check-btn-spinner" class="hidden animate-spin h-4 w-4 mx-auto text-black" fill="none" viewBox="0 0 24 24">
                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"/>
                            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4z"/>
                        </svg>
                    </button>
                    <p class="text-[9px] text-gray-600">Setelah transfer, klik tombol di atas untuk verifikasi</p>

                    {{-- Demo bypass — hanya untuk development --}}
                    <div class="pt-4 border-t border-white/[0.06]">
                        <p class="text-[7px] text-gray-700 uppercase tracking-widest font-bold mb-2">— Tanpa Verifikasi (Demo) —</p>
                        <form action="{{ route('payment.force-complete', $order->id) }}" method="POST">
                            @csrf
                            <button type="submit"
                                class="px-8 py-3 bg-elvo-primary/10 text-elvo-primary border border-elvo-primary/20 rounded-xl text-[9px] font-black uppercase tracking-widest hover:bg-elvo-primary hover:text-white transition-all">
                                Selesaikan Pesanan
                            </button>
                        </form>
                    </div>
                </div>
            </div>

            {{-- Right: Order Summary --}}
            <div class="lg:col-span-2">
                <div class="bg-white/[0.03] backdrop-blur-xl border border-white/[0.06] rounded-2xl p-5 sticky top-28" data-aos="fade-up">
                    <h3 class="text-[10px] font-black uppercase tracking-widest text-white mb-4">Ringkasan Pesanan</h3>
                    <div class="space-y-3 mb-5">
                        @foreach($order->items as $item)
                        <div class="flex items-center gap-3 pb-3 border-b border-white/[0.06]">
                            <div class="w-12 h-12 rounded-lg bg-[#1a1a1a] overflow-hidden border border-white/5 shrink-0">
                                @if($item->product && $item->product->image)
                                <img src="{{ asset('uploads/' . $item->product->image) }}" class="w-full h-full object-cover">
                                @endif
                            </div>
                            <div class="flex-1 min-w-0">
                                <p class="text-[9px] font-bold uppercase tracking-widest truncate text-white">{{ $item->product_name }}</p>
                                <p class="text-[8px] text-gray-500">{{ $item->quantity }} × Rp {{ number_format($item->price, 0, ',', '.') }}</p>
                            </div>
                            <span class="text-[9px] font-black text-white">Rp {{ number_format($item->subtotal, 0, ',', '.') }}</span>
                        </div>
                        @endforeach
                    </div>
                    <div class="border-t border-white/[0.06] pt-3 space-y-1.5">
                        <div class="flex justify-between text-[9px]">
                            <span class="text-gray-500 font-bold uppercase tracking-widest">Subtotal</span>
                            <span class="text-white font-black">Rp {{ number_format($order->items->sum('subtotal'), 0, ',', '.') }}</span>
                        </div>
                        <div class="flex justify-between text-[9px]">
                            <span class="text-gray-500 font-bold uppercase tracking-widest">Ongkir</span>
                            <span class="text-white font-black">Rp {{ number_format($order->shipping_cost ?? 0, 0, ',', '.') }}</span>
                        </div>
                        <div class="flex justify-between text-sm pt-2 border-t border-white/[0.06]">
                            <span class="text-white font-bold uppercase tracking-widest">Total</span>
                            <span class="text-white font-black text-base">Rp {{ number_format($order->total_price, 0, ',', '.') }}</span>
                        </div>
                    </div>
                    <div class="mt-4 p-3 bg-white/[0.02] rounded-xl border border-white/[0.06]">
                        <p class="text-[8px] font-bold text-gray-500 uppercase tracking-widest">Data Pengirim</p>
                        <p class="text-[9px] text-white font-bold mt-1">{{ $order->user->name ?? 'Guest' }}</p>
                        <p class="text-[8px] text-gray-400">{{ $order->user->phone ?? '-' }}</p>
                        <p class="text-[8px] text-gray-400 mt-0.5">{{ $order->user->address ?? '-' }}</p>
                        @if($order->notes)
                        <p class="text-[8px] text-gray-500 italic mt-1">{{ $order->notes }}</p>
                        @endif
                    </div>
                    <a href="{{ route('history.index') }}" class="block mt-3 text-center text-[9px] text-gray-500 hover:text-white transition underline">Lihat semua pesanan</a>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
@if($order->va_expires_at)
// Countdown timer
const expiresAt = new Date('{{ $order->va_expires_at->format('Y-m-d H:i:s') }}').getTime();
function updateCountdown() {
    const now = new Date().getTime();
    const diff = expiresAt - now;
    const el = document.getElementById('countdown');
    if (diff <= 0) {
        el.textContent = 'Kadaluarsa';
        el.className = 'text-[11px] font-mono font-black text-red-500';
        return;
    }
    const h = Math.floor(diff / (1000 * 60 * 60));
    const m = Math.floor((diff % (1000 * 60 * 60)) / (1000 * 60));
    const s = Math.floor((diff % (1000 * 60)) / 1000);
    el.textContent = String(h).padStart(2, '0') + ':' + String(m).padStart(2, '0') + ':' + String(s).padStart(2, '0');
}
setInterval(updateCountdown, 1000);
updateCountdown();
@endif

function toggleGuide(btn) {
    const content = btn.nextElementSibling;
    const arrow = btn.querySelector('svg:last-child');
    content.classList.toggle('hidden');
    arrow.classList.toggle('rotate-180');
}

function copyVA() {
    const va = '{{ $order->va_number }}';
    navigator.clipboard.writeText(va).then(() => {
        const btn = document.querySelector('#copy-va-btn');
        if (btn) { btn.textContent = 'Tersalin!'; setTimeout(() => btn.textContent = 'Salin', 2000); }
    });
}

function checkPayment() {
    const btn = document.getElementById('check-btn');
    const text = document.getElementById('check-btn-text');
    const spinner = document.getElementById('check-btn-spinner');
    const statusEl = document.getElementById('payment-status');

    btn.disabled = true;
    text.classList.add('hidden');
    spinner.classList.remove('hidden');
    statusEl.classList.add('hidden');

    fetch('{{ route('payment.check', $order->id) }}')
        .then(res => res.json())
        .then(data => {
            btn.disabled = false;
            text.classList.remove('hidden');
            spinner.classList.add('hidden');

            if (data.success) {
                statusEl.className = 'mb-6 p-4 rounded-2xl text-[10px] font-bold uppercase tracking-widest text-center bg-green-500/10 border border-green-500/20 text-green-400';
                statusEl.textContent = '✅ Pembayaran berhasil dikonfirmasi! Mengarahkan...';
                statusEl.classList.remove('hidden');
                setTimeout(() => { window.location.href = '{{ route('checkout.success') }}?order={{ $order->id }}'; }, 1500);
            } else if (data.expired) {
                statusEl.className = 'mb-6 p-4 rounded-2xl text-[10px] font-bold uppercase tracking-widest text-center bg-red-500/10 border border-red-500/20 text-red-400';
                statusEl.textContent = '⏰ VA sudah kadaluarsa. Pesanan dibatalkan.';
                statusEl.classList.remove('hidden');
                setTimeout(() => { window.location.href = '{{ route('history.index') }}'; }, 2000);
            } else {
                statusEl.className = 'mb-6 p-4 rounded-2xl text-[10px] font-bold uppercase tracking-widest text-center bg-red-500/10 border border-red-500/20 text-red-400';
                statusEl.textContent = '⏳ Pembayaran belum dikonfirmasi. Silakan lakukan transfer terlebih dahulu.';
                statusEl.classList.remove('hidden');
            }
        })
        .catch(() => {
            btn.disabled = false;
            text.classList.remove('hidden');
            spinner.classList.add('hidden');
            statusEl.className = 'mb-6 p-4 rounded-2xl text-[10px] font-bold uppercase tracking-widest text-center bg-red-500/10 border border-red-500/20 text-red-400';
            statusEl.textContent = '❌ Gagal memeriksa status. Coba lagi.';
            statusEl.classList.remove('hidden');
        });
}
</script>
@endsection
