@extends('layouts.app')

@section('content')
<div class="mb-6">
    <h1 class="text-2xl font-bold text-gray-800">Dashboard Overview</h1>
    <p class="text-sm text-gray-500">Selamat datang kembali, Andikha! Berikut adalah performa Elvoapp hari ini.</p>
</div>

<div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4 mb-6">
    <div class="p-5 bg-white rounded-xl border border-gray-200 shadow-sm">
        <div class="flex items-center justify-between mb-2">
            <span class="text-xs font-bold text-gray-400 uppercase">Total Pendapatan</span>
            <div class="p-2 bg-green-50 rounded-lg text-green-600">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
            </div>
        </div>
        <p class="text-2xl font-bold text-gray-900">Rp 42.500.000</p>
        <span class="text-xs text-green-500 font-medium">↑ 12% dari bulan lalu</span>
    </div>

    <div class="p-5 bg-white rounded-xl border border-gray-200 shadow-sm">
        <div class="flex items-center justify-between mb-2">
            <span class="text-xs font-bold text-gray-400 uppercase">Pesanan Aktif</span>
            <div class="p-2 bg-blue-50 rounded-lg text-elvo">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"/></svg>
            </div>
        </div>
        <p class="text-2xl font-bold text-gray-900">84</p>
        <span class="text-xs text-elvo font-medium">Perlu segera diproses</span>
    </div>

    <div class="p-5 bg-white rounded-xl border border-gray-200 shadow-sm">
        <div class="flex items-center justify-between mb-2">
            <span class="text-xs font-bold text-gray-400 uppercase">Total Produk</span>
            <div class="p-2 bg-orange-50 rounded-lg text-orange-500">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/></svg>
            </div>
        </div>
        <p class="text-2xl font-bold text-gray-900">1,205</p>
        <span class="text-xs text-gray-500">Dikelola di gudang</span>
    </div>

    <div class="p-5 bg-white rounded-xl border border-gray-200 shadow-sm">
        <div class="flex items-center justify-between mb-2">
            <span class="text-xs font-bold text-gray-400 uppercase">Pelanggan Baru</span>
            <div class="p-2 bg-purple-50 rounded-lg text-purple-600">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"/></svg>
            </div>
        </div>
        <p class="text-2xl font-bold text-gray-900">48</p>
        <span class="text-xs text-green-500 font-medium">↑ 5% minggu ini</span>
    </div>
</div>

<div class="lg:col-span-2 p-6 bg-white rounded-xl border border-gray-200 shadow-sm">
    <div class="flex justify-between items-center mb-6">
        <div>
            <h3 class="font-bold text-gray-800 text-lg">Grafik Penjualan Elvoapp</h3>
            <p class="text-xs text-gray-400">Pantau performa penjualan produk kamu</p>
        </div>
        
        <div class="relative">
            <select class="block w-full pl-3 pr-10 py-2 text-sm border-gray-200 focus:outline-none focus:ring-elvo focus:border-elvo rounded-lg bg-gray-50 text-gray-600 font-medium cursor-pointer transition">
                <option value="3">3 Hari Terakhir</option>
                <option value="7" selected>7 Hari Terakhir</option>
                <option value="30">30 Hari Terakhir (Sebulan)</option>
                <option value="90">3 Bulan Terakhir</option>
            </select>
        </div>
    </div>
    
    <div class="h-72">
        <canvas id="salesChart"></canvas>
    </div>
</div>

    <div class="p-6 bg-white rounded-xl border border-gray-200 shadow-sm">
        <h3 class="font-bold text-gray-800 mb-4">Aktivitas Terkini</h3>
        <div class="space-y-4">
            <div class="flex gap-3">
                <div class="w-2 h-2 mt-2 rounded-full bg-elvo"></div>
                <div>
                    <p class="text-sm text-gray-800">Pesanan baru <span class="font-bold">#TRX-99</span></p>
                    <p class="text-xs text-gray-500">2 menit yang lalu</p>
                </div>
            </div>
            <div class="flex gap-3">
                <div class="w-2 h-2 mt-2 rounded-full bg-green-500"></div>
                <div>
                    <p class="text-sm text-gray-800">Stok MacBook Pro diperbarui</p>
                    <p class="text-xs text-gray-500">1 jam yang lalu</p>
                </div>
            </div>
            <div class="flex gap-3">
                <div class="w-2 h-2 mt-2 rounded-full bg-orange-400"></div>
                <div>
                    <p class="text-sm text-gray-800">Pembayaran diproses Siti</p>
                    <p class="text-xs text-gray-500">3 jam yang lalu</p>
                </div>
            </div>
        </div>
        <button class="w-full mt-6 py-2 text-sm font-semibold text-elvo bg-blue-50 rounded-lg hover:bg-elvo hover:text-white transition">
            Lihat Semua Aktivitas
        </button>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    const ctx = document.getElementById('salesChart').getContext('2d');
    
    // Warna Elvo (disesuaikan dengan hex biru elvo kamu)
    const elvoColor = '#1E40AF'; 

    new Chart(ctx, {
        type: 'line',
        data: {
            labels: ['Sen', 'Sel', 'Rab', 'Kam', 'Jum', 'Sab', 'Min'],
            datasets: [{
                label: 'Penjualan (Rp)',
                data: [1200000, 1900000, 1500000, 2500000, 2200000, 3000000, 4100000],
                borderColor: elvoColor,
                backgroundColor: 'rgba(30, 64, 175, 0.1)',
                borderWidth: 3,
                fill: true,
                tension: 0.4, // Membuat garis melengkung smooth
                pointBackgroundColor: elvoColor,
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: { display: false }
            },
            scales: {
                y: {
                    beginAtZero: true,
                    grid: { color: '#f3f4f6' }
                },
                x: {
                    grid: { display: false }
                }
            }
        }
    });
</script>
@endsection