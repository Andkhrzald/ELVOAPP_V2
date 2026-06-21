@extends('layouts.app')

@section('content')
<div class="max-w-4xl">
    {{-- Header --}}
    <div class="mb-8">
        <div class="flex items-center gap-3 mb-2">
            <div class="w-10 h-10 rounded-xl bg-elvo-amber/10 border border-elvo-amber/20 flex items-center justify-center">
                <svg class="w-5 h-5 text-elvo-amber" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
            </div>
            <div>
                <h1 class="text-2xl font-black text-white tracking-tighter">Pengaturan Toko</h1>
                <p class="text-[10px] text-gray-500 uppercase tracking-widest font-bold">Konfigurasi toko online Elvo</p>
            </div>
        </div>
    </div>

    {{-- Read-only notice for non-owner --}}
    @if(!$canEdit)
    <div class="mb-6 p-4 bg-elvo-amber/5 border border-elvo-amber/20 rounded-xl flex items-center gap-3">
        <span class="text-lg">🔒</span>
        <div>
            <p class="text-[10px] font-black text-elvo-amber uppercase tracking-widest">Mode Read-Only</p>
            <p class="text-[10px] text-gray-500 mt-0.5">Anda melihat pengaturan toko dalam mode baca saja. Hanya owner yang dapat mengubah pengaturan.</p>
        </div>
    </div>
    @endif

    @if(session('success'))
    <div class="mb-6 p-4 bg-green-500/10 border border-green-500/20 rounded-xl text-[10px] text-green-400 font-bold uppercase tracking-widest flex items-center gap-3">
        <svg class="w-4 h-4 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
        {{ session('success') }}
    </div>
    @endif

    <form action="{{ route('admin.settings.update') }}" method="POST" enctype="multipart/form-data">
        @csrf
        <div class="p-6 bg-elvo-surface rounded-2xl border border-white/[0.08] card-hover">

            {{-- Informasi Toko --}}
            <div class="flex items-center gap-2 text-[10px] font-black text-elvo-amber uppercase tracking-widest mb-4">
                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/></svg>
                Informasi Toko
            </div>
            <div class="h-px bg-white/[0.06] mb-5"></div>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                <div>
                    <label class="block text-[9px] font-bold text-gray-500 uppercase tracking-widest mb-1.5">Nama Toko</label>
                    <input type="text" name="store_name" value="{{ old('store_name', $settings['store_name'] ?? 'Elvo Store') }}"
                        class="w-full bg-elvo-bg border border-white/[0.06] rounded-xl px-3.5 py-2.5 text-sm text-white focus:border-elvo-amber outline-none transition-all {{ !$canEdit ? 'opacity-60 cursor-not-allowed' : '' }}"
                        {{ !$canEdit ? 'disabled' : '' }} required>
                </div>
                <div>
                    <label class="block text-[9px] font-bold text-gray-500 uppercase tracking-widest mb-1.5">Email</label>
                    <input type="email" name="store_email" value="{{ old('store_email', $settings['store_email'] ?? 'hello@elvo.com') }}"
                        class="w-full bg-elvo-bg border border-white/[0.06] rounded-xl px-3.5 py-2.5 text-sm text-white focus:border-elvo-amber outline-none transition-all {{ !$canEdit ? 'opacity-60 cursor-not-allowed' : '' }}"
                        {{ !$canEdit ? 'disabled' : '' }} required>
                </div>
                <div>
                    <label class="block text-[9px] font-bold text-gray-500 uppercase tracking-widest mb-1.5">Telepon</label>
                    <input type="text" name="store_phone" value="{{ old('store_phone', $settings['store_phone'] ?? '081234567890') }}"
                        class="w-full bg-elvo-bg border border-white/[0.06] rounded-xl px-3.5 py-2.5 text-sm text-white focus:border-elvo-amber outline-none transition-all {{ !$canEdit ? 'opacity-60 cursor-not-allowed' : '' }}"
                        {{ !$canEdit ? 'disabled' : '' }} required>
                </div>
            </div>

            <div class="mt-4">
                <label class="block text-[9px] font-bold text-gray-500 uppercase tracking-widest mb-1.5">Alamat</label>
                <textarea name="store_address" rows="2"
                    class="w-full bg-elvo-bg border border-white/[0.06] rounded-xl px-3.5 py-2.5 text-sm text-white focus:border-elvo-amber outline-none transition-all resize-none {{ !$canEdit ? 'opacity-60 cursor-not-allowed' : '' }}"
                    {{ !$canEdit ? 'disabled' : '' }} required>{{ old('store_address', $settings['store_address'] ?? 'Jl. Elvo No. 1, Jakarta') }}</textarea>
            </div>

            {{-- Biaya Kirim --}}
            <div class="mt-7 flex items-center gap-2 text-[10px] font-black text-elvo-amber uppercase tracking-widest mb-4">
                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16V6a1 1 0 00-1-1H4a1 1 0 00-1 1v10a1 1 0 001 1h1m8-1a1 1 0 01-1 1H9m4-1V8a1 1 0 011-1h2.586a1 1 0 01.707.293l3.414 3.414a1 1 0 01.293.707V16a1 1 0 01-1 1h-1m-6-1a1 1 0 001 1h1M5 17a2 2 0 104 0m-4 0a2 2 0 114 0m6 0a2 2 0 104 0m-4 0a2 2 0 114 0"/></svg>
                Biaya Kirim
            </div>
            <div class="h-px bg-white/[0.06] mb-5"></div>

            <div class="max-w-xs">
                <label class="block text-[9px] font-bold text-gray-500 uppercase tracking-widest mb-1.5">Ongkos Kirim (Rp)</label>
                <input type="number" name="shipping_cost" value="{{ old('shipping_cost', $settings['shipping_cost'] ?? '15000') }}" min="0"
                    class="w-full bg-elvo-bg border border-white/[0.06] rounded-xl px-3.5 py-2.5 text-sm text-white focus:border-elvo-amber outline-none transition-all {{ !$canEdit ? 'opacity-60 cursor-not-allowed' : '' }}"
                    {{ !$canEdit ? 'disabled' : '' }} required>
            </div>

            {{-- QRIS --}}
            <div class="mt-7 flex items-center gap-2 text-[10px] font-black text-elvo-amber uppercase tracking-widest mb-4">
                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v1m6 11h2m-6 0h-2v4m0-11v3m0 0h.01M12 12h4.01M16 20h4M4 12h4m12 0h.01M5 8h2a1 1 0 001-1V5a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1zm12 0h2a1 1 0 001-1V5a1 1 0 00-1-1h-2a1 1 0 00-1 1v2a1 1 0 001 1zM5 20h2a1 1 0 001-1v-2a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1z"/></svg>
                QRIS
            </div>
            <div class="h-px bg-white/[0.06] mb-5"></div>

            <div class="flex items-start gap-5">
                @php $qrisPath = $settings['payment_qris'] ?? null; @endphp
                <div class="w-24 h-24 rounded-xl bg-elvo-bg border border-white/[0.06] overflow-hidden shrink-0 flex items-center justify-center">
                    @if($qrisPath)
                    <img src="{{ asset('storage/' . $qrisPath) }}" class="w-full h-full object-cover">
                    @else
                    <svg class="w-10 h-10 text-gray-700" viewBox="0 0 24 24" fill="currentColor"><path d="M1 1h10v10H1V1zm2 2v6h6V3H3zm11-2h10v10H14V1zm2 2v6h6V3h-6zM1 14h10v10H1V14zm2 2v6h6v-6H3zm11-2h10v10H14V14zm2 2v6h6v-6h-6z"/></svg>
                    @endif
                </div>
                <div class="flex-1">
                    <label class="block text-[9px] font-bold text-gray-500 uppercase tracking-widest mb-1.5">Upload Gambar QRIS</label>
                    <input type="file" name="payment_qris" accept="image/*" {{ !$canEdit ? 'disabled' : '' }}
                        class="w-full text-xs text-gray-400 file:mr-3 file:py-1.5 file:px-4 file:rounded-lg file:border-0 file:text-[10px] file:font-bold file:bg-white/10 file:text-white file:cursor-pointer hover:file:bg-white/20 transition {{ !$canEdit ? 'opacity-60 cursor-not-allowed' : '' }}">
                    <p class="text-[9px] text-gray-600 mt-1">Format: JPG/PNG, max 2MB</p>
                </div>
            </div>

            {{-- Submit --}}
            @if($canEdit)
            <div class="mt-7 pt-5 border-t border-white/[0.06] flex justify-end">
                <button type="submit" class="px-8 py-2.5 bg-elvo-amber text-black text-[10px] font-black uppercase tracking-widest rounded-xl hover:bg-yellow-400 transition-all shadow-lg shadow-elvo-amber/20">
                    Simpan Pengaturan
                </button>
            </div>
            @endif
        </div>
    </form>
</div>
@endsection