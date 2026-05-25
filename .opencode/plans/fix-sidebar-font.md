# Fix Sinkronisasi Font Sidebar Admin

## Masalah
Sidebar admin menggunakan **Instrument Sans**, sedangkan website publik menggunakan **Plus Jakarta Sans**.

## Perubahan yang diperlukan

### 1. `resources/css/app.css`
Ubah `--font-sans` dari Instrument Sans ke Plus Jakarta Sans:
```css
@theme {
    --font-sans: 'Plus Jakarta Sans', ui-sans-serif, system-ui, sans-serif, 'Apple Color Emoji', 'Segoe UI Emoji',
        'Segoe UI Symbol', 'Noto Color Emoji';
}
```

### 2. `resources/views/layouts/app.blade.php`
Tambah Google Fonts di `<head>` (setelah baris `@vite`):
```html
<link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
```

### 3. `resources/views/layouts/app.blade.php`
Ubah `<body>` untuk menggunakan font-sans:
```html
<body class="bg-[#121212] text-white font-sans">
```

## Verifikasi
Jalankan `npm run build` atau `npm run dev` untuk meng-compile ulang CSS Tailwind.
