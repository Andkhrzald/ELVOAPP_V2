@extends('layouts.app')

@section('content')
<div class="space-y-6">
    <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
        <div>
            <h1 class="text-2xl font-bold text-white">Kategori Produk</h1>
            <p class="text-sm text-gray-400">Kelola kategori utama & jenis produk.</p>
        </div>
        <button onclick="toggleModal('modal-create')" class="btn-primary px-5 py-2.5 text-sm flex items-center gap-2">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
            Tambah Kategori
        </button>
    </div>

    @if(session('success'))
    <div class="p-4 bg-green-500/10 border border-green-500/20 rounded-lg text-green-400 text-sm">{{ session('success') }}</div>
    @endif
    @if(session('error'))
    <div class="p-4 bg-red-500/10 border border-red-500/20 rounded-lg text-red-400 text-sm">{{ session('error') }}</div>
    @endif

    <div class="bg-elvo-surface rounded-xl border border-white/[0.06] shadow-[0_0_25px_rgba(0,0,0,0.2)] overflow-hidden">
        @php
        $parents = $categories->whereNull('parent_id');
        @endphp
        <table class="w-full text-left">
            <thead>
                <tr class="bg-white/[0.02] border-b border-white/[0.06]">
                    <th class="px-6 py-4 text-[10px] font-black text-gray-500 uppercase tracking-widest">Kategori</th>
                    <th class="px-6 py-4 text-[10px] font-black text-gray-500 uppercase tracking-widest text-center">Tipe</th>
                    <th class="px-6 py-4 text-[10px] font-black text-gray-500 uppercase tracking-widest text-center">Produk</th>
                    <th class="px-6 py-4 text-[10px] font-black text-gray-500 uppercase tracking-widest text-right">Aksi</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-white/5">
                @forelse($parents as $parent)
                <tr class="bg-white/[0.02]">
                    <td class="px-6 py-4">
                        <div class="flex items-center gap-3">
                            <div class="w-2 h-2 rounded-full bg-elvo-primary"></div>
                            <span class="text-sm font-black text-white uppercase tracking-wider">{{ $parent->name }}</span>
                        </div>
                    </td>
                    <td class="px-6 py-4 text-center">
                        <span class="px-2 py-0.5 bg-elvo-primary/10 text-elvo-primary text-[9px] font-bold rounded">Utama</span>
                    </td>
                    <td class="px-6 py-4 text-center">
                        <span class="inline-flex items-center justify-center w-8 h-8 rounded-lg bg-elvo-primary/10 text-xs font-bold text-elvo-primary">{{ $parent->products_count }}</span>
                    </td>
                    <td class="px-6 py-4 text-right">
                        <div class="flex justify-end items-center gap-2">
                            <button onclick="editCategory({{ $parent->id }}, '{{ $parent->name }}', '')" class="p-2 bg-white/5 text-gray-400 hover:text-white hover:bg-elvo-primary/20 rounded-lg transition-all">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z" stroke-width="2"/></svg>
                            </button>
                            <form action="{{ route('admin.categories.destroy', $parent->id) }}" method="POST" onsubmit="return confirm('Hapus kategori {{ $parent->name }}? Semua sub-kategori akan menjadi tidak memiliki parent.')">
                                @csrf @method('DELETE')
                                <button type="submit" class="p-2 bg-white/5 text-gray-400 hover:text-white hover:bg-red-500/20 rounded-lg transition-all">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" stroke-width="2"/></svg>
                                </button>
                            </form>
                        </div>
                    </td>
                </tr>
                @foreach($categories->where('parent_id', $parent->id) as $child)
                <tr class="hover:bg-white/[0.01] transition-colors">
                    <td class="px-6 py-3 pl-14">
                        <span class="text-sm font-bold text-white">
                            <span class="text-gray-600 mr-2">└</span>{{ $child->name }}
                        </span>
                    </td>
                    <td class="px-6 py-3 text-center">
                        <span class="px-2 py-0.5 bg-white/5 text-gray-400 text-[9px] font-bold rounded">Sub</span>
                    </td>
                    <td class="px-6 py-3 text-center">
                        <span class="inline-flex items-center justify-center w-7 h-7 rounded-lg bg-white/5 text-xs font-bold text-gray-300">{{ $child->products_count }}</span>
                    </td>
                    <td class="px-6 py-3 text-right">
                        <div class="flex justify-end items-center gap-2">
                            <button onclick="editCategory({{ $child->id }}, '{{ $child->name }}', '{{ $child->parent_id }}')" class="p-2 bg-white/5 text-gray-400 hover:text-white hover:bg-elvo-primary/20 rounded-lg transition-all">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z" stroke-width="2"/></svg>
                            </button>
                            <form action="{{ route('admin.categories.destroy', $child->id) }}" method="POST" onsubmit="return confirm('Hapus jenis produk {{ $child->name }}?')">
                                @csrf @method('DELETE')
                                <button type="submit" class="p-2 bg-white/5 text-gray-400 hover:text-white hover:bg-red-500/20 rounded-lg transition-all">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" stroke-width="2"/></svg>
                                </button>
                            </form>
                        </div>
                    </td>
                </tr>
                @endforeach
                @empty
                <tr><td colspan="4" class="px-6 py-16 text-center text-gray-500 italic">Belum ada kategori. Buat kategori pertama!</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

