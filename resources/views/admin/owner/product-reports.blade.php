@extends('layouts.app')

@section('content')
<div class="mb-6">
    <div class="flex items-center gap-2 mb-1">
        <h1 class="text-2xl font-bold text-white">Laporan Produk</h1>
        <span class="px-2 py-0.5 text-[10px] font-bold text-yellow-500 bg-yellow-500/10 rounded-full uppercase tracking-wider">Owner Only</span>
    </div>
    <p class="text-sm text-gray-400">Analisis performa produk, best seller, dan stok.</p>
</div>

{{-- Filter --}}
<div class="p-4 bg-elvo-surface rounded-xl border border-white/[0.06] mb-6">
    <form method="GET" class="flex flex-wrap items-end gap-3">
        <div>
            <label class="block text-[10px] font-bold text-gray-500 uppercase tracking-widest mb-1">Periode</label>
            <select name="range" id="rangeSelect" onchange="toggleCustom()" class="px-3 py-2 text-sm border border-white/10 rounded-lg bg-[#252525] text-gray-300">
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
                <input type="date" name="start_date" value="{{ request('start_date') }}" class="px-3 py-2 text-sm border border-white/10 rounded-lg bg-[#252525] text-gray-300">
            </div>
            <span class="text-gray-500 pt-5">—</span>
            <div>
                <label class="block text-[10px] font-bold text-gray-500 uppercase tracking-widest mb-1">Sampai</label>
                <input type="date" name="end_date" value="{{ request('end_date') }}" class="px-3 py-2 text-sm border border-white/10 rounded-lg bg-[#252525] text-gray-300">
            </div>
        </div>
        <button type="submit" class="px-5 py-2 text-sm font-semibold btn-primary rounded-lg">Terapkan</button>
    </form>
</div>

<div class="grid grid-cols-1 lg:grid-cols-3 gap-6 mb-6">
    {{-- Best Sellers --}}
    <div class="lg:col-span-2 p-6 bg-elvo-surface rounded-xl border border-white/[0.06] card-hover">
        <h3 class="font-bold text-white mb-4">Best Seller (Top 15)</h3>
        @if(count($bestSellers) > 0)
        <div class="overflow-x-auto">
            <table class="w-full text-left text-sm">
                <thead>
                    <tr class="text-gray-500 border-b border-white/10">
                        <th class="pb-3 font-semibold">#</th>
                        <th class="pb-3 font-semibold">Produk</th>
                        <th class="pb-3 font-semibold text-center">Terjual</th>
                        <th class="pb-3 font-semibold text-right">Revenue</th>
                        <th class="pb-3 font-semibold text-right">Kontribusi</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($bestSellers as $i => $item)
                    <tr class="border-b border-white/[0.06] hover:bg-white/5">
                        <td class="py-3 text-gray-500 font-mono">{{ $i + 1 }}</td>
                        <td class="py-3 text-white font-medium">{{ $item['name'] }}</td>
                        <td class="py-3 text-center">
                            <span class="px-2 py-0.5 bg-elvo-primary/10 text-elvo-primary rounded-full text-[10px] font-bold">{{ $item['qty'] }}</span>
                        </td>
                        <td class="py-3 text-right font-mono text-white">Rp {{ number_format($item['revenue'], 0, ',', '.') }}</td>
                        <td class="py-3 text-right">
                            <div class="flex items-center justify-end gap-2">
                                <div class="w-16 h-1.5 bg-white/5 rounded-full overflow-hidden">
                                    <div class="h-full bg-elvo-primary rounded-full" style="width: {{ $item['pct'] }}%"></div>
                                </div>
                                <span class="text-xs text-gray-400 w-10 text-right">{{ $item['pct'] }}%</span>
                            </div>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        @else
        <div class="text-center py-8 text-gray-500 italic">Belum ada data penjualan di periode ini.</div>
        @endif
    </div>

    {{-- Low Stock --}}
    <div class="p-6 bg-elvo-surface rounded-xl border border-white/[0.06] card-hover">
        <h3 class="font-bold text-white mb-4">Stok Menipis</h3>
        @if(count($lowStock) > 0)
        <div class="space-y-3">
            @foreach($lowStock as $p)
            <div class="flex items-center justify-between p-3 bg-red-500/5 rounded-lg border border-red-500/10">
                <div class="flex items-center gap-3 min-w-0">
                    <div class="w-8 h-8 rounded-lg bg-red-500/10 flex items-center justify-center text-red-400 shrink-0">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01"/></svg>
                    </div>
                    <div class="min-w-0">
                        <p class="text-sm font-medium text-white truncate max-w-[180px]">{{ $p->name }}</p>
                        <p class="text-xs {{ $p->stock <= 2 ? 'text-red-400 font-semibold' : 'text-orange-400' }}">Stok: {{ $p->stock }}</p>
                    </div>
                </div>
                <a href="{{ route('admin.products.edit', $p->id) }}" class="text-xs text-[#8b7df2] hover:text-[#9a8df4] shrink-0">Edit</a>
            </div>
            @endforeach
        </div>
        @else
        <div class="flex flex-col items-center py-8 text-gray-500">
            <svg class="w-12 h-12 mb-2 opacity-30" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
            <p class="text-sm italic">Semua stok aman ✅</p>
        </div>
        @endif
    </div>
</div>

{{-- Category Stats --}}
<div class="p-6 bg-elvo-surface rounded-xl border border-white/[0.06] card-hover">
    <div class="flex items-center justify-between mb-4">
        <h3 class="font-bold text-white">Performa per Kategori</h3>
    </div>
    @if(count($categoryStats) > 0)
    <div id="categoryChart" class="h-72"></div>
    @else
    <div class="text-center py-8 text-gray-500 italic">Belum ada data.</div>
    @endif
</div>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    if (typeof ApexCharts === 'undefined') return;

    var theme = { mode: 'dark', palette: 'palette1', monochrome: { enabled: false } };
    var grid = { show: true, borderColor: 'rgba(255,255,255,0.05)', strokeDashArray: 0 };

    var catData = @json($categoryStats);
    if (catData && catData.length > 0) {
        new ApexCharts(document.getElementById('categoryChart'), {
            chart: { type: 'bar', height: 280, ...theme, toolbar: { show: false } },
            series: [
                { name: 'Revenue', data: catData.map(function(c) { return parseInt(c.total_revenue); }) },
                { name: 'Terjual', data: catData.map(function(c) { return parseInt(c.total_qty); }) },
            ],
            colors: ['#8b5cf6', '#22d3ee'],
            plotOptions: { bar: { horizontal: true, borderRadius: 4 } },
            xaxis: { categories: catData.map(function(c) { return c.name; }), labels: { style: { colors: '#6b7280', fontSize: '11px' }, formatter: function(v) { return 'Rp ' + (v / 1000000).toFixed(1) + 'M'; } } },
            yaxis: { labels: { style: { colors: '#6b7280', fontSize: '11px' } } },
            grid: grid,
            tooltip: { theme: 'dark', shared: true, intersect: false },
            legend: { show: true, labels: { colors: '#9ca3af' }, position: 'top', horizontalAlign: 'right' },
            dataLabels: { enabled: false },
        }).render();
    }
});
</script>
@endpush
