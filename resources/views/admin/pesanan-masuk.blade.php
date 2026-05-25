@extends('layouts.app')

@section('content')
<div class="min-h-screen bg-[#0f0f0f] text-gray-300 pb-12">

    {{-- Toast Notification --}}
    @if(session('success') || session('error'))
    <div id="toast-notif" class="fixed top-24 right-8 z-[100] {{ session('success') ? 'bg-green-500/10 border-green-500/30 text-green-400' : 'bg-red-500/10 border-red-500/30 text-red-400' }} border px-6 py-4 rounded-2xl shadow-2xl backdrop-blur-xl flex items-center gap-4 animate-slide-in">
        <div class="w-10 h-10 rounded-full {{ session('success') ? 'bg-green-500/20' : 'bg-red-500/20' }} flex items-center justify-center">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                @if(session('success')) <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7"/> @else <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M6 18L18 6M6 6l12 12"/> @endif
            </svg>
        </div>
        <div>
            <p class="text-xs font-black uppercase tracking-widest opacity-50">{{ session('success') ? 'Success' : 'Warning' }}</p>
            <p class="text-sm font-bold">{{ session('success') ?? session('error') }}</p>
        </div>
        <button onclick="this.parentElement.remove()" class="ml-4 text-white/20 hover:text-white">&times;</button>
    </div>
    @endif

    {{-- Header Section --}}
    <div class="max-w-[1600px] mx-auto pt-10 px-6 lg:px-10 mb-12">
        <div class="flex flex-col lg:flex-row lg:items-end justify-between gap-8">
            <div>
                <p class="text-[10px] font-black text-elvo-primary uppercase tracking-[0.4em] mb-3">Order Management / Logistic Hub</p>
                <h1 class="text-5xl font-black text-white tracking-tighter">Manajemen Pesanan</h1>
                <p class="text-gray-500 text-xs mt-2 uppercase font-bold tracking-widest">Pantau dan kelola pesanan pelanggan Elvo secara real-time</p>
            </div>
            
            <div class="grid grid-cols-2 md:grid-cols-3 gap-4">
                <div class="bg-elvo-surface p-5 rounded-2xl border border-white/[0.06] flex flex-col items-center justify-center text-center group hover:border-elvo-primary/30 transition-all card-hover">
                    <p class="text-[9px] font-black text-gray-500 uppercase tracking-widest mb-1 group-hover:text-elvo-primary">Total Orders</p>
                    <p class="text-2xl font-black text-white leading-none">{{ $totalOrders }}</p>
                </div>
                <div class="bg-elvo-surface p-5 rounded-2xl border border-white/[0.06] flex flex-col items-center justify-center text-center group hover:border-orange-500/30 transition-all card-hover">
                    <p class="text-[9px] font-black text-gray-500 uppercase tracking-widest mb-1 group-hover:text-orange-500">Perlu Tindakan</p>
                    <p class="text-2xl font-black text-orange-500 leading-none">{{ $needAction }}</p>
                </div>
                <div class="hidden md:flex bg-elvo-primary p-5 rounded-2xl shadow-xl shadow-[#7c6df0]/20 flex-col items-center justify-center text-center">
                    <p class="text-[9px] font-black text-gray-400 uppercase tracking-widest mb-1">Status Aktif</p>
                    <p class="text-2xl font-black text-white leading-none capitalize">{{ str_replace('_', ' ', $status) }}</p>
                </div>
            </div>
        </div>
    </div>

    <div class="max-w-[1600px] mx-auto px-6 lg:px-10 grid grid-cols-12 gap-8">
        
        {{-- Sidebar Section --}}
        <div class="col-span-12 lg:col-span-3 space-y-6">
            <div class="bg-elvo-surface p-6 rounded-[2rem] border border-white/[0.06] sticky top-24">
                <form action="{{ route('admin.pesanan-masuk') }}" method="GET" class="space-y-8">
                    {{-- Search --}}
                    <div>
                        <label class="block text-[10px] font-black text-gray-500 uppercase tracking-widest mb-3">Pencarian Cepat</label>
                        <div class="relative group">
                            <input type="text" name="search" value="{{ request('search') }}" placeholder="Order ID / Nama / HP" 
                                class="w-full bg-elvo-bg border border-white/[0.06] rounded-xl px-5 py-3 text-sm text-white focus:border-elvo-primary outline-none transition-all placeholder-gray-700">
                            <button type="submit" class="absolute right-4 top-1/2 -translate-y-1/2 text-gray-500 group-focus-within:text-elvo-primary transition-colors">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" stroke-width="3"/></svg>
                            </button>
                        </div>
                    </div>

                    {{-- Status Filter --}}
                    <div>
                        <label class="block text-[10px] font-black text-gray-500 uppercase tracking-widest mb-4">Pipeline Status</label>
                        <div class="space-y-2">
                            @php
                            $tabs = [
                                'pending'      => ['label' => 'Pending Request'],
                                'proses'       => ['label' => 'Processing'],
                                'dikirim'      => ['label' => 'On Delivery'],
                                'selesai'      => ['label' => 'Completed'],
                                'minta_batal'  => ['label' => 'Cancellation'],
                                'batal'        => ['label' => 'Canceled'],
                                'minta_refund' => ['label' => 'Refund Request'],
                                'refund'       => ['label' => 'Refunded'],
                            ];
                            $tabActiveStyles = [
                                'pending'      => 'bg-elvo-primary/10 border-elvo-primary/50 shadow-[0_0_15px_rgba(124,109,240,0.15)]',
                                'proses'       => 'bg-purple-500/10 border-purple-500/50 shadow-[0_0_15px_rgba(168,85,247,0.15)]',
                                'dikirim'      => 'bg-indigo-500/10 border-indigo-500/50 shadow-[0_0_15px_rgba(99,102,241,0.15)]',
                                'selesai'      => 'bg-green-500/10 border-green-500/50 shadow-[0_0_15px_rgba(34,197,94,0.15)]',
                                'minta_batal'  => 'bg-orange-500/10 border-orange-500/50 shadow-[0_0_15px_rgba(249,115,22,0.15)]',
                                'batal'        => 'bg-red-500/10 border-red-500/50 shadow-[0_0_15px_rgba(239,68,68,0.15)]',
                                'minta_refund' => 'bg-amber-500/10 border-amber-500/50 shadow-[0_0_15px_rgba(245,158,11,0.15)]',
                                'refund'       => 'bg-pink-500/10 border-pink-500/50 shadow-[0_0_15px_rgba(236,72,153,0.15)]',
                            ];
                            $tabDotStyles = [
                                'pending'      => 'bg-elvo-primary shadow-[0_0_10px_rgba(124,109,240,0.5)]',
                                'proses'       => 'bg-purple-500 shadow-[0_0_10px_rgba(168,85,247,0.5)]',
                                'dikirim'      => 'bg-indigo-500 shadow-[0_0_10px_rgba(99,102,241,0.5)]',
                                'selesai'      => 'bg-green-500 shadow-[0_0_10px_rgba(34,197,94,0.5)]',
                                'minta_batal'  => 'bg-orange-500 shadow-[0_0_10px_rgba(249,115,22,0.5)]',
                                'batal'        => 'bg-red-500 shadow-[0_0_10px_rgba(239,68,68,0.5)]',
                                'minta_refund' => 'bg-amber-500 shadow-[0_0_10px_rgba(245,158,11,0.5)]',
                                'refund'       => 'bg-pink-500 shadow-[0_0_10px_rgba(236,72,153,0.5)]',
                            ];
                            @endphp
                            @foreach($tabs as $key => $tab)
                            <a href="?status={{ $key }}&search={{ request('search') }}" 
                                class="group flex items-center justify-between p-4 rounded-2xl border transition-all {{ $status == $key ? $tabActiveStyles[$key] : 'bg-elvo-bg border-white/[0.06] hover:border-white/20' }}">
                                <div class="flex items-center gap-3">
                                    <div class="w-2 h-2 rounded-full {{ $status == $key ? $tabDotStyles[$key] : 'bg-gray-700' }}"></div>
                                    <span class="text-xs font-bold {{ $status == $key ? 'text-white' : 'text-gray-500 group-hover:text-gray-300' }}">{{ $tab['label'] }}</span>
                                </div>
                                @if(($statusCounts[$key] ?? 0) > 0)
                                <span class="px-2 py-0.5 rounded-lg text-[9px] font-black {{ $status == $key ? 'bg-white text-black' : 'bg-white/5 text-gray-600' }}">{{ $statusCounts[$key] }}</span>
                                @endif
                            </a>
                            @endforeach
                        </div>
                    </div>

                    <a href="{{ route('admin.pesanan-masuk') }}" class="block w-full py-4 text-center text-[10px] font-black text-gray-500 uppercase tracking-[0.3em] hover:text-white transition-colors">Reset All Filters</a>
                </form>
            </div>
        </div>

        {{-- Main Content Section --}}
        <div class="col-span-12 lg:col-span-9 space-y-6">
            @forelse($orders as $order)
            <div class="group bg-elvo-surface rounded-[2rem] border border-white/[0.06] overflow-hidden hover:border-elvo-primary/20 transition-all shadow-2xl relative">
                {{-- Glowing indicator --}}
                <div class="absolute top-0 left-0 bottom-0 w-1 {{ $status == 'pending' ? 'bg-elvo-primary' : ($status == 'proses' ? 'bg-purple-500' : ($status == 'selesai' ? 'bg-green-500' : 'bg-gray-700')) }}"></div>

                {{-- Order Header --}}
                <div class="p-8 flex flex-col md:flex-row justify-between items-start md:items-center gap-6 border-b border-white/[0.06]">
                    <div class="flex items-center gap-5">
                        <div class="w-14 h-14 rounded-2xl bg-white/5 border border-white/[0.06] flex items-center justify-center text-xl font-black text-white group-hover:bg-elvo-primary group-hover:border-elvo-primary transition-all duration-500">
                            {{ substr($order->user->name ?? 'U', 0, 1) }}
                        </div>
                        <div>
                            <div class="flex items-center gap-3">
                                <h3 class="text-lg font-black text-white leading-none">{{ $order->user->name ?? 'Unknown Customer' }}</h3>
                                <span class="px-2 py-0.5 bg-elvo-primary/10 text-elvo-primary text-[8px] font-black uppercase tracking-widest rounded border border-elvo-primary/20">Verified Buyer</span>
                            </div>
                            <p class="text-xs text-gray-500 font-bold mt-2 font-mono">ORDER ID: <span class="text-gray-300">#{{ $order->order_number }}</span> · <span class="text-gray-600 uppercase">{{ $order->created_at->format('M d, Y · H:i') }}</span></p>
                        </div>
                    </div>
                    
                    <div class="text-right">
                        <p class="text-[9px] font-black text-gray-600 uppercase tracking-widest mb-1">Valuation</p>
                        <p class="text-2xl font-black text-white tracking-tighter">Rp {{ number_format($order->total_price, 0, ',', '.') }}</p>
                    </div>
                </div>

                {{-- Order Body --}}
                <div class="p-8 grid grid-cols-1 md:grid-cols-3 gap-10">
                    {{-- Contact Info --}}
                    <div>
                        <h4 class="text-[10px] font-black text-gray-600 uppercase tracking-widest mb-4 flex items-center gap-2">
                            <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z" stroke-width="2"/></svg>
                            Customer Intelligence
                        </h4>
                        <div class="space-y-3">
                            <div class="flex items-center gap-3">
                                <div class="w-8 h-8 rounded-lg bg-elvo-bg flex items-center justify-center text-xs text-gray-500 border border-white/[0.06]">PH</div>
                                <p class="text-xs font-bold text-gray-300">{{ $order->user->phone ?? '-' }}</p>
                            </div>
                            <div class="flex items-start gap-3">
                                <div class="w-8 h-8 rounded-lg bg-elvo-bg flex items-center justify-center text-xs text-gray-500 border border-white/[0.06] shrink-0">AD</div>
                                <p class="text-[11px] font-bold text-gray-500 leading-relaxed">{{ $order->user->address ?? 'No Address Provided' }}</p>
                            </div>
                        </div>
                    </div>

                    {{-- Products --}}
                    <div>
                        <h4 class="text-[10px] font-black text-gray-600 uppercase tracking-widest mb-4 flex items-center gap-2">
                            <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4" stroke-width="2"/></svg>
                            Manifest Assets
                        </h4>
                        <div class="space-y-3">
                            @foreach($order->items as $item)
                            <div class="flex items-center gap-3 group/item">
                                <div class="w-10 h-10 rounded-xl bg-white/5 overflow-hidden border border-white/[0.06] flex-shrink-0">
                                    <img src="{{ asset('uploads/' . $item->product->image) }}" class="w-full h-full object-cover opacity-60 group-hover/item:opacity-100 transition-opacity">
                                </div>
                                <div>
                                    <p class="text-xs font-black text-gray-300">{{ $item->product->name }}</p>
                                    <p class="text-[10px] text-gray-600 font-bold uppercase tracking-tighter">{{ $item->quantity }} Units · <span class="text-elvo-primary/60">Rp {{ number_format($item->price, 0, ',', '.') }}</span></p>
                                </div>
                            </div>
                            @endforeach
                        </div>
                    </div>

                    {{-- Logistics --}}
                    <div>
                        <h4 class="text-[10px] font-black text-gray-600 uppercase tracking-widest mb-4 flex items-center gap-2">
                            <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" stroke-width="2"/></svg>
                            Logistics Metadata
                        </h4>
                        <div class="space-y-4">
                            <div class="bg-elvo-bg p-3 rounded-xl border border-white/[0.06]">
                                <p class="text-[8px] font-black text-gray-600 uppercase mb-1">Gateway</p>
                                <p class="text-[10px] font-black text-white uppercase">{{ $order->payment_method ?? 'CREDIT/DEBIT' }}</p>
                            </div>
                            <div class="bg-elvo-bg p-3 rounded-xl border border-white/[0.06]">
                                <p class="text-[8px] font-black text-gray-600 uppercase mb-1">Carrier Service</p>
                                <p class="text-[10px] font-black text-elvo-primary uppercase">{{ $order->shipping_method ?? 'PENDING ASSIGNMENT' }}</p>
                            </div>
                            @if($order->no_resi)
                            <div class="bg-elvo-primary/10 p-3 rounded-xl border border-elvo-primary/20">
                                <p class="text-[8px] font-black text-elvo-primary uppercase mb-1">Tracking Number</p>
                                <p class="text-xs font-black text-white font-mono tracking-widest">{{ $order->no_resi }}</p>
                            </div>
                            @endif
                        </div>
                    </div>
                </div>

                {{-- Status Messages (Batal/Refund) --}}
                @if(in_array($order->status, ['minta_batal', 'minta_refund', 'batal', 'refund']))
                <div class="mx-8 mb-8 p-6 rounded-2xl {{ in_array($order->status, ['minta_batal', 'batal']) ? 'bg-red-500/5 border border-red-500/10' : 'bg-pink-500/5 border border-pink-500/10' }}">
                    <div class="flex items-center gap-3 mb-3">
                        <div class="w-2 h-2 rounded-full {{ in_array($order->status, ['minta_batal', 'batal']) ? 'bg-red-500' : 'bg-pink-500' }} animate-pulse"></div>
                        <h5 class="text-[10px] font-black uppercase tracking-widest {{ in_array($order->status, ['minta_batal', 'batal']) ? 'text-red-500' : 'text-pink-500' }}">
                            {{ $order->status === 'minta_batal' ? 'Cancellation Requested' : ($order->status === 'minta_refund' ? 'Refund Requested' : 'Historical Action Note') }}
                        </h5>
                    </div>
                    <p class="text-xs text-gray-400 font-bold italic leading-relaxed">"{{ $order->cancel_reason ?? $order->refund_reason ?? 'No additional notes provided by customer.' }}"</p>
                </div>
                @endif

                {{-- Action Bar --}}
                <div class="px-8 py-5 bg-white/[0.02] border-t border-white/[0.06] flex flex-wrap items-center justify-between gap-4">
                    <div class="flex items-center gap-4">
                        <span class="text-[10px] font-black text-gray-600 uppercase tracking-widest">Active Operations:</span>
                        
                        @if($order->status === 'pending')
                        <form action="{{ route('admin.orders.accept', $order->id) }}" method="POST">
                            @csrf
                            <button class="px-6 py-2.5 btn-primary text-white text-[10px] font-black uppercase tracking-widest rounded-xl transition-all shadow-xl shadow-[#7c6df0]/20">
                                Confirm & Process
                            </button>
                        </form>
                        @endif

                        @if($order->status === 'proses')
                        <button onclick="openShipModal({{ $order->id }}, '{{ $order->order_number }}')" class="px-6 py-2.5 bg-purple-600 hover:bg-purple-500 text-white text-[10px] font-black uppercase tracking-widest rounded-xl transition-all shadow-xl shadow-purple-900/20">
                            Dispatch Order
                        </button>
                        @endif

                        @if($order->status === 'dikirim')
                        <form action="{{ route('admin.orders.complete', $order->id) }}" method="POST">
                            @csrf
                            <button class="px-6 py-2.5 bg-gradient-to-br from-[#4ade80] to-[#22c55e] text-white text-[10px] font-black uppercase tracking-widest rounded-xl transition-all duration-300 shadow-[0_4px_20px_rgba(74,222,128,0.25)] hover:shadow-[0_6px_30px_rgba(74,222,128,0.4)] hover:from-[#22c55e] hover:to-[#16a34a]">
                                Confirm Receipt
                            </button>
                        </form>
                        @endif

                        @if($order->status === 'minta_batal')
                        <div class="flex gap-2">
                            <form action="{{ route('admin.orders.confirm-cancel', $order->id) }}" method="POST">
                                @csrf
                                <button class="px-6 py-2.5 btn-danger text-[10px] font-black uppercase tracking-widest">Accept Cancellation</button>
                            </form>
                            <form action="{{ route('admin.orders.reject-cancel', $order->id) }}" method="POST">
                                @csrf
                                <button class="px-6 py-2.5 bg-white/5 hover:bg-white/10 text-gray-400 text-[10px] font-black uppercase tracking-widest rounded-xl border border-white/[0.06] transition-all">Decline Request</button>
                            </form>
                        </div>
                        @endif

                        @if($order->status === 'minta_refund')
                        <div class="flex gap-2">
                            <form action="{{ route('admin.orders.confirm-refund', $order->id) }}" method="POST">
                                @csrf
                                <button class="px-6 py-2.5 bg-pink-600 hover:bg-pink-500 text-white text-[10px] font-black uppercase tracking-widest rounded-xl transition-all">Authorize Refund</button>
                            </form>
                            <form action="{{ route('admin.orders.reject-refund', $order->id) }}" method="POST">
                                @csrf
                                <button class="px-6 py-2.5 bg-white/5 hover:bg-white/10 text-gray-400 text-[10px] font-black uppercase tracking-widest rounded-xl border border-white/[0.06] transition-all">Decline Refund</button>
                            </form>
                        </div>
                        @endif

                        @if($order->status === 'selesai') <span class="text-[10px] font-black text-green-500 uppercase tracking-widest border border-green-500/20 px-4 py-2 rounded-xl bg-green-500/5">Manifest Fulfilled</span> @endif
                        @if($order->status === 'batal') <span class="text-[10px] font-black text-red-500 uppercase tracking-widest border border-red-500/20 px-4 py-2 rounded-xl bg-red-500/5">Order Terminated</span> @endif
                        @if($order->status === 'refund') <span class="text-[10px] font-black text-pink-500 uppercase tracking-widest border border-pink-500/20 px-4 py-2 rounded-xl bg-pink-500/5">Funds Reverted</span> @endif
                    </div>

                    <button class="text-[10px] font-black text-gray-600 uppercase tracking-widest hover:text-white transition-colors flex items-center gap-2">
                        View Audit Log
                        <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M9 5l7 7-7 7" stroke-width="3"/></svg>
                    </button>
                </div>
            </div>
            @empty
            <div class="bg-elvo-surface rounded-[2rem] border border-white/[0.06] p-20 text-center">
                <div class="w-20 h-20 bg-elvo-bg rounded-full flex items-center justify-center mx-auto mb-6">
                    <svg class="w-8 h-8 text-gray-700" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4" stroke-width="2"/></svg>
                </div>
                <p class="text-gray-500 text-sm font-bold italic uppercase tracking-widest">No matching manifests found for this status.</p>
                <a href="{{ route('admin.pesanan-masuk') }}" class="inline-block mt-6 text-xs font-black text-elvo-primary uppercase tracking-widest hover:text-white transition-colors">Clear all search & filters</a>
            </div>
            @endforelse

            @if($orders->hasPages())
            <div class="flex justify-center">
                {{ $orders->withQueryString()->links() }}
            </div>
            @endif
        </div>
    </div>
