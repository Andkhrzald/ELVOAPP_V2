@extends('layouts.app')

@section('content')
<div class="mb-6">
    <div class="flex items-center gap-2 mb-1">
        <h1 class="text-2xl font-bold text-white">Dashboard Overview</h1>
        @if(Auth::user()->role !== 'owner')
        <span class="px-2 py-0.5 text-[10px] font-bold text-blue-500 bg-blue-500/10 rounded-full uppercase tracking-wider">Admin</span>
        @endif
    </div>
    <p class="text-sm text-gray-400">Selamat datang kembali, {{ Auth::user()->name }}! Berikut adalah performa Elvoapp hari ini.</p>
</div>

<div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4 mb-6">
    {{-- Card 1: Total Pendapatan --}}
    <div class="p-5 bg-[#1a1a1a] rounded-xl border border-white/5 shadow-sm">
        <div class="flex items-center justify-between mb-2">
            <span class="text-xs font-bold text-gray-500 uppercase">Total Pendapatan</span>
            <div class="p-2 bg-green-500/10 rounded-lg text-green-500">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
            </div>
        </div>
        <p class="text-2xl font-bold text-white">Rp {{ number_format($totalRevenue, 0, ',', '.') }}</p>
        <span class="text-xs text-green-500 font-medium">Dari pesanan selesai & dikirim</span>
    </div>

    {{-- Card 2: Pesanan Aktif --}}
    <div class="p-5 bg-[#1a1a1a] rounded-xl border border-white/5 shadow-sm">
        <div class="flex items-center justify-between mb-2">
            <span class="text-xs font-bold text-gray-500 uppercase">Pesanan Aktif</span>
            <div class="p-2 bg-blue-500/10 rounded-lg text-blue-500">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"/></svg>
            </div>
        </div>
        <p class="text-2xl font-bold text-white">{{ $activeOrders }}</p>
        <span class="text-xs text-blue-500 font-medium">Perlu segera diproses</span>
    </div>

    {{-- Card 3: Total Produk --}}
    <div class="p-5 bg-[#1a1a1a] rounded-xl border border-white/5 shadow-sm">
        <div class="flex items-center justify-between mb-2">
            <span class="text-xs font-bold text-gray-500 uppercase">Total Produk</span>
            <div class="p-2 bg-orange-500/10 rounded-lg text-orange-500">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/></svg>
            </div>
        </div>
        <p class="text-2xl font-bold text-white">{{ number_format($totalProducts) }}</p>
        <span class="text-xs text-gray-500">Dikelola di gudang</span>
    </div>

    {{-- Card 4: Pelanggan Baru --}}
    <div class="p-5 bg-[#1a1a1a] rounded-xl border border-white/5 shadow-sm">
        <div class="flex items-center justify-between mb-2">
            <span class="text-xs font-bold text-gray-500 uppercase">Pelanggan Baru</span>
            <div class="p-2 bg-purple-500/10 rounded-lg text-purple-500">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"/></svg>
            </div>
        </div>
        <p class="text-2xl font-bold text-white">{{ $newCustomers }}</p>
        <span class="text-xs text-green-500 font-medium">7 hari terakhir</span>
    </div>
</div>

<div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
    {{-- Chart Section --}}
    <div class="lg:col-span-2 p-6 bg-[#1a1a1a] rounded-xl border border-white/5 shadow-sm">
        <div class="flex justify-between items-center mb-6">
            <div>
                <h3 class="font-bold text-white text-lg">Grafik Penjualan Elvoapp</h3>
                <p class="text-xs text-gray-500">Pantau performa penjualan produk kamu</p>
            </div>
            
            <select class="block pl-3 pr-10 py-2 text-sm border-white/10 focus:ring-blue-500 focus:border-blue-500 rounded-lg bg-[#252525] text-gray-300 font-medium cursor-pointer transition">
                <option value="7" selected>7 Hari Terakhir</option>
            </select>
        </div>
        
        <div class="h-72">
            <canvas id="salesChart"></canvas>
        </div>
    </div>

    {{-- Activity Section --}}
    <div class="p-6 bg-[#1a1a1a] rounded-xl border border-white/5 shadow-sm">
        <h3 class="font-bold text-white mb-4">Aktivitas Terkini</h3>
        <div class="space-y-4">
            @forelse($activities as $activity)
            <div class="flex gap-3">
                <div class="w-2 h-2 mt-2 rounded-full 
                    @if($activity->action === 'order_created') bg-blue-500
                    @elseif($activity->action === 'stock_updated') bg-green-500
                    @elseif($activity->action === 'payment_confirmed') bg-orange-400
                    @elseif($activity->action === 'order_shipped') bg-purple-500
                    @elseif($activity->action === 'user_registered') bg-cyan-500
                    @elseif($activity->action === 'product_created') bg-yellow-500
                    @elseif($activity->action === 'order_completed') bg-emerald-500
                    @else bg-gray-500
                    @endif
                "></div>
                <div>
                    <p class="text-sm text-gray-200">{{ $activity->description }}</p>
                    <p class="text-xs text-gray-500">{{ $activity->created_at->diffForHumans() }}</p>
                </div>
            </div>
            @empty
            <p class="text-sm text-gray-500 italic">Belum ada aktivitas.</p>
            @endforelse
        </div>
        <a href="{{ route('admin.transaksi') }}" class="block w-full mt-6 py-2 text-sm font-semibold text-blue-400 bg-blue-500/10 rounded-lg hover:bg-blue-500 hover:text-white transition text-center">
            Lihat Semua Aktivitas
        </a>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    const ctx = document.getElementById('salesChart').getContext('2d');
    
    // Warna Biru Elvo
    const elvoColor = '#3b82f6'; 

    // Data dari controller (real database)
    const chartLabels = @json($chartLabels);
    const chartData = @json($chartData);

    new Chart(ctx, {
        type: 'line',
        data: {
            labels: chartLabels,
            datasets: [{
                label: 'Penjualan (Rp)',
                data: chartData,
                borderColor: elvoColor,
                backgroundColor: 'rgba(59, 130, 246, 0.1)',
                borderWidth: 3,
                fill: true,
                tension: 0.4,
                pointBackgroundColor: elvoColor,
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: { display: false },
                tooltip: {
                    callbacks: {
                        label: function(context) {
                            return 'Rp ' + context.parsed.y.toLocaleString('id-ID');
                        }
                    }
                }
            },
            scales: {
                y: {
                    beginAtZero: true,
                    grid: { color: 'rgba(255, 255, 255, 0.05)' },
                    ticks: { 
                        color: '#6b7280',
                        callback: function(value) {
                            return 'Rp ' + (value / 1000000).toFixed(1) + 'M';
                        }
                    }
                },
                x: {
                    grid: { display: false },
                    ticks: { color: '#6b7280' }
                }
            }
        }
    });
</script>
@endsection