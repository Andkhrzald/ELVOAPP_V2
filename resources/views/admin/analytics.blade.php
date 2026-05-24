@extends('layouts.app')

@section('content')
<div class="mb-6">
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
        <div>
            <h1 class="text-2xl font-bold text-white">Analytics Dashboard</h1>
            <p class="text-sm text-gray-400">Analisis mendalam performa bisnis Elvoapp</p>
        </div>
        <div class="flex items-center gap-3">
            <select id="chartRange"
                class="px-3 py-2 text-sm border border-white/10 rounded-lg bg-[#252525] text-gray-300 focus:ring-blue-500 focus:border-blue-500 cursor-pointer">
                <option value="7">7 Hari Terakhir</option>
                <option value="30" selected>30 Hari Terakhir</option>
                <option value="90">90 Hari Terakhir</option>
                <option value="365">1 Tahun Terakhir</option>
                <option value="custom">Custom Range</option>
            </select>
            <div id="customDateRange" class="hidden items-center gap-2">
                <input type="date" id="startDate"
                    class="px-2 py-1.5 text-sm border border-white/10 rounded-lg bg-[#252525] text-gray-300 focus:ring-blue-500 focus:border-blue-500">
                <span class="text-gray-500">—</span>
                <input type="date" id="endDate"
                    class="px-2 py-1.5 text-sm border border-white/10 rounded-lg bg-[#252525] text-gray-300 focus:ring-blue-500 focus:border-blue-500">
                <button id="applyCustomRange"
                    class="px-3 py-1.5 text-sm font-semibold text-white bg-blue-600 rounded-lg hover:bg-blue-700 transition">
                    Terapkan
                </button>
            </div>
            <div id="dateDisplay" class="text-xs text-gray-500 hidden sm:block"></div>
        </div>
    </div>
</div>

{{-- Error Banner --}}
<div id="analyticsError" class="hidden mb-4 p-4 bg-red-500/10 border border-red-500/20 rounded-lg text-red-400 text-sm"></div>

{{-- Loading --}}
<div id="analyticsLoading" class="flex items-center justify-center py-20">
    <div class="flex flex-col items-center gap-3">
        <svg class="animate-spin h-8 w-8 text-blue-500" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
        </svg>
        <span class="text-sm text-gray-400">Memuat data analytics...</span>
    </div>
</div>