</div>

{{-- Professional Shipping Modal --}}
<div id="ship-modal" class="fixed inset-0 z-[200] hidden items-center justify-center bg-black/80 backdrop-blur-md">
    <div class="relative p-10 w-full max-w-lg bg-elvo-surface rounded-[2.5rem] shadow-2xl border border-white/10 animate-modal-in">
        <div class="flex items-center justify-between mb-8">
            <div>
                <h3 class="text-2xl font-black text-white italic uppercase tracking-tighter">Dispatch Protocol</h3>
                <p class="text-[10px] text-gray-500 mt-1 uppercase font-black tracking-[0.2em]" id="ship-subtitle">Order Reference: #0000</p>
            </div>
            <button onclick="closeShipModal()" class="w-10 h-10 bg-white/5 text-gray-400 hover:text-white rounded-full flex items-center justify-center transition-all">&times;</button>
        </div>

        <form id="ship-form" method="POST" class="space-y-8">
            @csrf
            <div class="space-y-6">
                <div>
                    <label class="block text-[10px] font-black text-gray-500 uppercase tracking-widest mb-3">Service Carrier</label>
                    <div class="relative group">
                        <select name="shipping_method" class="w-full bg-elvo-bg border border-white/[0.06] rounded-2xl px-5 py-4 text-sm font-bold text-white focus:border-elvo-primary outline-none transition-all appearance-none">
                            <option value="JNE Reguler">JNE Reguler</option>
                            <option value="JNE YES (Next Day)">JNE YES (Next Day)</option>
                            <option value="J&T Express Standard">J&T Express Standard</option>
                            <option value="SiCepat Best">SiCepat Best</option>
                            <option value="AnterAja Regular">AnterAja Regular</option>
                        </select>
                        <div class="absolute inset-y-0 right-5 flex items-center pointer-events-none text-gray-600">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M19 9l-7 7-7-7" stroke-width="3"/></svg>
                        </div>
                    </div>
                </div>
                <div>
                    <label class="block text-[10px] font-black text-gray-500 uppercase tracking-widest mb-3">Waybill / Tracking Number</label>
                    <input type="text" name="no_resi" required placeholder="Enter AWB Number..." 
                        class="w-full bg-elvo-bg border border-white/[0.06] rounded-2xl px-5 py-4 text-sm font-black text-white focus:border-elvo-primary outline-none transition-all placeholder-gray-800 font-mono tracking-widest">
                </div>
            </div>
            
            <div class="flex gap-4 pt-4">
                <button type="button" onclick="closeShipModal()" class="flex-1 py-4 bg-white/5 text-gray-500 rounded-2xl font-black text-[10px] uppercase tracking-widest hover:text-white transition-all">Abort</button>
                <button type="submit" class="flex-1 py-4 bg-purple-600 text-white rounded-2xl font-black text-[10px] uppercase tracking-widest hover:bg-purple-500 transition-all shadow-xl shadow-purple-900/20">Initiate Dispatch</button>
            </div>
        </form>
    </div>
