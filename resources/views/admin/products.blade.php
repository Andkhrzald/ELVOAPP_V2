@extends('layouts.app')

@section('content')
<div class="space-y-6">
    {{-- Header Section --}}
    <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
        <div>
            <h1 class="text-3xl font-black text-white tracking-tight">Katalog Produk</h1>
            <p class="text-sm text-gray-500 mt-1">Kelola inventaris, harga, dan visibilitas produk Anda.</p>
        </div>
        <div class="flex items-center gap-3">
            <button onclick="toggleModal('modal-tambah')" class="bg-blue-600 hover:bg-blue-500 text-white px-5 py-2.5 rounded-xl text-sm font-bold transition shadow-lg shadow-blue-900/20 flex items-center gap-2">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 4v16m8-8H4"/>
                </svg>
                Tambah Produk
            </button>
        </div>
    </div>

    {{-- Stats Grid --}}
    <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
        <div class="bg-[#1a1a1a] p-5 rounded-2xl border border-white/5 shadow-sm">
            <p class="text-[10px] font-bold text-gray-500 uppercase tracking-widest mb-1">Total Produk</p>
            <div class="flex items-end justify-between">
                <h3 class="text-2xl font-black text-white">{{ $totalProducts }}</h3>
                <span class="text-[10px] bg-blue-500/10 text-blue-500 px-2 py-1 rounded-md font-bold">Items</span>
            </div>
        </div>
        <div class="bg-[#1a1a1a] p-5 rounded-2xl border border-white/5 shadow-sm">
            <p class="text-[10px] font-bold text-gray-500 uppercase tracking-widest mb-1">Produk Aktif</p>
            <div class="flex items-end justify-between">
                <h3 class="text-2xl font-black text-green-500">{{ $activeProducts }}</h3>
                <span class="text-[10px] bg-green-500/10 text-green-500 px-2 py-1 rounded-md font-bold">Live</span>
            </div>
        </div>
        <div class="bg-[#1a1a1a] p-5 rounded-2xl border border-white/5 shadow-sm">
            <p class="text-[10px] font-bold text-gray-500 uppercase tracking-widest mb-1">Disembunyikan</p>
            <div class="flex items-end justify-between">
                <h3 class="text-2xl font-black text-yellow-500">{{ $hiddenProducts }}</h3>
                <span class="text-[10px] bg-yellow-500/10 text-yellow-500 px-2 py-1 rounded-md font-bold">Hidden</span>
            </div>
        </div>
        <div class="bg-[#1a1a1a] p-5 rounded-2xl border border-white/5 shadow-sm">
            <p class="text-[10px] font-bold text-gray-500 uppercase tracking-widest mb-1">Stok Menipis</p>
            <div class="flex items-end justify-between">
                <h3 class="text-2xl font-black text-red-500">{{ $lowStockProducts }}</h3>
                <span class="text-[10px] bg-red-500/10 text-red-500 px-2 py-1 rounded-md font-bold">Alert</span>
            </div>
        </div>
    </div>

    <div class="grid grid-cols-12 gap-6">
        {{-- Sidebar Section --}}
        <div class="col-span-12 lg:col-span-3 space-y-6">
            <form action="{{ route('admin.products') }}" method="GET" class="bg-[#1a1a1a] p-6 rounded-2xl border border-white/5 sticky top-24">
                <h4 class="text-xs font-black text-white uppercase tracking-widest mb-6 pb-2 border-b border-white/5">Filter & Pencarian</h4>
                
                <div class="space-y-5">
                    <div>
                        <label class="block text-[10px] font-bold text-gray-500 uppercase tracking-widest mb-2">Cari Nama</label>
                        <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari..." class="w-full bg-[#121212] border border-white/5 rounded-xl px-4 py-2.5 text-sm text-white focus:border-blue-500 outline-none transition-all">
                    </div>

                    <div>
                        <label class="block text-[10px] font-bold text-gray-500 uppercase tracking-widest mb-2">Kategori</label>
                        <select name="category" class="w-full bg-[#121212] border border-white/5 rounded-xl px-4 py-2.5 text-sm text-white focus:border-blue-500 outline-none transition-all appearance-none">
                            <option value="">Semua Kategori</option>
                            @foreach(\App\Models\Category::all() as $cat)
                                <option value="{{ $cat->id }}" {{ request('category') == $cat->id ? 'selected' : '' }}>{{ $cat->name }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div>
                        <label class="block text-[10px] font-bold text-gray-500 uppercase tracking-widest mb-2">Status</label>
                        <div class="space-y-2">
                            <label class="flex items-center gap-3 cursor-pointer group">
                                <input type="radio" name="status" value="" {{ request('status') == '' ? 'checked' : '' }} class="hidden peer">
                                <span class="w-4 h-4 rounded-full border-2 border-white/10 peer-checked:border-blue-500 peer-checked:bg-blue-500 transition-all"></span>
                                <span class="text-sm text-gray-400 group-hover:text-white transition-colors">Semua</span>
                            </label>
                            <label class="flex items-center gap-3 cursor-pointer group">
                                <input type="radio" name="status" value="active" {{ request('status') == 'active' ? 'checked' : '' }} class="hidden peer">
                                <span class="w-4 h-4 rounded-full border-2 border-white/10 peer-checked:border-green-500 peer-checked:bg-green-500 transition-all"></span>
                                <span class="text-sm text-gray-400 group-hover:text-white transition-colors">Aktif (Live)</span>
                            </label>
                            <label class="flex items-center gap-3 cursor-pointer group">
                                <input type="radio" name="status" value="hidden" {{ request('status') == 'hidden' ? 'checked' : '' }} class="hidden peer">
                                <span class="w-4 h-4 rounded-full border-2 border-white/10 peer-checked:border-yellow-500 peer-checked:bg-yellow-500 transition-all"></span>
                                <span class="text-sm text-gray-400 group-hover:text-white transition-colors">Disembunyikan</span>
                            </label>
                        </div>
                    </div>

                    <div class="grid grid-cols-2 gap-3">
                        <a href="{{ route('admin.products') }}" class="py-3 bg-white/5 hover:bg-white/10 text-white text-[10px] font-black uppercase tracking-widest rounded-xl transition-all text-center">
                            Reset
                        </a>
                        <button type="submit" class="py-3 bg-blue-600 hover:bg-blue-500 text-white text-[10px] font-black uppercase tracking-widest rounded-xl transition-all">
                            Filter
                        </button>
                    </div>
                </div>
            </form>
        </div>

        {{-- Table Section --}}
        <div class="col-span-12 lg:col-span-9">
            <div class="bg-[#1a1a1a] rounded-2xl border border-white/5 overflow-hidden shadow-2xl">
                <div class="overflow-x-auto">
                    <table class="w-full text-left">
                        <thead>
                            <tr class="bg-white/[0.02] border-b border-white/5">
                                <th class="px-6 py-4 text-[10px] font-black text-gray-500 uppercase tracking-widest">Produk</th>
                                <th class="px-6 py-4 text-[10px] font-black text-gray-500 uppercase tracking-widest text-center">Status</th>
                                <th class="px-6 py-4 text-[10px] font-black text-gray-500 uppercase tracking-widest">Kategori</th>
                                <th class="px-6 py-4 text-[10px] font-black text-gray-500 uppercase tracking-widest">Stok</th>
                                <th class="px-6 py-4 text-[10px] font-black text-gray-500 uppercase tracking-widest">Harga</th>
                                <th class="px-6 py-4 text-[10px] font-black text-gray-500 uppercase tracking-widest text-right">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-white/5">
                            @forelse($products as $p)
                            <tr class="hover:bg-white/[0.01] transition-colors group">
                                <td class="px-6 py-4">
                                    <div class="flex items-center gap-4">
                                        <div class="w-12 h-12 rounded-xl bg-[#121212] border border-white/5 overflow-hidden flex-shrink-0">
                                            @if($p->image)
                                                <img src="{{ asset('uploads/' . $p->image) }}" class="w-full h-full object-cover">
                                            @else
                                                <div class="w-full h-full flex items-center justify-center text-gray-600">
                                                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" stroke-width="2"/></svg>
                                                </div>
                                            @endif
                                        </div>
                                        <div>
                                            <div class="text-sm font-bold text-white">{{ $p->name }}</div>
                                            <div class="text-[10px] text-gray-500 font-medium">SKU: #ELV-{{ str_pad($p->id, 4, '0', STR_PAD_LEFT) }}</div>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-6 py-4 text-center">
                                    <button onclick="toggleStatus({{ $p->id }}, this)" 
                                        class="relative inline-flex items-center h-6 rounded-full w-11 transition-colors focus:outline-none {{ $p->is_active ? 'bg-blue-600' : 'bg-gray-700' }}">
                                        <span class="sr-only">Toggle Status</span>
                                        <span class="inline-block w-4 h-4 transform bg-white rounded-full transition-transform {{ $p->is_active ? 'translate-x-6' : 'translate-x-1' }}"></span>
                                    </button>
                                    <div class="mt-1 text-[9px] font-bold uppercase tracking-tighter {{ $p->is_active ? 'text-blue-500' : 'text-gray-500' }}">
                                        {{ $p->is_active ? 'Visible' : 'Hidden' }}
                                    </div>
                                </td>
                                <td class="px-6 py-4">
                                    <span class="px-2 py-1 bg-white/5 text-gray-400 border border-white/5 rounded-md text-[10px] font-bold uppercase">
                                        {{ $p->category->name ?? 'None' }}
                                    </span>
                                </td>
                                <td class="px-6 py-4">
                                    <div class="flex items-center gap-2">
                                        <div class="w-1.5 h-1.5 rounded-full {{ $p->stock > 10 ? 'bg-green-500' : ($p->stock > 0 ? 'bg-yellow-500' : 'bg-red-500') }}"></div>
                                        <span class="text-sm font-bold {{ $p->stock > 0 ? 'text-white' : 'text-red-500' }}">{{ $p->stock }}</span>
                                    </div>
                                </td>
                                <td class="px-6 py-4 text-sm font-black text-white">
                                    <span class="text-[10px] text-gray-500 font-normal mr-1">Rp</span>{{ number_format($p->price, 0, ',', '.') }}
                                </td>
                                <td class="px-6 py-4">
                                    <div class="flex justify-end items-center gap-2">
                                        <a href="{{ route('admin.products.edit', $p->id) }}" class="p-2 bg-white/5 text-gray-400 hover:text-white hover:bg-blue-600 rounded-lg transition-all">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/></svg>
                                        </a>
                                        <form action="{{ route('admin.products.destroy', $p->id) }}" method="POST" onsubmit="return confirm('Hapus produk ini?')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="p-2 bg-white/5 text-gray-400 hover:text-white hover:bg-red-600 rounded-lg transition-all">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/></svg>
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="6" class="px-6 py-20 text-center">
                                    <div class="flex flex-col items-center">
                                        <div class="w-16 h-16 bg-white/5 rounded-full flex items-center justify-center mb-4">
                                            <svg class="w-8 h-8 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/></svg>
                                        </div>
                                        <p class="text-gray-500 font-medium italic">Tidak ada produk yang ditemukan.</p>
                                    </div>
                                </td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                @if($products->hasPages())
                <div class="p-6 border-t border-white/5">
                    {{ $products->withQueryString()->links() }}
                </div>
                @endif
            </div>
        </div>
    </div>
</div>

{{-- MODAL TAMBAH PRODUK --}}
<div id="modal-tambah" class="fixed inset-0 z-[100] hidden items-center justify-center bg-black/80 backdrop-blur-md">
    <div class="relative p-8 w-full max-w-3xl max-h-[90vh] overflow-y-auto bg-[#1a1a1a] rounded-[2rem] shadow-2xl border border-white/10 animate-modal-in">
        <div class="flex items-center justify-between mb-8">
            <div>
                <h3 class="text-2xl font-black text-white">Tambah Produk Baru</h3>
                <p class="text-xs text-gray-500 mt-1 uppercase tracking-widest">Inventory System / Add Product</p>
            </div>
            <button onclick="toggleModal('modal-tambah')" class="w-10 h-10 bg-white/5 text-gray-400 hover:text-white rounded-full flex items-center justify-center transition-all">&times;</button>
        </div>

        <form action="{{ route('admin.products.store') }}" method="POST" enctype="multipart/form-data" class="space-y-8">
            @csrf
            <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                <div class="md:col-span-2">
                    <label class="block text-[10px] font-bold text-gray-500 uppercase tracking-widest mb-3">Nama Produk Lengkap</label>
                    <input type="text" name="name" id="productName" onkeyup="generateSlug(this.value)" class="w-full bg-[#121212] border border-white/5 rounded-2xl px-5 py-4 text-white focus:border-blue-500 outline-none transition-all placeholder-gray-700" placeholder="Contoh: Premium Elvo Hoodie Black Edition" required>
                </div>

                <div class="md:col-span-2">
                    <label class="block text-[10px] font-bold text-gray-500 uppercase tracking-widest mb-3">Slug URL (Otomatis)</label>
                    <div class="flex items-center gap-2 bg-[#0a0a0a] border border-white/5 rounded-2xl px-5 py-3 text-[10px] text-gray-500 font-mono">
                        <span>elvo.com/shop/</span>
                        <input type="text" name="slug" id="productSlug" class="bg-transparent border-none p-0 focus:ring-0 text-blue-400 font-bold w-full" readonly>
                    </div>
                </div>

                <div>
                    <label class="block text-[10px] font-bold text-gray-500 uppercase tracking-widest mb-3">Kategori</label>
                    <select name="category_id" class="w-full bg-[#121212] border border-white/5 rounded-2xl px-5 py-4 text-white focus:border-blue-500 outline-none transition-all" required>
                        <option value="">Pilih Kategori</option>
                        @foreach(\App\Models\Category::all() as $cat)
                            <option value="{{ $cat->id }}">{{ $cat->name }}</option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <label class="block text-[10px] font-bold text-gray-500 uppercase tracking-widest mb-3">Warna</label>
                    <input type="text" name="color" class="w-full bg-[#121212] border border-white/5 rounded-2xl px-5 py-4 text-white focus:border-blue-500 outline-none transition-all" placeholder="Hitam / Putih">
                </div>

                <div>
                    <label class="block text-[10px] font-bold text-gray-500 uppercase tracking-widest mb-3">Harga (Rp)</label>
                    <div class="relative">
                        <span class="absolute left-5 top-1/2 -translate-y-1/2 text-gray-500 font-bold">Rp</span>
                        <input type="number" name="price" class="w-full bg-[#121212] border border-white/5 rounded-2xl pl-12 pr-5 py-4 text-white focus:border-blue-500 outline-none transition-all font-black text-xl" placeholder="0" required>
                    </div>
                </div>

                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="block text-[10px] font-bold text-gray-500 uppercase tracking-widest mb-3">Stok</label>
                        <input type="number" name="stock" class="w-full bg-[#121212] border border-white/5 rounded-2xl px-5 py-4 text-white focus:border-blue-500 outline-none transition-all" required>
                    </div>
                    <div>
                        <label class="block text-[10px] font-bold text-gray-500 uppercase tracking-widest mb-3">Berat (g)</label>
                        <input type="number" name="weight" class="w-full bg-[#121212] border border-white/5 rounded-2xl px-5 py-4 text-white focus:border-blue-500 outline-none transition-all" required>
                    </div>
                </div>

                <div class="md:col-span-2">
                    <label class="block text-[10px] font-bold text-gray-500 uppercase tracking-widest mb-3">Deskripsi Produk</label>
                    <textarea name="description" rows="4" class="w-full bg-[#121212] border border-white/5 rounded-2xl px-5 py-4 text-white focus:border-blue-500 outline-none transition-all resize-none" placeholder="Jelaskan detail produk, bahan, dan keunggulannya..." required></textarea>
                </div>

                <div class="md:col-span-2">
                    <label class="block text-[10px] font-bold text-gray-500 uppercase tracking-widest mb-3">Foto Produk</label>
                    <div id="imagePreviewContainer" class="hidden mb-4">
                        <img id="imagePreview" src="#" class="w-40 h-40 object-cover rounded-2xl border border-white/10 mx-auto">
                    </div>
                    <label class="flex flex-col items-center justify-center w-full h-40 border-2 border-dashed border-white/5 rounded-3xl cursor-pointer hover:bg-white/[0.02] hover:border-blue-500/50 transition-all group">
                        <div class="flex flex-col items-center justify-center pt-5 pb-6">
                            <svg class="w-10 h-10 mb-3 text-gray-500 group-hover:text-blue-500 transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M12 4v16m8-8H4" stroke-width="2" stroke-linecap="round"/></svg>
                            <p class="mb-2 text-xs text-gray-500 uppercase font-black tracking-widest group-hover:text-white">Klik untuk upload gambar</p>
                            <p class="text-[9px] text-gray-600 uppercase">PNG, JPG (MAX. 2MB)</p>
                        </div>
                        <input type="file" name="image" class="hidden" onchange="previewImage(this)" />
                    </label>
                </div>

                <div class="md:col-span-2 flex items-center gap-3">
                    <input type="checkbox" name="is_active" value="1" checked id="isActiveCheck" class="w-5 h-5 bg-[#121212] border-white/10 rounded text-blue-600 focus:ring-blue-500">
                    <label for="isActiveCheck" class="text-sm font-bold text-gray-300 cursor-pointer">Langsung tampilkan di website (Aktif)</label>
                </div>
            </div>

            <div class="flex justify-end gap-4 pt-8 border-t border-white/5">
                <button type="button" onclick="toggleModal('modal-tambah')" class="px-8 py-4 text-xs font-black uppercase tracking-widest text-gray-500 hover:text-white transition-all">Batal</button>
                <button type="submit" class="px-10 py-4 bg-blue-600 text-white text-xs font-black uppercase tracking-widest rounded-2xl hover:bg-blue-500 shadow-xl shadow-blue-900/40 transition-all">Simpan Produk</button>
            </div>
        </form>
    </div>
</div>

<script>
    function toggleModal(id) {
        const modal = document.getElementById(id);
        if (modal.classList.contains('hidden')) {
            modal.classList.remove('hidden');
            modal.classList.add('flex');
            document.body.style.overflow = 'hidden';
        } else {
            modal.classList.add('hidden');
            modal.classList.remove('flex');
            document.body.style.overflow = 'auto';
        }
    }

    async function toggleStatus(productId, btn) {
        try {
            const response = await fetch(`/admin/products/toggle-status/${productId}`, {
                method: 'PATCH',
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                    'Content-Type': 'application/json',
                    'Accept': 'application/json'
                }
            });
            const data = await response.json();
            
            if (data.success) {
                // Update UI visually
                const dot = btn.querySelector('span:last-child');
                const label = btn.nextElementSibling;
                
                if (data.is_active) {
                    btn.classList.remove('bg-gray-700');
                    btn.classList.add('bg-blue-600');
                    dot.classList.remove('translate-x-1');
                    dot.classList.add('translate-x-6');
                    label.textContent = 'Visible';
                    label.classList.remove('text-gray-500');
                    label.classList.add('text-blue-500');
                } else {
                    btn.classList.remove('bg-blue-600');
                    btn.classList.add('bg-gray-700');
                    dot.classList.remove('translate-x-6');
                    dot.classList.add('translate-x-1');
                    label.textContent = 'Hidden';
                    label.classList.remove('text-blue-500');
                    label.classList.add('text-gray-500');
                }
            }
        } catch (error) {
            console.error('Error toggling status:', error);
            alert('Gagal mengubah status produk.');
        }
    }

    // Image Preview logic
    function previewImage(input) {
        const container = document.getElementById('imagePreviewContainer');
        const preview = document.getElementById('imagePreview');
        
        if (input.files && input.files[0]) {
            const reader = new FileReader();
            reader.onload = function(e) {
                preview.src = e.target.result;
                container.classList.remove('hidden');
            }
            reader.readAsDataURL(input.files[0]);
        }
    }

    function generateSlug(text) {
        const slug = text.toLowerCase()
            .replace(/[^\w ]+/g, '')
            .replace(/ +/g, '-');
        document.getElementById('productSlug').value = slug;
    }
</script>

<style>
    @keyframes modalIn {
        from { transform: scale(0.9) translateY(20px); opacity: 0; }
        to { transform: scale(1) translateY(0); opacity: 1; }
    }
    .animate-modal-in {
        animation: modalIn 0.3s cubic-bezier(0.34, 1.56, 0.64, 1);
    }
    
    /* Custom Scrollbar for Modal */
    #modal-tambah::-webkit-scrollbar {
        width: 8px;
    }
    #modal-tambah::-webkit-scrollbar-track {
        background: transparent;
    }
    #modal-tambah::-webkit-scrollbar-thumb {
        background: rgba(255, 255, 255, 0.1);
        border-radius: 10px;
    }
</style>
@endsection