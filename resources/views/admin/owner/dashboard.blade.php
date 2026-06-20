@extends('layouts.app')

@section('content')
<div class="mb-6">
    <div class="flex items-center gap-2 mb-1">
        <h1 class="text-2xl font-bold text-white">Owner Dashboard</h1>
        <span class="px-2 py-0.5 text-[10px] font-bold text-yellow-500 bg-yellow-500/10 rounded-full uppercase tracking-wider">Owner Only</span>
    </div>
    <p class="text-sm text-gray-400">Ikhtisar bisnis & pengawasan sistem.</p>
</div>

{{-- KPI Cards --}}
<div class="grid grid-cols-2 md:grid-cols-4 gap-4 mb-6">
    <div class="p-5 bg-elvo-surface rounded-xl border border-white/[0.06] card-hover">
        <div class="flex items-center justify-between mb-2">
            <span class="text-[10px] font-bold text-gray-500 uppercase tracking-wider">Revenue Bulan Ini</span>
            <div class="p-2 bg-green-500/10 rounded-lg text-green-500">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
            </div>
        </div>
        <p class="text-2xl font-bold text-white">Rp {{ number_format($revenueThisMonth, 0, ',', '.') }}</p>
        <div class="flex items-center gap-1 mt-1">
            @if($revenueGrowth > 0)
            <span class="text-xs text-green-500">+{{ $revenueGrowth }}%</span>
            @elseif($revenueGrowth < 0)
            <span class="text-xs text-red-500">{{ $revenueGrowth }}%</span>
            @else
            <span class="text-xs text-gray-500">—</span>
            @endif
            <span class="text-xs text-gray-500">vs bulan lalu</span>
        </div>
    </div>
    <div class="p-5 bg-elvo-surface rounded-xl border border-white/[0.06] card-hover">
        <div class="flex items-center justify-between mb-2">
            <span class="text-[10px] font-bold text-gray-500 uppercase tracking-wider">Total Pesanan</span>
            <div class="p-2 bg-purple-500/10 rounded-lg text-purple-500">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"/></svg>
            </div>
        </div>
        <p class="text-2xl font-bold text-white">{{ number_format($totalOrders) }}</p>
        <span class="text-xs text-gray-500">Semua status</span>
    </div>
    <div class="p-5 bg-elvo-surface rounded-xl border border-white/[0.06] card-hover">
        <div class="flex items-center justify-between mb-2">
            <span class="text-[10px] font-bold text-gray-500 uppercase tracking-wider">Pelanggan</span>
            <div class="p-2 bg-cyan-500/10 rounded-lg text-cyan-500">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
            </div>
        </div>
        <p class="text-2xl font-bold text-white">{{ number_format($totalCustomers) }}</p>
        <span class="text-xs text-gray-500">Terdaftar</span>
    </div>
    <div class="p-5 bg-elvo-surface rounded-xl border border-white/[0.06] card-hover">
        <div class="flex items-center justify-between mb-2">
            <span class="text-[10px] font-bold text-gray-500 uppercase tracking-wider">Stok Menipis</span>
            <div class="p-2 bg-red-500/10 rounded-lg text-red-500">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L4.082 16.5c-.77.833.192 2.5 1.732 2.5z"/></svg>
            </div>
        </div>
        <p class="text-2xl font-bold text-white">{{ $lowStockCount }}</p>
        <span class="text-xs text-red-400">Stok &lt; 5</span>
    </div>
</div>

<div class="grid grid-cols-1 lg:grid-cols-3 gap-6 mb-6">
    {{-- Revenue Chart --}}
    <div class="lg:col-span-2 p-6 bg-elvo-surface rounded-xl border border-white/[0.06] card-hover">
        <div class="flex items-center justify-between mb-4">
            <h3 class="font-bold text-white">Revenue 7 Hari Terakhir</h3>
            <div class="flex items-center gap-4 text-xs text-gray-500">
                <span class="flex items-center gap-1"><span class="w-3 h-0.5 bg-elvo-primary inline-block"></span> Revenue</span>
                <span class="flex items-center gap-1"><span class="w-3 h-0.5 bg-cyan-400 inline-block"></span> Orders</span>
            </div>
        </div>
        <div id="revenueChart" class="h-72"></div>
    </div>

    {{-- Order Status Donut --}}
    <div class="p-6 bg-elvo-surface rounded-xl border border-white/[0.06] card-hover">
        <h3 class="font-bold text-white mb-4">Status Pesanan</h3>
        <div id="orderStatusChart" class="h-72"></div>
    </div>
