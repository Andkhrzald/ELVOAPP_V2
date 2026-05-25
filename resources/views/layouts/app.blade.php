<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Elvoapp</title>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-elvo-bg text-white font-sans">

<nav class="fixed top-0 z-50 w-full bg-elvo-surface/80 backdrop-blur-xl border-b border-white/[0.06]">
  <div class="px-3 py-3 lg:px-5 lg:pl-3">
    <div class="flex items-center justify-between">
      <div class="flex items-center justify-start rtl:justify-end">
        <button data-drawer-target="logo-sidebar" data-drawer-toggle="logo-sidebar" aria-controls="logo-sidebar" type="button" class="inline-flex items-center p-2 text-sm text-gray-400 rounded-lg sm:hidden hover:bg-white/[0.03] focus:outline-none focus:ring-2 focus:ring-gray-700">
            <span class="sr-only">Open sidebar</span>
            <svg class="w-6 h-6" aria-hidden="true" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
               <path clip-rule="evenodd" fill-rule="evenodd" d="M2 4.75A.75.75 0 012.75 4h14.5a.75.75 0 010 1.5H2.75A.75.75 0 012 4.75zm0 10.5a.75.75 0 01.75-.75h7.5a.75.75 0 010 1.5h-7.5a.75.75 0 01-.75-.75zM2 10a.75.75 0 01.75-.75h14.5a.75.75 0 010 1.5H2.75A.75.75 0 012 10z"></path>
            </svg>
         </button>
        <a href="{{ route('admin.dashboard') }}" class="flex ms-2 md:me-24">
          <span class="self-center text-xl sm:text-2xl whitespace-nowrap text-elvo-primary font-bold tracking-tight">ELVO ADMIN</span>
        </a>
      </div>
      <div class="flex items-center gap-3">
          @if(Auth::user()->role === 'owner')
          <span class="px-2 py-0.5 text-[10px] font-bold text-yellow-500 bg-yellow-500/10 rounded-full uppercase tracking-wider hidden sm:inline">Owner</span>
          @endif
          <div class="flex items-center ms-3 relative group">
                <div class="flex items-center gap-3 cursor-pointer">
                    <span class="text-sm font-mono text-gray-300">{{ Auth::user()->name }}</span>
                    <div class="w-8 h-8 bg-elvo-primary rounded-full flex items-center justify-center text-white text-xs">{{ strtoupper(substr(Auth::user()->name, 0, 1)) }}</div>
                </div>
                <div class="absolute right-0 top-full mt-2 w-48 py-2 bg-elvo-elevated/90 backdrop-blur-xl border border-white/[0.06] rounded-xl shadow-xl opacity-0 invisible group-hover:opacity-100 group-hover:visible transition-all z-50">
                    <div class="px-4 py-2 border-b border-white/10">
                        <p class="text-sm text-white font-medium">{{ Auth::user()->name }}</p>
                        <p class="text-xs text-gray-500">{{ Auth::user()->email }}</p>
                    </div>
                    <form method="POST" action="{{ route('logout') }}" class="p-2">
                        @csrf
                        <button type="submit" class="flex items-center gap-2 w-full px-3 py-2 text-sm text-red-400 hover:bg-red-500/10 rounded-lg transition">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/></svg>
                            Logout
                        </button>
                    </form>
                </div>
          </div>
        </div>
    </div>
  </div>
</nav>

