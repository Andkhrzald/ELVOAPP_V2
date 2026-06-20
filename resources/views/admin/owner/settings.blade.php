@extends('layouts.app')

@section('content')
<div class="mb-6">
    <div class="flex items-center gap-2 mb-1">
        <h1 class="text-2xl font-bold text-white">Pengaturan Toko</h1>
        <span class="px-2 py-0.5 text-[10px] font-bold text-yellow-500 bg-yellow-500/10 rounded-full uppercase tracking-wider">Owner Only</span>
    </div>
    <p class="text-sm text-gray-400">Konfigurasi toko online Elvo.</p>
</div>

@if(session('success'))
<div class="mb-4 p-4 bg-green-500/10 border border-green-500/20 rounded-lg text-green-400 text-sm">{{ session('success') }}</div>
@endif

<div class="max-w-3xl p-6 bg-elvo-surface rounded-xl border border-white/[0.06] shadow-[0_0_25px_rgba(0,0,0,0.2)] card-hover">
    <form action="{{ route('admin.owner.settings.update') }}" method="POST" class="space-y-6">
        @csrf

        <div class="pb-4 border-b border-white/[0.06]">
            <h3 class="font-bold text-white text-lg">Informasi Toko</h3>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
            <div>
                <label class="block text-[10px] font-bold text-gray-500 uppercase tracking-widest mb-2">Nama Toko</label>
                <input type="text" name="store_name" value="{{ old('store_name', $settings['store_name'] ?? 'Elvo Store') }}" class="w-full bg-elvo-bg border border-white/[0.06] rounded-xl px-4 py-3 text-white focus:border-elvo-primary outline-none" required>
            </div>
            <div>
                <label class="block text-[10px] font-bold text-gray-500 uppercase tracking-widest mb-2">Email Toko</label>
                <input type="email" name="store_email" value="{{ old('store_email', $settings['store_email'] ?? 'hello@elvo.com') }}" class="w-full bg-elvo-bg border border-white/[0.06] rounded-xl px-4 py-3 text-white focus:border-elvo-primary outline-none" required>
            </div>
            <div>
                <label class="block text-[10px] font-bold text-gray-500 uppercase tracking-widest mb-2">No. Telepon</label>
                <input type="text" name="store_phone" value="{{ old('store_phone', $settings['store_phone'] ?? '081234567890') }}" class="w-full bg-elvo-bg border border-white/[0.06] rounded-xl px-4 py-3 text-white focus:border-elvo-primary outline-none" required>
            </div>
        </div>

        <div>
            <label class="block text-[10px] font-bold text-gray-500 uppercase tracking-widest mb-2">Alamat Toko</label>
            <textarea name="store_address" rows="3" class="w-full bg-elvo-bg border border-white/[0.06] rounded-xl px-4 py-3 text-white focus:border-elvo-primary outline-none resize-none" required>{{ old('store_address', $settings['store_address'] ?? 'Jl. Elvo No. 1, Jakarta') }}</textarea>
        </div>

        <div class="pb-4 pt-2 border-b border-white/[0.06]">
            <h3 class="font-bold text-white text-lg">Pengaturan Penjualan</h3>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-3 gap-5">
            <div>
                <label class="block text-[10px] font-bold text-gray-500 uppercase tracking-widest mb-2">Ongkos Kirim (Rp)</label>
                <input type="number" name="shipping_cost" value="{{ old('shipping_cost', $settings['shipping_cost'] ?? '15000') }}" min="0" class="w-full bg-elvo-bg border border-white/[0.06] rounded-xl px-4 py-3 text-white focus:border-elvo-primary outline-none" required>
            </div>
            <div>
                <label class="block text-[10px] font-bold text-gray-500 uppercase tracking-widest mb-2">Pajak (%)</label>
                <input type="number" name="tax_rate" value="{{ old('tax_rate', $settings['tax_rate'] ?? '0') }}" min="0" max="100" step="0.1" class="w-full bg-elvo-bg border border-white/[0.06] rounded-xl px-4 py-3 text-white focus:border-elvo-primary outline-none" required>
            </div>
        </div>

        <div>
            <label class="block text-[10px] font-bold text-gray-500 uppercase tracking-widest mb-2">Metode Pembayaran (pisahkan dengan koma)</label>
            <input type="text" name="payment_methods" value="{{ old('payment_methods', $settings['payment_methods'] ?? 'Transfer Bank, Cash On Delivery') }}" class="w-full bg-elvo-bg border border-white/[0.06] rounded-xl px-4 py-3 text-white focus:border-elvo-primary outline-none" required>
            <p class="text-xs text-gray-500 mt-1">Contoh: Transfer Bank, Cash On Delivery, QRIS</p>
        </div>

        <div class="flex justify-end pt-4 border-t border-white/[0.06]">
            <button type="submit" class="px-10 py-3 btn-primary text-xs font-black uppercase tracking-widest rounded-xl">Simpan Pengaturan</button>
        </div>
    </form>
</div>
@endsection