{{-- Analytics Content --}}
<div id="analyticsContent" class="hidden">

    {{-- KPI Cards --}}
    <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-6 gap-3 mb-6" id="kpiCards">
        <div class="p-4 bg-[#1a1a1a] rounded-xl border border-white/5 shadow-sm">
            <span class="text-[10px] font-semibold text-gray-500 uppercase tracking-wider">Total Revenue</span>
            <p class="text-lg font-bold text-white mt-1" id="kpiRevenue">—</p>
            <div class="flex items-center gap-1 mt-1"><span class="text-xs font-medium" id="kpiRevenueGrowth">—</span></div>
        </div>
        <div class="p-4 bg-[#1a1a1a] rounded-xl border border-white/5 shadow-sm">
            <span class="text-[10px] font-semibold text-gray-500 uppercase tracking-wider">Total Orders</span>
            <p class="text-lg font-bold text-white mt-1" id="kpiOrders">—</p>
            <div class="flex items-center gap-1 mt-1"><span class="text-xs font-medium" id="kpiOrderGrowth">—</span></div>
        </div>
        <div class="p-4 bg-[#1a1a1a] rounded-xl border border-white/5 shadow-sm">
            <span class="text-[10px] font-semibold text-gray-500 uppercase tracking-wider">AOV</span>
            <p class="text-lg font-bold text-white mt-1" id="kpiAov">—</p>
            <div class="flex items-center gap-1 mt-1"><span class="text-xs font-medium" id="kpiAovGrowth">—</span></div>
        </div>
        <div class="p-4 bg-[#1a1a1a] rounded-xl border border-white/5 shadow-sm">
            <span class="text-[10px] font-semibold text-gray-500 uppercase tracking-wider">Customers</span>
            <p class="text-lg font-bold text-white mt-1" id="kpiCustomers">—</p>
            <div class="flex items-center gap-1 mt-1"><span class="text-xs font-medium" id="kpiCustomerGrowth">—</span></div>
        </div>
        <div class="p-4 bg-[#1a1a1a] rounded-xl border border-white/5 shadow-sm">
            <span class="text-[10px] font-semibold text-gray-500 uppercase tracking-wider">Rating</span>
            <p class="text-lg font-bold text-white mt-1" id="kpiRating">—</p>
            <div class="flex items-center gap-1 mt-1"><span class="text-xs font-medium text-gray-500">Rata-rata</span></div>
        </div>
        <div class="p-4 bg-[#1a1a1a] rounded-xl border border-white/5 shadow-sm">
            <span class="text-[10px] font-semibold text-gray-500 uppercase tracking-wider">Low Stock</span>
            <p class="text-lg font-bold text-white mt-1" id="kpiLowStock">—</p>
            <div class="flex items-center gap-1 mt-1"><span class="text-xs font-medium text-red-400">Perlu restock</span></div>
        </div>
    </div>

    {{-- Revenue & Orders Trend --}}
    <div class="p-5 bg-[#1a1a1a] rounded-xl border border-white/5 shadow-sm mb-6">
        <div class="flex items-center justify-between mb-4">
            <h3 class="font-bold text-white">Revenue & Orders Trend</h3>
            <div class="flex items-center gap-4 text-xs text-gray-500">
                <span class="flex items-center gap-1"><span class="w-3 h-0.5 bg-blue-500 inline-block"></span> Revenue</span>
                <span class="flex items-center gap-1"><span class="w-3 h-0.5 bg-cyan-400 inline-block"></span> Orders</span>
            </div>
        </div>
        <div id="revenueTrendChart" class="h-80 chart-container"></div>
    </div>

    {{-- Row 2 --}}
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-6">
        <div class="p-5 bg-[#1a1a1a] rounded-xl border border-white/5 shadow-sm">
            <h3 class="font-bold text-white mb-4">Top Products by Revenue</h3>
            <div id="topProductsChart" class="h-72 chart-container"></div>
        </div>
        <div class="p-5 bg-[#1a1a1a] rounded-xl border border-white/5 shadow-sm">
            <h3 class="font-bold text-white mb-4">Sales by Category</h3>
            <div id="categorySalesChart" class="h-72 chart-container"></div>
        </div>
    </div>

    {{-- Row 3 --}}
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-6">
        <div class="p-5 bg-[#1a1a1a] rounded-xl border border-white/5 shadow-sm">
            <h3 class="font-bold text-white mb-4">Order Status Distribution</h3>
            <div id="orderStatusChart" class="h-72 chart-container"></div>
        </div>
        <div class="p-5 bg-[#1a1a1a] rounded-xl border border-white/5 shadow-sm">
            <h3 class="font-bold text-white mb-4">Payment Methods</h3>
            <div id="paymentMethodsChart" class="h-72 chart-container"></div>
        </div>
    </div>

    {{-- Row 4 --}}
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-6">
        <div class="p-5 bg-[#1a1a1a] rounded-xl border border-white/5 shadow-sm">
            <h3 class="font-bold text-white mb-4">Average Order Value Trend</h3>
            <div id="aovTrendChart" class="h-72 chart-container"></div>
        </div>
        <div class="p-5 bg-[#1a1a1a] rounded-xl border border-white/5 shadow-sm">
            <h3 class="font-bold text-white mb-4">New Customers</h3>
            <div id="customerTrendChart" class="h-72 chart-container"></div>
        </div>
    </div>

    {{-- Row 5 --}}
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        <div class="p-5 bg-[#1a1a1a] rounded-xl border border-white/5 shadow-sm">
            <h3 class="font-bold text-white mb-4">Low Stock Alerts</h3>
            <div id="lowStockContainer" class="space-y-3"><p class="text-sm text-gray-500 italic">Memuat...</p></div>
        </div>
        <div class="p-5 bg-[#1a1a1a] rounded-xl border border-white/5 shadow-sm">
            <h3 class="font-bold text-white mb-4">Rating Distribution</h3>
            <div id="ratingDistChart" class="h-72 chart-container"></div>
        </div>
    </div>

</div>
@endsection