<aside id="logo-sidebar" class="fixed top-0 left-0 z-40 w-64 h-full transition-transform -translate-x-full sm:translate-x-0" aria-label="Sidebar">
   <div class="h-full px-3 py-4 overflow-y-auto bg-elvo-surface border-e border-white/[0.06] pt-20 shadow-[4px_0_30px_rgba(0,0,0,0.3)]">
      <ul class="space-y-1 font-medium">
   <li>
      <a href="{{ route('admin.dashboard') }}" class="flex items-center p-2 text-Bold rounded-lg hover:bg-white/[0.03] group {{ request()->routeIs('admin.dashboard') && !request()->routeIs('admin.dashboard.analytics') ? 'bg-elvo-elevated border-l-4 border-elvo-primary shadow-[0_0_20px_rgba(124,109,240,0.12)]' : '' }}">
         <svg class="w-5 h-5 text-gray-400 transition duration-75 group-hover:text-elvo-primary" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/></svg>
          <span class="ms-3 text-sm tracking-wide">Dashboard</span>
      </a>
   </li>
   <li>
      <a href="{{ route('admin.dashboard.analytics') }}" class="flex items-center p-2 text-white rounded-lg hover:bg-white/[0.03] group {{ request()->routeIs('admin.dashboard.analytics') ? 'bg-elvo-elevated border-l-4 border-elvo-primary shadow-[0_0_20px_rgba(124,109,240,0.12)]' : '' }}">
         <svg class="w-5 h-5 text-gray-400 transition duration-75 group-hover:text-cyan-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/></svg>
          <span class="ms-3 text-sm tracking-wide">Analytics</span>
      </a>
   </li>

   <li class="pt-4 pb-1">
      <span class="flex items-center gap-2 px-2 text-xs font-semibold text-gray-500 uppercase tracking-wider"><span class="w-1.5 h-1.5 rounded-full bg-elvo-primary"></span>Katalog & Transaksi</span>
   </li>

   <li>
      <a href="/admin/products" class="flex items-center p-2 text-white rounded-lg hover:bg-white/[0.03] group {{ Request::is('admin/products*') ? 'bg-elvo-elevated border-l-4 border-elvo-primary shadow-[0_0_20px_rgba(124,109,240,0.12)]' : '' }}">
         <svg class="w-5 h-5 text-gray-400 transition duration-75 group-hover:text-elvo-primary" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/></svg>
          <span class="ms-3 text-sm tracking-wide">Produk Elvo</span>
      </a>
   </li>

   <li class="pt-4 pb-1">
      <span class="flex items-center gap-2 px-2 text-xs font-semibold text-gray-500 uppercase tracking-wider"><span class="w-1.5 h-1.5 rounded-full bg-elvo-secondary"></span>Manajemen Penjualan</span>
   </li>

   <li>
      <a href="{{ route('admin.pesanan-masuk') }}" class="flex items-center p-2 text-white rounded-lg hover:bg-white/[0.03] group {{ request()->routeIs('admin.pesanan-masuk') ? 'bg-elvo-elevated border-l-4 border-elvo-primary' : '' }}">
         <svg class="w-5 h-5 text-gray-400 group-hover:text-elvo-primary" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"/>
         </svg>
           <span class="ms-3 text-sm tracking-wide">Pesanan Masuk</span>
          @php $pendingOrders = \App\Models\Order::whereIn('status', ['pending', 'minta_batal', 'minta_refund'])->count(); @endphp
          @if($pendingOrders > 0)
          <span class="inline-flex items-center justify-center w-5 h-5 ms-3 text-[10px] font-bold text-white bg-elvo-rose rounded-full animate-badge-pulse">{{ $pendingOrders }}</span>
          @endif
      </a>
   </li>

   <li>
      <a href="{{ route('admin.transaksi') }}" class="flex items-center p-2 text-white rounded-lg hover:bg-white/[0.03] group {{ request()->routeIs('admin.transaksi') ? 'bg-elvo-elevated border-l-4 border-elvo-primary' : '' }}">
         <svg class="w-5 h-5 text-gray-400 group-hover:text-elvo-primary" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01"/>
         </svg>
          <span class="ms-3 text-sm tracking-wide">Riwayat Transaksi</span>
      </a>
   </li>

   <li class="pt-4 pb-1">
      <span class="flex items-center gap-2 px-2 text-xs font-semibold text-gray-500 uppercase tracking-wider"><span class="w-1.5 h-1.5 rounded-full bg-elvo-sky"></span>User & System</span>
   </li>

   <li>
      <a href="{{ route('admin.pelanggan') }}" class="flex items-center p-2 text-white rounded-lg hover:bg-white/[0.03] group {{ Request::is('admin/pelanggan*') ? 'bg-elvo-elevated border-l-4 border-elvo-primary shadow-[0_0_20px_rgba(124,109,240,0.12)]' : '' }}">
         <svg class="w-5 h-5 text-gray-400 transition duration-75 group-hover:text-elvo-primary" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"/></svg>
          <span class="ms-3 text-sm tracking-wide">Pelanggan</span>
      </a>
   </li>
   <li>
      <a href="{{ route('admin.reviews') }}" class="flex items-center p-2 text-white rounded-lg hover:bg-white/[0.03] group {{ Request::is('admin/reviews*') ? 'bg-elvo-elevated border-l-4 border-elvo-amber' : '' }}">
         <svg class="w-5 h-5 text-gray-400 transition duration-75 group-hover:text-yellow-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11.049 2.927c.3-.921 1.603-.921 1.902 0l1.519 4.674a1 1 0 00.95.69h4.915c.969 0 1.371 1.24.588 1.81l-3.976 2.888a1 1 0 00-.363 1.118l1.518 4.674c.3.922-.755 1.688-1.538 1.118l-3.976-2.888a1 1 0 00-1.176 0l-3.976 2.888c-.783.57-1.838-.197-1.538-1.118l1.518-4.674a1 1 0 00-.363-1.118l-3.976-2.888c-.784-.57-.38-1.81.588-1.81h4.914a1 1 0 00.951-.69l1.519-4.674z"/></svg>
          <span class="ms-3 text-sm tracking-wide">Review Pelanggan</span>
         @php $reviewCount = \App\Models\Review::count(); @endphp
         @if($reviewCount > 0)
         <span class="inline-flex items-center justify-center px-2 py-0.5 ms-3 text-[10px] font-bold text-yellow-500 bg-yellow-500/10 rounded-full">{{ $reviewCount }}</span>
         @endif
      </a>
   </li>
   <li>
      <a href="#" class="flex items-center p-2 text-white rounded-lg hover:bg-white/[0.03] group">
         <svg class="w-5 h-5 text-gray-400 transition duration-75 group-hover:text-elvo-primary" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
          <span class="ms-3 text-sm tracking-wide">Pengaturan</span>
      </a>
   </li>

   @if(Auth::user()->role === 'owner')
   <li class="pt-4 pb-1">
      <span class="flex items-center gap-2 px-2 text-xs font-semibold text-elvo-amber uppercase tracking-wider"><span class="w-1.5 h-1.5 rounded-full bg-elvo-amber"></span>🔐 Owner Panel</span>
   </li>
   <li>
      <a href="{{ route('admin.owner.dashboard') }}" class="flex items-center p-2 text-white rounded-lg hover:bg-white/[0.03] group {{ request()->routeIs('admin.owner.*') ? 'bg-elvo-elevated border-l-4 border-elvo-amber' : '' }}">
         <svg class="w-5 h-5 text-yellow-500 transition duration-75 group-hover:text-yellow-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/></svg>
          <span class="ms-3 text-sm tracking-wide">Owner Dashboard</span>
      </a>
   </li>
   <li>
      <a href="{{ route('admin.owner.manage-admins') }}" class="flex items-center p-2 text-white rounded-lg hover:bg-white/[0.03] group {{ request()->routeIs('admin.owner.manage-admins*') ? 'bg-elvo-elevated border-l-4 border-elvo-amber' : '' }}">
         <svg class="w-5 h-5 text-yellow-500 transition duration-75 group-hover:text-yellow-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z"/></svg>
          <span class="ms-3 text-sm tracking-wide">Manajemen Admin</span>
      </a>
   </li>
   <li>
      <a href="{{ route('admin.owner.audit-log') }}" class="flex items-center p-2 text-white rounded-lg hover:bg-white/[0.03] group {{ request()->routeIs('admin.owner.audit-log') ? 'bg-elvo-elevated border-l-4 border-elvo-amber' : '' }}">
         <svg class="w-5 h-5 text-yellow-500 transition duration-75 group-hover:text-yellow-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"/></svg>
          <span class="ms-3 text-sm tracking-wide">Audit Log</span>
      </a>
   </li>
   @endif

    <li class="pt-4 pb-1 mt-4 border-t border-white/[0.06]">
      <span class="flex items-center gap-2 px-2 text-xs font-semibold text-gray-500 uppercase tracking-wider"><span class="w-1.5 h-1.5 rounded-full bg-gray-500"></span>Akun</span>
   </li>
   <li>
      <form method="POST" action="{{ route('logout') }}" class="p-0 m-0">
         @csrf
         <button type="submit" class="flex items-center w-full p-2 text-red-400 rounded-lg hover:bg-red-500/10 group">
            <svg class="w-5 h-5 text-red-400 transition duration-75 group-hover:text-red-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/></svg>
            <span class="ms-3 text-sm tracking-wide">Logout</span>
         </button>
      </form>
   </li>
</ul>
</div>
</aside>

<div class="p-4 sm:ml-64">
   <div class="p-4 mt-14 animate-fade-up">
      @yield('content')
   </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/flowbite@2.5.2/dist/flowbite.min.js"></script>
@stack('scripts')
</body>
</html>