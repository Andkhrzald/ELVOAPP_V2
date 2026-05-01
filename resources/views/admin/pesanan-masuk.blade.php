@extends('layouts.app')

@section('content')
{{-- Notifikasi Sukses --}}
@if(session('success'))
    <div class="bg-green-100 border-l-4 border-green-500 text-green-700 p-4 mb-4 rounded shadow-sm flex justify-between items-center" role="alert">
        <div>
            <p class="font-bold">Sukses!</p>
            <p>{{ session('success') }}</p>
        </div>
        <button onclick="this.parentElement.remove()" class="text-green-700 font-bold">&times;</button>
    </div>
@endif

<div class="mb-5 flex justify-between items-center">
    <div>
        <h1 class="text-2xl font-bold text-gray-800">Pesanan Masuk</h1>
        <p class="text-sm text-gray-500">Segera proses pesanan pelanggan agar mereka senang!</p>
    </div>
</div>

{{-- Filter Status Tab --}}
<div class="flex border-b border-gray-200 mb-6">
    <a href="{{ route('admin.pesanan-masuk', ['status' => 'proses']) }}" 
       class="px-4 py-2 text-sm font-medium {{ $status == 'proses' ? 'text-blue-600 border-b-2 border-blue-600' : 'text-gray-500 hover:text-blue-500' }}">
        Perlu Diproses
    </a>

    <a href="{{ route('admin.pesanan-masuk', ['status' => 'pending']) }}" 
       class="px-4 py-2 text-sm font-medium {{ $status == 'pending' ? 'text-blue-600 border-b-2 border-blue-600' : 'text-gray-500 hover:text-blue-500' }}">
        Belum Bayar
    </a>

    <a href="{{ route('admin.pesanan-masuk', ['status' => 'dikirim']) }}" 
       class="px-4 py-2 text-sm font-medium {{ $status == 'dikirim' ? 'text-blue-600 border-b-2 border-blue-600' : 'text-gray-500 hover:text-blue-500' }}">
        Dikirim
    </a>
</div>

<div class="grid grid-cols-1 gap-4">
    {{-- STEP 3: Looping Data dari Controller --}}
    @forelse($orders as $order)
        <div class="p-5 bg-white rounded-xl border border-gray-200 shadow-sm">
            <div class="flex justify-between items-start mb-4">
                <div class="flex gap-4">
                    <div class="w-12 h-12 bg-blue-50 rounded-lg flex items-center justify-center text-blue-600">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"/>
                        </svg>
                    </div>
                    <div>
                        {{-- Mengambil data dari array $order --}}
                        <h3 class="font-bold text-gray-900">#{{ $order['id'] }} - {{ $order['customer_name'] }}</h3>
                        <p class="text-xs text-gray-500">Dipesan pada: {{ $order['created_at'] }}</p>
                    </div>
                </div>
                
                {{-- Warna Badge Otomatis sesuai Status --}}
                <span class="px-3 py-1 rounded-full text-xs font-bold 
                    {{ $status == 'proses' ? 'bg-orange-100 text-orange-600' : '' }}
                    {{ $status == 'pending' ? 'bg-red-100 text-red-600' : '' }}
                    {{ $status == 'dikirim' ? 'bg-green-100 text-green-600' : '' }}">
                    {{ $status == 'proses' ? 'Perlu Dikemas' : ($status == 'pending' ? 'Belum Bayar' : 'Sudah Dikirim') }}
                </span>
            </div>

            <div class="border-t border-b border-gray-50 py-3 my-3">
                <div class="flex justify-between text-sm">
                    <span class="text-gray-600 italic">Pesanan: {{ $order['items'] ?? 'Produk Elvo' }}</span>
                    <span class="font-bold text-gray-900">Total: Rp {{ number_format($order['total'], 0, ',', '.') }}</span>
                </div>
            </div>

            <div class="flex justify-end gap-3">
                <button onclick="toggleDrawer()" class="text-sm text-gray-600 font-medium px-4 py-2">Lihat Detail</button>
                
                {{-- Logika Tombol Berdasarkan Tab --}}
                @if($status == 'proses')
                    <button onclick="toggleModal('{{ $order['id'] }}')" class="bg-blue-600 text-white px-4 py-2 rounded-lg text-sm font-medium hover:bg-opacity-90">
                        Konfirmasi & Kirim
                    </button>
                @elseif($status == 'pending')
                    <button class="bg-gray-200 text-gray-500 px-4 py-2 rounded-lg text-sm font-medium cursor-not-allowed">
                        Menunggu Pembayaran
                    </button>
                @else
                    <button class="bg-green-100 text-green-600 px-4 py-2 rounded-lg text-sm font-medium">
                        Selesai Dikirim
                    </button>
                @endif
            </div>
        </div>
    @empty
        {{-- Tampilan jika data kosong --}}
        <div class="bg-white p-10 rounded-xl border border-dashed border-gray-300 text-center">
            <p class="text-gray-500">Tidak ada pesanan dengan status ini.</p>
        </div>
    @endforelse
