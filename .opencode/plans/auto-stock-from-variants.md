# Plan: Auto-Calculate Product Stock from Variants

## Goal
Product stock (level) otomatis = jumlah stok semua varian. Input stock menjadi **read-only** (display only) jika produk memiliki varian.

---

## Changes

### 1. `products.blade.php` (create modal)

**Stock input di pricing panel (line 413):**
```blade
{{-- Before --}}
<input type="number" name="stock" class="..." placeholder="0" required>

{{-- After --}}
<div class="flex items-center gap-2 bg-elvo-bg border border-white/[0.06] rounded-xl px-4 h-11 focus-within:border-elvo-primary transition-all">
    <svg class="w-4 h-4 text-gray-500 flex-shrink-0" ...></svg>
    <input type="number" id="product-stock" readonly
        class="bg-transparent w-full text-sm font-bold text-white outline-none border-none p-0 focus:ring-0 cursor-default" placeholder="0">
    <span class="text-[7px] text-elvo-primary/60 font-bold uppercase tracking-widest shrink-0">Auto</span>
</div>
<p class="text-[7px] text-gray-600 mt-1">Stok otomatis dari jumlah stok varian</p>
```

**New JS function:**
```javascript
function updateProductStock() {
    const hint = document.getElementById('variant-type-hint');
    const usesSize = hint && hint.textContent.includes('Ukuran tersedia');
    let total = 0;
    if (usesSize) {
        colors.forEach(c => {
            sizes.forEach(s => {
                const key = c.name + '_' + s;
                const cell = matrix[key];
                if (cell && cell.enabled && cell.stock) total += Number(cell.stock);
            });
        });
    } else {
        colors.forEach(c => {
            const key = c.name + '_';
            const cell = matrix[key];
            if (cell && cell.stock) total += Number(cell.stock);
        });
    }
    document.getElementById('product-stock').value = total;
}
```

**Call `updateProductStock()` di:**
- `setCellStock()` — setelah nilai stock cell berubah
- `toggleCell()` — setelah enable/disable cell
- `addColor()` / `removeColor()` — setelah warna ditambah/dihapus
- `addSizePreset()` / `addSize()` / `removeSize()` — setelah ukuran berubah
- Form submit handler — pastikan stock terisi sebelum submit

### 2. `products_edit.blade.php` (edit form)

**Stock input di pricing panel (lines 244-245):**
Same as create modal: `id="product-stock"`, `readonly`, Auto badge, helper text.
Value is pre-filled via JS from existing variant stock sum.

**JS function `editUpdateProductStock()`:**
Same logic as `updateProductStock()` but using `editColors`, `editSizes`, `editMatrix`.

Call di:
- `editInitVariants()` — setelah pre-fill dari data existing
- `editSetCellStock()`, `editToggleCell()`
- `editAddColor()`, `editRemoveColor()`
- `editAddSizePreset()`, `editAddSize()`, `editRemoveSize()`

### 3. `ProductController.php`

**`store()`:**
Setelah variant loop (line 122), tambahkan:
```php
// Recalculate product stock from variants
$totalStock = ProductVariant::where('product_id', $product->id)->sum('stock');
$product->update(['stock' => $totalStock]);
```

**`update()`:**
Setelah variant loop (line 207), tambahkan:
```php
// Recalculate product stock from variants
$totalStock = ProductVariant::where('product_id', $product->id)->sum('stock');
$product->update(['stock' => $totalStock]);
```

Hapus validasi `'stock' => 'required|integer'` karena stock sudah auto-calculate.
Hapus log `StockMutation` manual untuk stock — karena stock otomatis mengikuti varian.

### 4. Edge Cases

| Skenario | Perilaku |
|----------|----------|
| Produk tanpa varian | Stock input tetap readonly bernilai 0. User harus tambah varian dulu. |
| Semua varian di-disable | Stock = 0 |
| Varian stock diubah | Stock produk otomatis update |
| Varian dihapus (warna/size di-remove) | Stock produk otomatis update |
| Edit produk existing | Stock langsung terisi dari sum varian yang sudah ada |

### Files Modified
- `resources/views/admin/products.blade.php` — stock input readonly + JS `updateProductStock()`
- `resources/views/admin/products_edit.blade.php` — stock input readonly + JS `editUpdateProductStock()`
- `app/Http/Controllers/Admin/ProductController.php` — recalculate stock after variant processing
