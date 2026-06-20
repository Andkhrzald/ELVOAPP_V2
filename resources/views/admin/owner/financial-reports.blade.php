@extends('layouts.app')

@section('content')
<div class="mb-6">
    <div class="flex items-center gap-2 mb-1">
        <h1 class="text-2xl font-bold text-white">Laporan Keuangan</h1>
        <span class="px-2 py-0.5 text-[10px] font-bold text-yellow-500 bg-yellow-500/10 rounded-full uppercase tracking-wider">Owner Only</span>
    </div>
    <p class="text-sm text-gray-400">Analisis pendapatan, pesanan, dan performa bisnis.</p>
</div>

<div class="flex items-center justify-end mb-4">
    <a href="{{ route('admin.owner.financial-reports.export', request()->query()) }}"
       class="px-5 py-2.5 text-xs font-bold bg-emerald-500/10 text-emerald-400 border border-emerald-500/20 rounded-xl hover:bg-emerald-500/20 transition-all flex items-center gap-2">
        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
        Export Excel
    </a>
</div>

{{-- Filter --}}
<div class="p-4 bg-elvo-surface rounded-xl border border-white/[0.06] mb-6">
    <form method="GET" class="flex flex-wrap items-end gap-3">
        <div>
            <label class="block text-[10px] font-bold text-gray-500 uppercase tracking-widest mb-1">Periode</label>
            <select name="range" id="rangeSelect" onchange="toggleCustom()" class="px-3 py-2 text-sm border border-white/10 rounded-lg bg-[#252525] text-gray-300 focus:ring-elvo-primary focus:border-elvo-primary">
                <option value="7" {{ $range == '7' ? 'selected' : '' }}>7 Hari</option>
                <option value="30" {{ $range == '30' ? 'selected' : '' }}>30 Hari</option>
                <option value="90" {{ $range == '90' ? 'selected' : '' }}>90 Hari</option>
                <option value="365" {{ $range == '365' ? 'selected' : '' }}>1 Tahun</option>
                <option value="custom" {{ $range == 'custom' ? 'selected' : '' }}>Custom</option>
            </select>
        </div>
        <div id="customRange" class="flex items-center gap-2 {{ $range == 'custom' ? '' : 'hidden' }}">
            <div>
                <label class="block text-[10px] font-bold text-gray-500 uppercase tracking-widest mb-1">Dari</label>
                <input type="date" name="start_date" value="{{ request('start_date') }}" class="px-3 py-2 text-sm border border-white/10 rounded-lg bg-[#252525] text-gray-300 focus:ring-elvo-primary">
            </div>
            <span class="text-gray-500 pt-5">—</span>
            <div>
                <label class="block text-[10px] font-bold text-gray-500 uppercase tracking-widest mb-1">Sampai</label>
                <input type="date" name="end_date" value="{{ request('end_date') }}" class="px-3 py-2 text-sm border border-white/10 rounded-lg bg-[#252525] text-gray-300 focus:ring-elvo-primary">
            </div>
        </div>
        <button type="submit" class="px-5 py-2 text-sm font-semibold btn-primary rounded-lg">Terapkan</button>
    </form>
</div>