</div>

{{-- DRAWER DETAIL --}}
<div id="drawer-detail" class="fixed inset-0 z-50 hidden">
    {{-- Kita hilangkan overlay hitam pekat, ganti dengan area klik transparan saja --}}
    <div class="fixed inset-0 bg-transparent" onclick="toggleDrawer()"></div>

    <div class="fixed inset-y-0 right-0 flex max-w-full">
        {{-- Panel Content --}}
        <div class="relative w-screen max-w-md bg-white shadow-2xl animate-slide-in h-full flex flex-col border-l border-gray-100">
            
            <div class="px-6 py-6 border-b flex items-center justify-between">
                <h2 class="text-xl font-bold text-gray-900">Rincian Pesanan</h2>
                <button onclick="toggleDrawer()" class="text-gray-400 hover:text-gray-600 outline-none">
                    <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>

            <div class="flex-1 overflow-y-auto p-6 space-y-8">
                {{-- ISI RINCIAN PESANAN TETAP SAMA (TIDAK SAYA UBAH) --}}
                <div class="bg-blue-50 p-4 rounded-xl border border-blue-100">
                    <p class="text-xs text-blue-600 font-bold uppercase tracking-wider">Nomor Invoice</p>
                    <p class="text-lg font-black text-blue-900">#{{ $orders[0]['id'] ?? 'TRX-XXXXX' }}</p>
                </div>

                <section>
                    <h3 class="text-sm font-bold text-gray-400 uppercase mb-3">Informasi Pengiriman</h3>
                    <div class="space-y-2">
                        <p class="font-bold text-gray-900 text-base">{{ $orders[0]['customer_name'] ?? 'Nama Pelanggan' }}</p>
                        <p class="text-sm text-gray-600">{{ $orders[0]['phone'] ?? '-' }}</p>
                        <p class="text-sm text-gray-600 leading-relaxed">{{ $orders[0]['address'] ?? 'Alamat belum diisi' }}</p>
                    </div>
                </section>

                <div class="grid grid-cols-2 gap-4">
                    <section>
                        <h3 class="text-xs font-bold text-gray-400 uppercase mb-2">Pembayaran</h3>
                        <p class="text-sm font-semibold text-gray-900">{{ $orders[0]['payment_method'] ?? '-' }}</p>
                    </section>
                    <section>
                        <h3 class="text-xs font-bold text-gray-400 uppercase mb-2">Ekspedisi</h3>
                        <p class="text-sm font-semibold text-gray-900">{{ $orders[0]['shipping_method'] ?? '-' }}</p>
                    </section>
                </div>

                <section>
                    <h3 class="text-sm font-bold text-gray-400 uppercase mb-3">Produk</h3>
                    <div class="border rounded-xl divide-y overflow-hidden">
                        <div class="p-4 flex justify-between items-center bg-white">
                            <div>
                                <p class="text-sm font-bold text-gray-900">Elvo Premium Hoodie</p>
                                <p class="text-xs text-gray-500">Hitam | XL x 2</p>
                            </div>
                            <p class="text-sm font-bold text-gray-900">Rp 900.000</p>
                        </div>
                        <div class="p-4 flex justify-between items-center bg-white">
                            <div>
                                <p class="text-sm font-bold text-gray-900">Aksesoris Elvo</p>
                                <p class="text-xs text-gray-500">Universal x 1</p>
                            </div>
                            <p class="text-sm font-bold text-gray-900">Rp 250.000</p>
                        </div>
                    </div>
                </section>

                <section class="bg-gray-50 p-5 rounded-2xl space-y-3">
                    <div class="flex justify-between text-sm text-gray-500">
                        <span>Subtotal</span>
                        <span class="text-gray-900 font-semibold">Rp {{ number_format($orders[0]['subtotal'] ?? 0, 0, ',', '.') }}</span>
                    </div>
                    <div class="flex justify-between text-sm text-gray-500">
                        <span>Ongkos Kirim</span>
                        <span class="text-gray-900 font-semibold">Rp {{ number_format($orders[0]['shipping_cost'] ?? 0, 0, ',', '.') }}</span>
                    </div>
                    <div class="flex justify-between text-lg font-black border-t pt-3 mt-2 text-blue-600">
                        <span>Total Bayar</span>
                        <span>Rp {{ number_format($orders[0]['total'] ?? 0, 0, ',', '.') }}</span>
                    </div>
                </section>
            </div>

            <div class="p-6 border-t bg-white">
                @if($status == 'proses')
                <button onclick="toggleModal('{{ $orders[0]['id'] ?? '' }}')" class="w-full bg-blue-600 text-white py-4 rounded-xl font-bold hover:bg-blue-700 transition-all shadow-lg shadow-blue-100">
                    Konfirmasi & Input Resi
                </button>
                @endif
            </div>
        </div>
    </div>
