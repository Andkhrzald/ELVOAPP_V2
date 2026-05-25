# Living Background Admin Elvo

## 1. `resources/css/app.css`
Tambahkan gradient + pattern CSS:

```css
body {
    background: 
        radial-gradient(ellipse at 20% 0%, rgba(124,109,240,0.08) 0%, transparent 50%),
        radial-gradient(ellipse at 80% 100%, rgba(60,224,196,0.05) 0%, transparent 50%),
        #0c0e1a;
}
```

## 2. `resources/views/layouts/app.blade.php`
- Ubah `<body class="bg-elvo-bg ...">` → `<body class="...">` (hapus bg-elvo-bg)
- Tambahkan watermark + event decor di dalam body (tapi di luar main content):

```html
{{-- Brand Watermark --}}
<div class="fixed inset-0 pointer-events-none select-none z-0 overflow-hidden">
    <span class="absolute bottom-8 right-8 text-[180px] sm:text-[250px] font-black italic text-white/[0.02] tracking-tighter leading-none">
        ELVO.
    </span>
    {{-- Event Decor --}}
    @if($eventTheme['active'] ?? false)
    <span class="absolute top-32 right-16 text-8xl opacity-[0.03] pointer-events-none select-none">
        {{ $eventTheme['icon'] ?? '' }}
    </span>
    <span class="absolute bottom-40 left-10 text-[12px] font-black text-white/[0.03] uppercase tracking-[0.5em] rotate-90 origin-left">
        {{ $eventTheme['name'] ?? '' }}
    </span>
    @endif
</div>
```

## 3. Sidebar Gradient
Di `app.blade.php`, sidebar `<div>` tambahkan:
```
class="... bg-gradient-to-b from-elvo-surface to-[#1a2040] ..."
```

## 4. `app/Providers/AppServiceProvider.php`
Tambah di method `boot()`:
```php
use Illuminate\Support\Facades\View;

View::share('eventTheme', [
    'active' => env('ELVO_EVENT_THEME', false) ? true : false,
    'name'   => env('ELVO_EVENT_NAME', 'Ramadan 1447 H'),
    'icon'   => env('ELVO_EVENT_ICON', '🌙'),
]);
```

## 5. `.env`
Tambah (opsional):
```
ELVO_EVENT_THEME=true
ELVO_EVENT_NAME="Ramadan 1447 H"
ELVO_EVENT_ICON="🌙"
```