{{-- KPI Summary --}}
<div class="grid grid-cols-2 md:grid-cols-4 gap-4 mb-6">
    <div class="p-5 bg-elvo-surface rounded-xl border border-white/[0.06] card-hover">
        <span class="text-[10px] font-bold text-gray-500 uppercase tracking-wider">Total Revenue</span>
        <p class="text-xl font-bold text-white mt-1">Rp {{ number_format($totalRevenue, 0, ',', '.') }}</p>
        <div class="flex items-center gap-1 mt-1">
            @if($revGrowth > 0) <span class="text-xs text-green-500">+{{ $revGrowth }}% ↑</span>
            @elseif($revGrowth < 0) <span class="text-xs text-red-500">{{ $revGrowth }}% ↓</span>
            @else <span class="text-xs text-gray-500">—</span>
            @endif
            <span class="text-xs text-gray-500">vs sebelumnya</span>
        </div>
    </div>
    <div class="p-5 bg-elvo-surface rounded-xl border border-white/[0.06] card-hover">
        <span class="text-[10px] font-bold text-gray-500 uppercase tracking-wider">Total Pesanan</span>
        <p class="text-xl font-bold text-white mt-1">{{ number_format($totalOrders) }}</p>
        <div class="flex items-center gap-1 mt-1">
            @if($ordGrowth > 0) <span class="text-xs text-green-500">+{{ $ordGrowth }}% ↑</span>
            @elseif($ordGrowth < 0) <span class="text-xs text-red-500">{{ $ordGrowth }}% ↓</span>
            @else <span class="text-xs text-gray-500">—</span>
            @endif
            <span class="text-xs text-gray-500">vs sebelumnya</span>
        </div>
    </div>
    <div class="p-5 bg-elvo-surface rounded-xl border border-white/[0.06] card-hover">
        <span class="text-[10px] font-bold text-gray-500 uppercase tracking-wider">Selesai</span>
        <p class="text-xl font-bold text-green-500 mt-1">{{ number_format($completedOrders) }}</p>
        <span class="text-xs text-gray-500">Pesanan selesai</span>
    </div>
    <div class="p-5 bg-elvo-surface rounded-xl border border-white/[0.06] card-hover">
        <span class="text-[10px] font-bold text-gray-500 uppercase tracking-wider">AOV</span>
        <p class="text-xl font-bold text-white mt-1">Rp {{ number_format($aov, 0, ',', '.') }}</p>
        <div class="flex items-center gap-1 mt-1">
            @if($aovGrowth > 0) <span class="text-xs text-green-500">+{{ $aovGrowth }}% ↑</span>
            @elseif($aovGrowth < 0) <span class="text-xs text-red-500">{{ $aovGrowth }}% ↓</span>
            @else <span class="text-xs text-gray-500">—</span>
            @endif
            <span class="text-xs text-gray-500">per pesanan</span>
        </div>
    </div>
</div>

<div class="grid grid-cols-1 lg:grid-cols-3 gap-6 mb-6">
    {{-- Revenue Trend Chart --}}
    <div class="lg:col-span-2 p-6 bg-elvo-surface rounded-xl border border-white/[0.06] card-hover">
        <h3 class="font-bold text-white mb-4">Revenue & Orders</h3>
        <div id="revenueChart" class="h-80"></div>
    </div>

    {{-- Order Status --}}
    <div class="p-6 bg-elvo-surface rounded-xl border border-white/[0.06] card-hover">
        <h3 class="font-bold text-white mb-4">Status Pesanan</h3>
        <div id="orderStatusChart" class="h-72"></div>
    </div>
</div>

<div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-6">
    {{-- Payment Methods --}}
    <div class="p-6 bg-elvo-surface rounded-xl border border-white/[0.06] card-hover">
        <h3 class="font-bold text-white mb-4">Metode Pembayaran</h3>
        <div id="paymentChart" class="h-72"></div>
    </div>

    {{-- Revenue by Category --}}
    <div class="p-6 bg-elvo-surface rounded-xl border border-white/[0.06] card-hover">
        <h3 class="font-bold text-white mb-4">Revenue per Kategori</h3>
        <div id="categoryChart" class="h-72"></div>
    </div>