</div>

{{-- MODAL KONFIRMASI --}}
<div id="modal-konfirmasi" class="fixed inset-0 z-[60] hidden">
    {{-- Overlay: Kita buat transparan agar background dashboard tetap kelihatan interaktif --}}
    <div class="fixed inset-0 bg-transparent" onclick="toggleModal()"></div>
    
    <div class="flex items-center justify-center min-h-screen p-4">
        {{-- Modal Card: Kita tambah border dan shadow agar tetap menonjol di atas background bening --}}
        <form id="formKonfirmasi" method="POST" class="relative bg-white rounded-2xl shadow-2xl max-w-lg w-full p-8 z-10 border border-gray-100 animate-slide-in">
            @csrf
            <div class="mb-6">
                <h3 class="text-xl font-bold text-gray-900">Konfirmasi Pengiriman</h3>
                <p class="text-sm text-gray-500 mt-2">Silahkan masukkan nomor resi kurir untuk pesanan ini agar pelanggan bisa melacak paket mereka.</p>
            </div>
            
            <div class="space-y-4">
                <label class="text-sm font-bold text-gray-700">Nomor Resi Pengiriman</label>
                <input type="text" name="resi" required placeholder="Contoh: JNE123456789" 
                       class="w-full px-4 py-3 bg-gray-50 border border-gray-200 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none transition-all">
            </div>
            
            <div class="mt-8 flex justify-end gap-3">
                <button type="button" onclick="toggleModal()" 
                        class="px-6 py-3 text-gray-600 font-bold hover:bg-gray-100 rounded-xl transition-all">
                    Batal
                </button>
                <button type="submit" 
                        class="px-6 py-3 bg-blue-600 text-white rounded-xl font-bold hover:bg-blue-700 shadow-lg shadow-blue-100 transition-all">
                    Proses & Kirim
                </button>
            </div>
        </form>
    </div>
</div>

<script>
    function toggleDrawer() {
        const drawer = document.getElementById('drawer-detail');
    
        if (drawer.classList.contains('hidden')) {
            drawer.classList.remove('hidden');
            drawer.classList.add('flex');
            // Jangan gunakan overflow hidden pada body jika ingin background tetap interaktif
        } else {
            drawer.classList.add('hidden');
            drawer.classList.remove('flex');
        }
    }

    // Modal Konfirmasi tetap aman
    function toggleModal(orderId = '') {
        const modal = document.getElementById('modal-konfirmasi');
        modal.classList.toggle('hidden');
        if(orderId) {
            let urlTemplate = "{{ route('admin.orders.confirm', ':id') }}";
            document.getElementById('formKonfirmasi').action = urlTemplate.replace(':id', orderId);
        }
    }
</script>

<style>
    .animate-slide-in { animation: slideIn 0.3s ease-out; }
    @keyframes slideIn {
        from { transform: translateX(100%); }
        to { transform: translateX(0); }
    }
</style>
@endsection