@push('scripts')
<script>
// Safety: wait for ApexCharts to load
(function waitForApex(interval) {
    if (typeof ApexCharts !== 'undefined') {
        initAnalytics();
        return;
    }
    interval = interval || 0;
    if (interval > 10000) {
        showError('ApexCharts gagal dimuat. Segarkan halaman.');
        return;
    }
    setTimeout(function(){ waitForApex(interval + 200); }, 200);
})();

var charts = {};

// =============================================
// Chart Theme
// =============================================
var chartTheme = { mode: 'dark', palette: 'palette1', monochrome: { enabled: false } };
var gridStyle = { show: true, borderColor: 'rgba(255,255,255,0.05)', strokeDashArray: 0, position: 'back', xaxis: { lines: { show: false } }, yaxis: { lines: { show: true } } };

function initAnalytics() {
    fetchAnalytics('30');
    bindEvents();
}

function bindEvents() {
    var rangeSelect = document.getElementById('chartRange');
    var customRange = document.getElementById('customDateRange');

    rangeSelect.addEventListener('change', function() {
        if (this.value === 'custom') {
            customRange.classList.remove('hidden');
            customRange.style.display = 'flex';
        } else {
            customRange.classList.add('hidden');
            customRange.style.display = 'none';
            fetchAnalytics(this.value);
        }
    });

    document.getElementById('applyCustomRange').addEventListener('click', function() {
        var start = document.getElementById('startDate').value;
        var end = document.getElementById('endDate').value;
        if (start && end) {
            fetchAnalytics('custom', start, end);
        } else {
            showError('Pilih tanggal mulai dan selesai.');
        }
    });
}

// =============================================
// Fetch Data → Init Charts → Update UI
// =============================================
function fetchAnalytics(range, startDate, endDate) {
    showLoading();

    var url = '/admin/dashboard/analytics-data?range=' + range;
    if (range === 'custom' && startDate && endDate) {
        url += '&start_date=' + startDate + '&end_date=' + endDate;
    }

    fetch(url)
        .then(function(r) { return r.json(); })
        .then(function(d) {
            if (d.error) {
                showError(d.error);
                return;
            }
            hideLoading();
            hideError();
            initAllCharts(d);
            updateKPIs(d.summary, d.comparison);
            updateLowStock(d.lowStock);
            updateDateDisplay(range, startDate, endDate);
        })
        .catch(function(err) {
            console.error('Fetch error:', err);
            showError('Gagal mengambil data. Cek koneksi atau coba refresh.');
        });
}

// =============================================
// Safe Chart Initializer
// =============================================
function safeChart(elId, config, name) {
    var el = document.getElementById(elId);
    if (!el) { console.warn('Element #' + elId + ' not found'); return null; }
    try {
        if (charts[name]) { charts[name].destroy(); }
        var c = new ApexCharts(el, config);
        c.render();
        charts[name] = c;
        return c;
    } catch(e) {
        console.error('Chart "' + name + '" error:', e.message);
        el.innerHTML = '<div class="flex items-center justify-center h-full text-red-400 text-xs">⚠ ' + name + ': ' + e.message + '</div>';
        return null;
    }
}

function safeUpdate(name, fn) {
    try {
        if (charts[name]) fn(charts[name]);
    } catch(e) {
        console.error('Update "' + name + '" error:', e.message);
    }
}

