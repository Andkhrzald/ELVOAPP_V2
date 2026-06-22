# Plan: Variant Matrix (Warna × Ukuran)

## Tujuan
Mengganti input varian dari flat row list menjadi **matrix 2-dimensi**: warna sebagai baris, ukuran sebagai kolom. Setiap cell bisa di-enable/disable dengan stock & price sendiri. Untuk kategori tanpa ukuran, cukup color list dengan stock per warna.

---

## 1. Changes Overview

### 1.1 `products.blade.php` (Create Modal)
**Replace** variant section (lines 298-352) + JavaScript (lines 439-654)

### 1.2 `products_edit.blade.php` (Edit Form)
**Replace** variant section (lines 108-195) + JavaScript (lines 309-489)

### 1.3 `ProductController.php` (Backend)
**Minor update** to `store()` and `update()` — handle `color_images` alongside `variants`

---

## 2. UI Components (shared between create & edit)

### 2.1 Color Input Section
```
[Nama Warna] [Color Picker] [Upload Foto] [+ Tambah Warna]
┌─────────────────────────────────────────────────────┐
│  🟥 Red    [img]  ×    🟦 Blue   [img]  ×         │
└─────────────────────────────────────────────────────┘
```
- Each color chip: swatch, name, image thumbnail (72×72), remove button
- Image is uploaded once per color, assigned to ALL its variant rows

### 2.2 Size Input Section (hidden for non-size categories)
```
Quick Size: [S] [M] [L] [XL]   [Ukuran kustom] [+ Tambah Ukuran]
┌─────────────────────────────────────────────────────┐
│  S ×    M ×    L ×    XL ×                         │
└─────────────────────────────────────────────────────┘
```

### 2.3 Matrix Table (size categories)
```
┌──────────┬──────┬──────┬──────┬──────┐
│ Warna    │  S   │  M   │  L   │ XL   │
├──────────┼──────┼──────┼──────┼──────┤
│ 🟥 Red  │[✓]10│[✓]5 │[✗]   │[✓]0  │
│   [img] │100k  │100k  │      │100k  │
├──────────┼──────┼──────┼──────┼──────┤
│ 🟦 Blue │[✗]   │[✓]3 │[✓]8  │[✓]2  │
│   [img] │      │100k  │100k  │100k  │
└──────────┴──────┴──────┴──────┴──────┘
```
- Each cell: checkbox enable + stock input + price input
- Stock/price inputs disabled (grayed) when checkbox unchecked
- Color image thumbnail in the row header

### 2.4 Color List Table (non-size categories)
```
┌──────────┬──────┬──────┬──────┬────┐
│ Warna    │ Stok │Harga │ Foto │    │
├──────────┼──────┼──────┼──────┼────┤
│ 🟥 Red  │  10  │100k  │ [img]│  × │
│ 🟦 Blue │  0   │100k  │ [img]│  × │
└──────────┴──────┴──────┴──────┴────┘
```

---

## 3. JavaScript Architecture

### 3.1 State
```javascript
let colors = [];       // {name, hex, image: File|null}
let sizes = [];        // string[]
let matrix = {};       // { "Red_S": {enabled, stock, price} }
```

### 3.2 Key Functions

| Function | Description |
|---|---|
| `addColor()` | Push to `colors[]`, create chip, rebuild matrix |
| `removeColor(name)` | Filter `colors[]`, remove from `matrix`, rebuild |
| `addSize(s)` | Push to `sizes[]`, create chip, rebuild matrix |
| `removeSize(s)` | Filter `sizes[]`, remove from `matrix`, rebuild |
| `buildMatrix()` | Re-render the matrix table from `colors × sizes` |
| `toggleCell(color, size)` | Toggle enabled state, update inputs disabled state |
| `buildColorList()` | Re-render color list for non-size categories |
| `convertToFlatVariants()` | On form submit: generate hidden `variants[]` inputs from matrix + color images |

### 3.3 Form Submission Conversion (`convertToFlatVariants`)
Before form submit:
1. Collect all enabled cells from matrix
2. For each cell, create hidden `<input>` elements:
   ```
   <input type="hidden" name="variants[{index}][size]" value="S">
   <input type="hidden" name="variants[{index}][color]" value="Red">
   <input type="hidden" name="variants[{index}][color_hex]" value="#FF0000">
   <input type="hidden" name="variants[{index}][stock]" value="10">
   <input type="hidden" name="variants[{index}][price]" value="100000">
   ```
3. For color images, keep the file inputs with `name="color_images[Red]"` etc.
4. Append all hidden inputs to form, remove the matrix display inputs (or just leave them — form won't include disabled inputs)

### 3.4 Edit Mode: Pre-fill from DB
- On page load, parse existing variants:
  - Extract unique colors → `colors[]`
  - Extract unique sizes → `sizes[]`
  - Map existing (color, size) → matrix cell with stock, price, enabled=true
- Color images: loaded from `asset('uploads/' . $v->image)` — show as preview

---

## 4. Backend Changes (ProductController)

### 4.1 `store()` — Add color_images handling
```php
// Before variant loop, map color images
$colorImages = [];
if ($request->hasFile('color_images')) {
    foreach ($request->file('color_images') as $colorName => $file) {
        $colorImages[$colorName] = $file->store('products/variants', 'uploads');
    }
}

// Then process variants with color image fallback
foreach ($request->variants as $v) {
    $vPath = null;
    if (isset($v['image']) && $v['image'] instanceof UploadedFile) {
        $vPath = $v['image']->store('products/variants', 'uploads');
    } elseif (isset($v['color']) && isset($colorImages[$v['color']])) {
        $vPath = $colorImages[$v['color']];
    }
    // ... create variant
}
```

### 4.2 `update()` — Same logic + delete-all-then-recreate (current approach preserved)

---

## 5. Files Modified

| File | Changes |
|---|---|
| `resources/views/admin/products.blade.php` | Lines 298-352 variant section → colors + sizes + matrix + color list. Lines 439-654 JS → new stateful functions. |
| `resources/views/admin/products_edit.blade.php` | Same structural changes + edit-specific pre-fill + existing image handling |
| `app/Http/Controllers/Admin/ProductController.php` | `store()` and `update()` — add `color_images` handling with per-color image fallback |

**No migration, no model changes, no new routes.**

---

## 6. Edge Cases & Considerations

- **Non-size categories**: `sizes-section` and `matrix-section` hidden, `color-list-section` shown
- **Size categories**: `color-list-section` hidden, sizes + matrix shown
- **Empty state**: Matrix shows placeholder "Tambahkan warna & ukuran terlebih dahulu"
- **0 stock**: Cell is still enabled, just shows 0. (Customer-facing disable is separate feature)
- **Edit mode**: Images for colors shown as `<img>` tags (not file inputs). New color images are uploaded, existing ones keep their path.
- **Quick Size presets**: Same as now — S/M/L/XL for fashion/outerwear, 28/30/32/34/36 for bawahan, 39/40/41/42/43 for footwear
- **Price per cell**: Falls back to product price if empty
