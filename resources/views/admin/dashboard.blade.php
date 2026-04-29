@extends('layouts.app')

@section('content')
<div class="p-4 border-2 border-gray-200 border-dashed rounded-lg">
    <div class="grid grid-cols-3 gap-4 mb-4">
        <div class="flex items-center justify-center h-24 rounded bg-gray-50 text-gray-400"> Statistik 1 </div>
        <div class="flex items-center justify-center h-24 rounded bg-gray-50 text-gray-400"> Statistik 2 </div>
        <div class="flex items-center justify-center h-24 rounded bg-gray-50 text-gray-400"> Statistik 3 </div>
    </div>

    <div class="w-full bg-neutral-primary-soft border border-default rounded-base shadow-xs p-4 md:p-6">
      <div class="flex justify-between items-start mb-5">
         <div>
            <h5 class="text-2xl font-semibold text-heading">Rp 4.200.000</h5><p class="text-body">Total Penjualan Minggu Ini</p>
         </div>
      </div>
    
         <div id="area-chart" class="w-full"></div>

        <div class="grid grid-cols-1 items-center border-t border-gray-200 justify-between mt-4 pt-4">
            <div class="flex justify-between items-center">
                <button id="dropdownDefaultButton" data-dropdown-toggle="lastDaysdropdown" class="text-sm font-medium text-gray-500 hover:text-gray-900 inline-flex items-center" type="button">
                    Last 7 days <svg class="w-4 h-4 ms-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m19 9-7 7-7-7"/></svg>
                </button>
                <a href="#" class="uppercase text-sm font-semibold inline-flex items-center rounded-lg text-blue-600 hover:text-blue-700 px-3 py-2">
                    Users Report <svg class="w-4 h-4 ms-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 12H5m14 0-4 4m4-4-4-4"/></svg>
                </a>
            </div>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/apexcharts"></script>

<script>
  // Pastikan script ini jalan SETELAH library ApexCharts dimuat
  window.addEventListener("load", function() {
    const options = {
      chart: {
        height: "100%",
        maxWidth: "100%",
        type: "area",
        fontFamily: "Inter, sans-serif",
        toolbar: { show: false },
      },
      tooltip: { enabled: true },
      fill: {
        type: "gradient",
        gradient: { opacityFrom: 0.55, opacityTo: 0, shade: "#1C64F2", gradientToColors: ["#1C64F2"] },
      },
      dataLabels: { enabled: false },
      stroke: { width: 6, curve: "smooth" },
      series: [
        {
          name: "Penjualan (Rp)",
          data: [1500000, 2300000, 1800000, 3500000, 2900000, 4200000], 
          color: "#1A56DB",
        },
      ],
      xaxis: {
        categories: ['Sen', 'Sel', 'Rab', 'Kam', 'Jum', 'Sab'],
      },
    }

    if (document.getElementById("area-chart") && typeof ApexCharts !== 'undefined') {
      const chart = new ApexCharts(document.getElementById("area-chart"), options);
      chart.render();
    }
  });
</script>
@endsection