// =============================================
// Init All Charts with Data
// =============================================
function initAllCharts(d) {
    // ----- Revenue Trend (Dual Axis) -----
    safeChart('revenueTrendChart', {
        chart: { type: 'line', height: 320, ...chartTheme, toolbar: { show: true }, zoom: { enabled: true } },
        series: [
            { name: 'Revenue (Rp)', type: 'line', data: d.revenueTrend.revenue },
            { name: 'Orders', type: 'column', data: d.revenueTrend.orders },
        ],
        colors: ['#3b82f6', '#22d3ee'],
        stroke: { curve: 'smooth', width: [3, 0] },
        fill: { type: 'gradient', gradient: { shadeIntensity: 1, opacityFrom: 0.4, opacityTo: 0.1, stops: [0, 90, 100] } },
        xaxis: { categories: d.revenueTrend.labels, labels: { style: { colors: '#6b7280', fontSize: '11px' } }, axisBorder: { show: false }, axisTicks: { show: false } },
        yaxis: [
            { seriesName: 'Revenue (Rp)', labels: { formatter: function(v) { return 'Rp ' + (v / 1000000).toFixed(1) + 'M'; }, style: { colors: '#6b7280' } } },
            { seriesName: 'Orders', opposite: true, labels: { formatter: function(v) { return Math.round(v); }, style: { colors: '#6b7280' } } },
        ],
        grid: gridStyle,
        tooltip: { theme: 'dark', shared: true, intersect: false, y: [
            { formatter: function(v) { return 'Rp ' + Number(v).toLocaleString('id-ID'); } },
            { formatter: function(v) { return v + ' orders'; } },
        ]},
        legend: { show: true, labels: { colors: '#9ca3af' }, position: 'top', horizontalAlign: 'right' },
        dataLabels: { enabled: false },
    }, 'revenueTrend');

    // ----- Top Products -----
    safeChart('topProductsChart', {
        chart: { type: 'bar', height: 280, ...chartTheme, toolbar: { show: false } },
        series: [{ name: 'Revenue', data: d.topProducts.map(function(p) { return p.revenue; }) }],
        colors: ['#3b82f6'],
        plotOptions: { bar: { horizontal: true, borderRadius: 4 } },
        xaxis: { categories: d.topProducts.map(function(p) { return p.name.length > 25 ? p.name.substring(0, 22) + '...' : p.name; }), labels: { style: { colors: '#6b7280', fontSize: '11px' }, formatter: function(v) { return 'Rp ' + (v / 1000000).toFixed(1) + 'M'; } } },
        yaxis: { labels: { style: { colors: '#6b7280', fontSize: '11px' }, maxWidth: 180 } },
        grid: gridStyle,
        tooltip: { theme: 'dark', y: { formatter: function(v) { return 'Rp ' + Number(v).toLocaleString('id-ID') + ' (' + d.topProducts.find(function(p) { return p.revenue === v; })?.percentage + '%)'; } } },
        dataLabels: { enabled: true, formatter: function(v, opt) { return d.topProducts[opt.dataPointIndex]?.percentage + '%'; }, style: { colors: ['#9ca3af'], fontSize: '10px' } },
    }, 'topProducts');

    // ----- Category Sales (Donut) -----
    safeChart('categorySalesChart', {
        chart: { type: 'donut', height: 280, ...chartTheme },
        series: d.categorySales.map(function(c) { return c.revenue; }),
        labels: d.categorySales.map(function(c) { return c.category; }),
        colors: ['#3b82f6', '#8b5cf6', '#f97316', '#22c55e', '#ec4899'],
        plotOptions: { pie: { donut: { size: '55%', labels: { show: true, total: { show: true, label: 'Total', color: '#fff', formatter: function() { var t = d.summary.totalRevenue; return 'Rp ' + t.toLocaleString('id-ID'); } } } } } },
        dataLabels: { enabled: true, formatter: function(v, opt) { return opt.w.globals.series[opt.seriesIndex] ? Math.round(v) + '%' : ''; } },
        tooltip: { theme: 'dark', y: { formatter: function(v) { return 'Rp ' + Number(v).toLocaleString('id-ID'); } } },
        legend: { position: 'bottom', labels: { colors: '#9ca3af' } },
        stroke: { show: false },
    }, 'categorySales');

    // ----- Order Status (Pie) -----
    safeChart('orderStatusChart', {
        chart: { type: 'pie', height: 280, ...chartTheme },
        series: d.orderStatus.map(function(s) { return s.count; }),
        labels: d.orderStatus.map(function(s) { return s.status; }),
        colors: d.orderStatus.map(function(s) { return s.color; }),
        dataLabels: { enabled: true, formatter: function(v, opt) { return opt.w.globals.series[opt.seriesIndex] + ' (' + v.toFixed(1) + '%)'; } },
        tooltip: { theme: 'dark', y: { formatter: function(v) { return v + ' orders'; } } },
        legend: { position: 'bottom', labels: { colors: '#9ca3af' } },
        stroke: { show: false },
    }, 'orderStatus');

    // ----- Payment Methods (Pie) -----
    safeChart('paymentMethodsChart', {
        chart: { type: 'pie', height: 280, ...chartTheme },
        series: d.paymentMethods.map(function(p) { return p.total; }),
        labels: d.paymentMethods.map(function(p) { return p.method; }),
        colors: ['#3b82f6', '#8b5cf6', '#f97316', '#22c55e', '#ec4899', '#6366f1'],
        dataLabels: { enabled: true, formatter: function(v, opt) { return 'Rp ' + (opt.w.globals.series[opt.seriesIndex] / 1000000).toFixed(1) + 'M'; } },
        tooltip: { theme: 'dark', y: { formatter: function(v) { return 'Rp ' + Number(v).toLocaleString('id-ID'); } } },
        legend: { position: 'bottom', labels: { colors: '#9ca3af' } },
        stroke: { show: false },
    }, 'paymentMethods');

    // ----- AOV Trend -----
    safeChart('aovTrendChart', {
        chart: { type: 'line', height: 280, ...chartTheme, toolbar: { show: false }, zoom: { enabled: false } },
        series: [{ name: 'AOV (Rp)', data: d.aovTrend.data }],
        colors: ['#22c55e'],
        stroke: { curve: 'smooth', width: 3 },
        fill: { type: 'gradient', gradient: { shadeIntensity: 1, opacityFrom: 0.3, opacityTo: 0 } },
        xaxis: { categories: d.aovTrend.labels, labels: { style: { colors: '#6b7280', fontSize: '11px' } }, axisBorder: { show: false }, axisTicks: { show: false } },
        yaxis: { labels: { formatter: function(v) { return 'Rp ' + Number(v).toLocaleString('id-ID'); }, style: { colors: '#6b7280' } } },
        grid: gridStyle,
        tooltip: { theme: 'dark', y: { formatter: function(v) { return 'Rp ' + Number(v).toLocaleString('id-ID'); } } },
        markers: { size: 4, colors: ['#22c55e'], strokeColors: '#22c55e', strokeWidth: 2, hover: { size: 6 } },
        dataLabels: { enabled: false },
    }, 'aovTrend');

    // ----- Customer Trend -----
    safeChart('customerTrendChart', {
        chart: { type: 'bar', height: 280, ...chartTheme, toolbar: { show: false } },
        series: [{ name: 'New Customers', data: d.customerTrend.data }],
        colors: ['#8b5cf6'],
        plotOptions: { bar: { borderRadius: 4, columnWidth: '60%' } },
        fill: { type: 'gradient', gradient: { shadeIntensity: 1, opacityFrom: 0.85, opacityTo: 0.4 } },
        xaxis: { categories: d.customerTrend.labels, labels: { style: { colors: '#6b7280', fontSize: '11px' } }, axisBorder: { show: false }, axisTicks: { show: false } },
        yaxis: { labels: { formatter: function(v) { return Math.round(v); }, style: { colors: '#6b7280' } }, min: 0 },
        grid: gridStyle,
        tooltip: { theme: 'dark', y: { formatter: function(v) { return v + ' customers'; } } },
        dataLabels: { enabled: true, formatter: function(v) { return v > 0 ? v : ''; }, style: { colors: ['#9ca3af'], fontSize: '10px' }, offsetY: -20 },
    }, 'customerTrend');

    // ----- Rating Distribution -----
    safeChart('ratingDistChart', {
        chart: { type: 'bar', height: 280, ...chartTheme, toolbar: { show: false } },
        series: [{ name: 'Reviews', data: d.ratingDistribution.map(function(r) { return r.count; }) }],
        colors: ['#f59e0b'],
        plotOptions: { bar: { horizontal: true, borderRadius: 4, barHeight: '60%' } },
        xaxis: { categories: d.ratingDistribution.map(function(r) { return r.rating + ' Star'; }), labels: { style: { colors: '#6b7280', fontSize: '12px' } } },
        yaxis: { labels: { style: { colors: '#6b7280', fontSize: '12px' }, formatter: function(v) { return '⭐ ' + v; } } },
        grid: { show: true, borderColor: 'rgba(255,255,255,0.05)', xaxis: { lines: { show: true } }, yaxis: { lines: { show: false } } },
        tooltip: { theme: 'dark', y: { formatter: function(v) { return v + ' reviews'; } } },
        dataLabels: { enabled: true, formatter: function(v) { return v > 0 ? v : ''; }, style: { colors: ['#f59e0b'], fontSize: '12px', fontWeight: 'bold' } },
    }, 'ratingDist');
}

