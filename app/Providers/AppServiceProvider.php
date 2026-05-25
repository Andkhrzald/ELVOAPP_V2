<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\View;

class AppServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        //
    }

    public function boot(): void
    {
        View::share('eventTheme', [
            'active' => env('ELVO_EVENT_THEME', false),
            'name'   => env('ELVO_EVENT_NAME', 'Ramadan 1447 H'),
            'icon'   => env('ELVO_EVENT_ICON', '🌙'),
        ]);
    }
}