</div>

<script>
function openShipModal(id, num) {
    document.getElementById('ship-subtitle').textContent = 'Order Reference: #' + num;
    document.getElementById('ship-form').action = '/admin/orders/' + id + '/ship';
    document.getElementById('ship-modal').classList.remove('hidden');
    document.getElementById('ship-modal').classList.add('flex');
}
function closeShipModal() { 
    document.getElementById('ship-modal').classList.add('hidden'); 
    document.getElementById('ship-modal').classList.remove('flex');
}

// Fade out toasts
setTimeout(() => { 
    const toast = document.getElementById('toast-notif');
    if (toast) {
        toast.style.transition = 'opacity 0.5s ease-out';
        toast.style.opacity = '0';
        setTimeout(() => toast.remove(), 500);
    }
}, 5000);
</script>

<style>
@keyframes slideIn { 
    from { transform: translateX(100%) scale(0.9); opacity: 0; } 
    to { transform: translateX(0) scale(1); opacity: 1; } 
}
.animate-slide-in { animation: slideIn 0.4s cubic-bezier(0.16, 1, 0.3, 1); }

@keyframes modalIn {
    from { transform: scale(0.95); opacity: 0; }
    to { transform: scale(1); opacity: 1; }
}
.animate-modal-in { animation: modalIn 0.3s cubic-bezier(0.16, 1, 0.3, 1); }

/* Custom Scrollbar for modern look */
::-webkit-scrollbar { width: 8px; }
::-webkit-scrollbar-track { bg: #0f0f0f; }
::-webkit-scrollbar-thumb { bg: #1a1a1a; border-radius: 10px; border: 2px solid #0f0f0f; }
::-webkit-scrollbar-thumb:hover { bg: #252525; }
</style>
@endsection