</div>

<div class="grid grid-cols-1 lg:grid-cols-3 gap-6 mb-6">
    {{-- Low Stock Products --}}
    <div class="p-6 bg-elvo-surface rounded-xl border border-white/[0.06] card-hover">
        <div class="flex items-center justify-between mb-4">
            <h3 class="font-bold text-white">Stok Menipis</h3>
            <a href="{{ route('admin.owner.stock-history') }}" class="text-xs text-[#8b7df2] hover:text-[#9a8df4]">Riwayat →</a>
        </div>
        <div class="space-y-3">
            @forelse($lowStockProducts as $p)
            <div class="flex items-center justify-between p-3 bg-red-500/5 rounded-lg border border-red-500/10">
                <div class="flex items-center gap-3">
                    <div class="w-8 h-8 rounded-lg bg-red-500/10 flex items-center justify-center text-red-400 shrink-0">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01"/></svg>
                    </div>
                    <div>
                        <p class="text-sm font-medium text-white truncate max-w-[180px]">{{ $p->name }}</p>
                        <p class="text-xs {{ $p->stock <= 2 ? 'text-red-400 font-semibold' : 'text-orange-400' }}">Stok: {{ $p->stock }}</p>
                    </div>
                </div>
            </div>
            @empty
            <div class="flex flex-col items-center py-6 text-gray-500">
                <svg class="w-10 h-10 mb-2 opacity-30" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                <p class="text-sm italic">Semua stok aman ✅</p>
            </div>
            @endforelse
        </div>
    </div>

    {{-- Quick Actions --}}
    <div class="p-6 bg-elvo-surface rounded-xl border border-white/[0.06] card-hover">
        <h3 class="font-bold text-white mb-4">Aksi Cepat</h3>
        <div class="space-y-3">
            <a href="{{ route('admin.owner.manage-admins') }}" class="flex items-center gap-3 p-3 bg-elvo-primary/5 rounded-xl border border-elvo-primary/10 hover:bg-elvo-primary/10 transition group">
                <div class="p-2 bg-elvo-primary/10 rounded-lg text-elvo-primary group-hover:scale-110 transition">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z"/></svg>
                </div>
                <div>
                    <p class="font-semibold text-white text-sm">Tambah Admin</p>
                    <p class="text-xs text-gray-500">Buat akun staff baru</p>
                </div>
            </a>
            <a href="{{ route('admin.owner.product-reports') }}" class="flex items-center gap-3 p-3 bg-green-500/5 rounded-xl border border-green-500/10 hover:bg-green-500/10 transition group">
                <div class="p-2 bg-green-500/10 rounded-lg text-green-500 group-hover:scale-110 transition">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/></svg>
                </div>
                <div>
                    <p class="font-semibold text-white text-sm">Laporan Produk</p>
                    <p class="text-xs text-gray-500">Best seller & stok</p>
                </div>
            </a>
            <a href="{{ route('admin.owner.financial-reports') }}" class="flex items-center gap-3 p-3 bg-purple-500/5 rounded-xl border border-purple-500/10 hover:bg-purple-500/10 transition group">
                <div class="p-2 bg-purple-500/10 rounded-lg text-purple-500 group-hover:scale-110 transition">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                </div>
                <div>
                    <p class="font-semibold text-white text-sm">Laporan Keuangan</p>
                    <p class="text-xs text-gray-500">Revenue & analisis</p>
                </div>
            </a>
            <a href="{{ route('admin.owner.settings') }}" class="flex items-center gap-3 p-3 bg-yellow-500/5 rounded-xl border border-yellow-500/10 hover:bg-yellow-500/10 transition group">
                <div class="p-2 bg-yellow-500/10 rounded-lg text-yellow-500 group-hover:scale-110 transition">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                </div>
                <div>
                    <p class="font-semibold text-white text-sm">Pengaturan Toko</p>
                    <p class="text-xs text-gray-500">Info toko & penjualan</p>
                </div>
            </a>
        </div>
    </div>

    {{-- Recent Activities --}}
    <div class="p-6 bg-elvo-surface rounded-xl border border-white/[0.06] card-hover">
        <div class="flex items-center justify-between mb-4">
            <h3 class="font-bold text-white">Aktivitas Terkini</h3>
            <a href="{{ route('admin.owner.audit-log') }}" class="text-xs text-[#8b7df2] hover:text-[#9a8df4]">Lihat Semua →</a>
        </div>
        <div class="space-y-3 max-h-[360px] overflow-y-auto">
            @forelse($activities as $activity)
            <div class="flex gap-3">
                <div class="w-2 h-2 mt-2 rounded-full shrink-0
                    @if(str_contains($activity->action, 'created')) bg-elvo-primary
                    @elseif(str_contains($activity->action, 'deleted')) bg-red-500
                    @elseif(str_contains($activity->action, 'updated')) bg-green-500
                    @else bg-gray-500
                    @endif
                "></div>
                <div class="min-w-0">
                    <p class="text-sm text-gray-200 truncate">{{ $activity->description }}</p>
                    <div class="flex items-center gap-2 text-xs text-gray-500">
                        <span>{{ $activity->user?->name ?? 'System' }}</span>
                        <span>•</span>
                        <span>{{ $activity->created_at->diffForHumans() }}</span>
                    </div>
                </div>
            </div>
            @empty
            <p class="text-sm text-gray-500 italic">Belum ada aktivitas.</p>
            @endforelse
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    var chartTheme = { mode: 'dark', palette: 'palette1', monochrome: { enabled: false } };
    var gridStyle = { show: true, borderColor: 'rgba(255,255,255,0.05)', strokeDashArray: 0, position: 'back', xaxis: { lines: { show: false } }, yaxis: { lines: { show: true } } };

    // Revenue Chart
    var revEl = document.getElementById('revenueChart');
    if (revEl && typeof ApexCharts !== 'undefined') {
        var revChart = new ApexCharts(revEl, {
            chart: { type: 'line', height: 280, ...chartTheme, toolbar: { show: false } },
            series: [
                { name: 'Revenue (Rp)', type: 'line', data: @json($revenueChart) },
                { name: 'Orders', type: 'column', data: @json($orderChart) },
            ],
            colors: ['#3b82f6', '#22d3ee'],
            stroke: { curve: 'smooth', width: [3, 0] },
            fill: { type: 'gradient', gradient: { shadeIntensity: 1, opacityFrom: 0.4, opacityTo: 0.1, stops: [0, 90, 100] } },
            xaxis: { categories: @json($chartLabels), labels: { style: { colors: '#6b7280', fontSize: '11px' } }, axisBorder: { show: false }, axisTicks: { show: false } },
            yaxis: [
                { seriesName: 'Revenue (Rp)', labels: { formatter: function(v) { return 'Rp ' + (v / 1000000).toFixed(1) + 'M'; }, style: { colors: '#6b7280' } } },
                { seriesName: 'Orders', opposite: true, labels: { formatter: function(v) { return Math.round(v); }, style: { colors: '#6b7280' } } },
            ],
            grid: gridStyle,
            tooltip: { theme: 'dark', shared: true, intersect: false, y: [
                { formatter: function(v) { return 'Rp ' + Number(v).toLocaleString('id-ID'); } },
                { formatter: function(v) { return v + ' orders'; } },
            ]},
            legend: { show: false },
            dataLabels: { enabled: false },
        });
        revChart.render();
    }

    // Order Status Donut
    var ordEl = document.getElementById('orderStatusChart');
    if (ordEl && typeof ApexCharts !== 'undefined') {
        var ordChart = new ApexCharts(ordEl, {
            chart: { type: 'donut', height: 280, ...chartTheme },
            series: [{{ $activeOrders }}, {{ $completedOrders }}, {{ $shippedOrders }}, {{ $cancelledOrders }}],
            labels: ['Aktif', 'Selesai', 'Dikirim', 'Batal'],
            colors: ['#f97316', '#22c55e', '#3b82f6', '#ef4444'],
            plotOptions: { pie: { donut: { size: '60%', labels: { show: true, total: { show: true, label: 'Total', color: '#fff', formatter: function() { return '{{ $totalOrders }}'; } } } } } },
            dataLabels: { enabled: true, formatter: function(v) { return v > 0 ? Math.round(v) + '%' : ''; } },
            tooltip: { theme: 'dark', y: { formatter: function(v) { return v + ' orders'; } } },
            legend: { position: 'bottom', labels: { colors: '#9ca3af' } },
            stroke: { show: false },
        });
        ordChart.render();
    }
});
</script>
@endpush
