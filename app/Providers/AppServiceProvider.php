<?php

namespace App\Providers;

use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        //
    }

    public function boot(): void
    {
        $manifestPath = public_path('build/manifest.json');

        if (! file_exists($manifestPath)) {
            return;
        }

        $manifest = json_decode(file_get_contents($manifestPath), true);

        View::share('viteCss', isset($manifest['resources/css/app.css']['file'])
            ? asset('build/'.$manifest['resources/css/app.css']['file'])
            : null);

        View::share('viteJs', isset($manifest['resources/js/app.js']['file'])
            ? asset('build/'.$manifest['resources/js/app.js']['file'])
            : null);
    }
}
