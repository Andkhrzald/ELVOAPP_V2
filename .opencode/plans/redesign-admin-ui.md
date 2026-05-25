# Redesign UI Admin Elvo — Eksekusi Bertahap

## Palette Warna Baru

```css
--color-elvo-bg:        #0c0e1a    /* background utama */
--color-elvo-surface:   #151a2e    /* kartu, sidebar */
--color-elvo-elevated:  #1c2340    /* hover, active */
--color-elvo-primary:   #7c6df0    /* violet — signature */
--color-elvo-secondary: #3ce0c4    /* teal */
--color-elvo-rose:      #ff6b8a    /* danger / aksen hangat */
--color-elvo-amber:     #fbbf24    /* warning */
--color-elvo-emerald:   #4ade80    /* success */
--color-elvo-sky:       #38bdf8    /* info */
```

---

## FASE 1 — Palet + Sidebar + Cards

### 1. `resources/css/app.css`
Tambahkan custom colors di `@theme`:
```css
@theme {
    --font-sans: 'Plus Jakarta Sans', ...;
    --color-elvo-bg: #0c0e1a;
    --color-elvo-surface: #151a2e;
    --color-elvo-elevated: #1c2340;
    --color-elvo-primary: #7c6df0;
    --color-elvo-secondary: #3ce0c4;
    --color-elvo-rose: #ff6b8a;
    --color-elvo-amber: #fbbf24;
    --color-elvo-emerald: #4ade80;
    --color-elvo-sky: #38bdf8;
}
```

### 2. `resources/views/layouts/app.blade.php`
- **Body**: `bg-[#0c0e1a]` ganti `#121212`
- **Sidebar**: `bg-[#151a2e]` ganti `#1a1a1a`, tambah border-right glow subtle
- **Navbar**: `bg-[#151a2e]` dengan `backdrop-blur-xl` glass effect
- **Active sidebar item**: Ubah dari `border-blue-500` ke `border-[#7c6df0]` + tambah `shadow-[inset_3px_0_0_#7c6df0]` + glow
- **Sidebar item hover**: `hover:bg-white/[0.03]` + `hover:translate-x-0.5`
- **Section header**: Tambah bullet dot `w-1.5 h-1.5 rounded-full bg-[#7c6df0]` sebelum teks
- **Logo**: `text-[#7c6df0]` ganti `text-blue-500`

### 3. Update Warna di SEMUA halaman admin (12 file)
| Cari | Ganti |
|------|-------|
| `bg-[#121212]` | `bg-[#0c0e1a]` |
| `bg-[#1a1a1a]` | `bg-[#151a2e]` |
| `text-blue-500` | `text-[#7c6df0]` |
| `text-blue-400` | `text-[#8b7df2]` |
| `bg-blue-500/10` | `bg-[#7c6df0]/10` |
| `bg-blue-600` | `bg-[#7c6df0]` |
| `hover:bg-blue-500` | `hover:bg-[#6a5cd8]` |
| `border-blue-500` | `border-[#7c6df0]` |
| `focus:border-blue-500` | `focus:border-[#7c6df0]` |
| `ring-blue-500` | `ring-[#7c6df0]` |

### 4. Stat Cards (di setiap halaman)
Tambah efek pada card:
- `border border-white/[0.06]`
- `shadow-[0_0_25px_rgba(124,109,240,0.06)]`
- Icon container: ganti dengan warna baru (violet, teal, rose, amber, emerald)
- Hover: `hover:-translate-y-0.5 hover:shadow-[0_0_35px_rgba(124,109,240,0.12)]`

---

## FASE 2 — Button + Badge + Input (nanti)

| Elemen | Perubahan |
|--------|-----------|
| Primary button | Gradien `from-[#7c6df0] to-[#6a5cd8]` + glow hover |
| Danger button | `bg-[#ff6b8a]` + glow |
| Badge status | Warna custom + subtle glow |

## FASE 3 — Animasi + Refinements (nanti)

- Custom scrollbar violet
- Micro-animations card
- Glass effect konsisten
- Typography refinement