</div>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    function toggleCustom() {
        var v = document.getElementById('rangeSelect').value;
        document.getElementById('customRange').classList.toggle('hidden', v !== 'custom');
    }

    var theme = { mode: 'dark', palette: 'palette1', monochrome: { enabled: false } };
    var grid = { show: true, borderColor: 'rgba(255,255,255,0.05)', strokeDashArray: 0, position: 'back', xaxis: { lines: { show: false } }, yaxis: { lines: { show: true } } };

    if (typeof ApexCharts !== 'undefined') {
        new ApexCharts(document.getElementById('revenueChart'), {
            chart: { type: 'line', height: 320, ...theme, toolbar: { show: false }, zoom: { enabled: true } },
            series: [
                { name: 'Revenue (Rp)', type: 'line', data: @json($chartRevenue) },
                { name: 'Orders', type: 'column', data: @json($chartOrders) },
            ],
            colors: ['#3b82f6', '#22d3ee'],
            stroke: { curve: 'smooth', width: [3, 0] },
            fill: { type: 'gradient', gradient: { shadeIntensity: 1, opacityFrom: 0.4, opacityTo: 0.1, stops: [0, 90, 100] } },
            xaxis: { categories: @json($chartLabels), labels: { style: { colors: '#6b7280', fontSize: '11px' } }, axisBorder: { show: false }, axisTicks: { show: false } },
            yaxis: [
                { seriesName: 'Revenue', labels: { formatter: function(v) { return 'Rp ' + (v / 1000000).toFixed(1) + 'M'; }, style: { colors: '#6b7280' } } },
                { seriesName: 'Orders', opposite: true, labels: { formatter: function(v) { return Math.round(v); }, style: { colors: '#6b7280' } } },
            ],
            grid: grid,
            tooltip: { theme: 'dark', shared: true, intersect: false, y: [
                { formatter: function(v) { return 'Rp ' + Number(v).toLocaleString('id-ID'); } },
                { formatter: function(v) { return v + ' orders'; } },
            ]},
            legend: { show: true, labels: { colors: '#9ca3af' }, position: 'top', horizontalAlign: 'right' },
            dataLabels: { enabled: false },
        }).render();

        const statusColors = { pending: '#f97316', proses: '#8b5cf6', dikirim: '#6366f1', selesai: '#22c55e', minta_batal: '#f59e0b', batal: '#ef4444', minta_refund: '#ec4899', refund: '#ec4899' };
        const statusLabels = { pending: 'Pending', proses: 'Diproses', dikirim: 'Dikirim', selesai: 'Selesai', minta_batal: 'Minta Batal', batal: 'Batal', minta_refund: 'Minta Refund', refund: 'Refund' };
        var ordStatus = @json($orderStatuses);

        if (ordStatus && ordStatus.length > 0) {
            new ApexCharts(document.getElementById('orderStatusChart'), {
                chart: { type: 'pie', height: 280, ...theme },
                series: ordStatus.map(function(s) { return s.count; }),
                labels: ordStatus.map(function(s) { return statusLabels[s.status] || s.status; }),
                colors: ordStatus.map(function(s) { return statusColors[s.status] || '#6b7280'; }),
                dataLabels: { enabled: true, formatter: function(v, opt) { return opt.w.globals.series[opt.seriesIndex] + ' (' + v.toFixed(1) + '%)'; } },
                tooltip: { theme: 'dark', y: { formatter: function(v) { return v + ' orders'; } } },
                legend: { position: 'bottom', labels: { colors: '#9ca3af' } },
                stroke: { show: false },
            }).render();
        }

        var payData = @json($paymentMethods);
        if (payData && payData.length > 0) {
            new ApexCharts(document.getElementById('paymentChart'), {
                chart: { type: 'donut', height: 280, ...theme },
                series: payData.map(function(p) { return p.total; }),
                labels: payData.map(function(p) { return p.method; }),
                colors: ['#3b82f6', '#8b5cf6', '#f97316', '#22c55e', '#ec4899'],
                dataLabels: { enabled: true, formatter: function(v) { return v > 0 ? v.toFixed(1) + '%' : ''; } },
                tooltip: { theme: 'dark', y: { formatter: function(v) { return 'Rp ' + Number(v).toLocaleString('id-ID'); } } },
                legend: { position: 'bottom', labels: { colors: '#9ca3af' } },
                stroke: { show: false },
            }).render();
        }

        var catData = @json($categoryRevenue);
        if (catData && catData.length > 0) {
            new ApexCharts(document.getElementById('categoryChart'), {
                chart: { type: 'bar', height: 280, ...theme, toolbar: { show: false } },
                series: [{ name: 'Revenue', data: catData.map(function(c) { return c.total; }) }],
                colors: ['#8b5cf6'],
                plotOptions: { bar: { horizontal: true, borderRadius: 4 } },
                xaxis: { categories: catData.map(function(c) { return c.category; }), labels: { style: { colors: '#6b7280', fontSize: '11px' }, formatter: function(v) { return 'Rp ' + (v / 1000000).toFixed(1) + 'M'; } } },
                yaxis: { labels: { style: { colors: '#6b7280', fontSize: '11px' } } },
                grid: grid,
                tooltip: { theme: 'dark', y: { formatter: function(v) { return 'Rp ' + Number(v).toLocaleString('id-ID'); } } },
                dataLabels: { enabled: false },
            }).render();
        }
    }
});
</script>
@endpush