// =============================================
// Update Functions
// =============================================
function updateKPIs(summary, comparison) {
    document.getElementById('kpiRevenue').textContent = 'Rp ' + Number(summary.totalRevenue).toLocaleString('id-ID');
    document.getElementById('kpiOrders').textContent = summary.totalOrders.toLocaleString();
    document.getElementById('kpiAov').textContent = 'Rp ' + Number(summary.aov).toLocaleString('id-ID');
    document.getElementById('kpiCustomers').textContent = summary.newCustomers;
    document.getElementById('kpiRating').textContent = summary.avgRating > 0 ? summary.avgRating + ' ⭐' : '—';
    document.getElementById('kpiLowStock').textContent = summary.lowStockCount;

    setGrowth('kpiRevenueGrowth', comparison.revenueGrowth);
    setGrowth('kpiOrderGrowth', comparison.orderGrowth);
    setGrowth('kpiAovGrowth', comparison.aovGrowth);
    setGrowth('kpiCustomerGrowth', comparison.customerGrowth);
}

function setGrowth(elId, value) {
    var el = document.getElementById(elId);
    if (value > 0) el.innerHTML = '<span class="text-green-500">+' + value + '% ↑</span> <span class="text-gray-500">vs sebelumnya</span>';
    else if (value < 0) el.innerHTML = '<span class="text-red-500">' + value + '% ↓</span> <span class="text-gray-500">vs sebelumnya</span>';
    else el.innerHTML = '<span class="text-gray-500">— vs sebelumnya</span>';
}