{{-- MODAL CREATE --}}
<div id="modal-create" class="fixed inset-0 z-[100] hidden items-center justify-center bg-black/80 backdrop-blur-md">
    <div class="relative p-8 w-full max-w-lg bg-elvo-surface rounded-[2rem] shadow-2xl border border-white/10 animate-modal-in">
        <div class="flex items-center justify-between mb-6">
            <h3 class="text-xl font-black text-white">Tambah Kategori</h3>
            <button onclick="toggleModal('modal-create')" class="w-10 h-10 bg-white/5 text-gray-400 hover:text-white rounded-full flex items-center justify-center">&times;</button>
        </div>
        <form action="{{ route('admin.categories.store') }}" method="POST" class="space-y-4">
            @csrf
            <div>
                <label class="block text-[10px] font-bold text-gray-500 uppercase tracking-widest mb-2">Nama Kategori</label>
                <input type="text" name="name" required class="w-full bg-elvo-bg border border-white/[0.06] rounded-xl px-5 py-3 text-white focus:border-elvo-primary outline-none transition-all" placeholder="Contoh: Jam Tangan">
            </div>
            <div>
                <label class="block text-[10px] font-bold text-gray-500 uppercase tracking-widest mb-2">Kategori Utama</label>
                <select name="parent_id" class="w-full bg-elvo-bg border border-white/[0.06] rounded-xl px-5 py-3 text-white focus:border-elvo-primary outline-none transition-all">
                    <option value="">— Buat sebagai Kategori Utama —</option>
                    @foreach($parentCategories as $parent)
                    <option value="{{ $parent->id }}">Sub dari {{ $parent->name }}</option>
                    @endforeach
                </select>
            </div>
            <div class="flex justify-end gap-3 pt-4">
                <button type="button" onclick="toggleModal('modal-create')" class="px-6 py-3 text-xs font-black uppercase tracking-widest text-gray-500 hover:text-white">Batal</button>
                <button type="submit" class="px-8 py-3 btn-primary text-xs font-black uppercase tracking-widest rounded-xl">Simpan</button>
            </div>
        </form>
    </div>
</div>

{{-- MODAL EDIT --}}
<div id="modal-edit" class="fixed inset-0 z-[100] hidden items-center justify-center bg-black/80 backdrop-blur-md">
    <div class="relative p-8 w-full max-w-lg bg-elvo-surface rounded-[2rem] shadow-2xl border border-white/10 animate-modal-in">
        <div class="flex items-center justify-between mb-6">
            <h3 class="text-xl font-black text-white">Edit Kategori</h3>
            <button onclick="toggleModal('modal-edit')" class="w-10 h-10 bg-white/5 text-gray-400 hover:text-white rounded-full flex items-center justify-center">&times;</button>
        </div>
        <form id="editForm" method="POST" class="space-y-4">
            @csrf @method('PUT')
            <div>
                <label class="block text-[10px] font-bold text-gray-500 uppercase tracking-widest mb-2">Nama Kategori</label>
                <input type="text" name="name" id="editName" required class="w-full bg-elvo-bg border border-white/[0.06] rounded-xl px-5 py-3 text-white focus:border-elvo-primary outline-none transition-all">
            </div>
            <div>
                <label class="block text-[10px] font-bold text-gray-500 uppercase tracking-widest mb-2">Kategori Utama</label>
                <select name="parent_id" id="editParentId" class="w-full bg-elvo-bg border border-white/[0.06] rounded-xl px-5 py-3 text-white focus:border-elvo-primary outline-none transition-all">
                    <option value="">— Kategori Utama —</option>
                    @foreach($parentCategories as $parent)
                    <option value="{{ $parent->id }}">Sub dari {{ $parent->name }}</option>
                    @endforeach
                </select>
            </div>
            <div class="flex justify-end gap-3 pt-4">
                <button type="button" onclick="toggleModal('modal-edit')" class="px-6 py-3 text-xs font-black uppercase tracking-widest text-gray-500 hover:text-white">Batal</button>
                <button type="submit" class="px-8 py-3 btn-primary text-xs font-black uppercase tracking-widest rounded-xl">Simpan</button>
            </div>
        </form>
    </div>
</div>

<script>
function toggleModal(id) {
    const m = document.getElementById(id);
    m.classList.toggle('hidden');
    m.classList.toggle('flex');
    document.body.style.overflow = m.classList.contains('hidden') ? 'auto' : 'hidden';
}

function editCategory(id, name, parentId) {
    document.getElementById('editName').value = name;
    document.getElementById('editParentId').value = parentId;
    document.getElementById('editForm').action = '/admin/categories/' + id;
    toggleModal('modal-edit');
}
</script>
<style>
@keyframes modalIn { from { transform: scale(0.9) translateY(20px); opacity: 0; } to { transform: scale(1) translateY(0); opacity: 1; } }
.animate-modal-in { animation: modalIn 0.3s cubic-bezier(0.34, 1.56, 0.64, 1); }
</style>
@endsection