function updateLowStock(products) {
    var container = document.getElementById('lowStockContainer');
    if (!products || products.length === 0) {
        container.innerHTML = '<div class="flex flex-col items-center py-8 text-gray-500"><svg class="w-12 h-12 mb-2 opacity-30" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg><p class="text-sm italic">Semua stok aman ✅</p></div>';
        return;
    }
    container.innerHTML = products.map(function(p) {
        return '<div class="flex items-center justify-between p-3 bg-red-500/5 rounded-lg border border-red-500/10">' +
            '<div class="flex items-center gap-3">' +
            '<div class="w-8 h-8 rounded-lg bg-red-500/10 flex items-center justify-center text-red-400">' +
            '<svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L4.082 16.5c-.77.833.192 2.5 1.732 2.5z"/></svg></div>' +
            '<div><p class="text-sm font-medium text-white">' + p.name + '</p><p class="text-xs ' + (p.stock <= 2 ? 'text-red-400 font-semibold' : 'text-orange-400') + '">Stok: ' + p.stock + (p.stock <= 2 ? ' — Segera restock!' : '') + '</p></div></div>' +
            '<a href="/admin/products/edit/' + p.id + '" class="text-xs text-blue-400 hover:text-blue-300">Edit</a></div>';
    }).join('');
}

function updateDateDisplay(range, startDate, endDate) {
    var el = document.getElementById('dateDisplay');
    if (range === 'custom' && startDate && endDate) {
        el.textContent = formatDate(startDate) + ' — ' + formatDate(endDate);
        el.classList.remove('hidden');
    } else {
        el.textContent = range + '-hari terakhir';
        el.classList.remove('hidden');
    }
}

function formatDate(str) {
    var d = new Date(str + 'T00:00:00');
    return d.toLocaleDateString('id-ID', { day: 'numeric', month: 'short', year: 'numeric' });
}

// =============================================
// UI Helpers
// =============================================
function showLoading() {
    document.getElementById('analyticsLoading').classList.remove('hidden');
    document.getElementById('analyticsContent').classList.add('hidden');
}
function hideLoading() {
    document.getElementById('analyticsLoading').classList.add('hidden');
    document.getElementById('analyticsContent').classList.remove('hidden');
}
function showError(msg) {
    var el = document.getElementById('analyticsError');
    el.textContent = msg;
    el.classList.remove('hidden');
    hideLoading();
}
function hideError() {
    document.getElementById('analyticsError').classList.add('hidden');
}
</script>
@endpush