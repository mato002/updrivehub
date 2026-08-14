<?php

namespace App\Providers;

use App\Models\DriverApplication;
use App\Services\SettingsService;
use Illuminate\Support\Facades\Blade;
use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->app->singleton(SettingsService::class);
    }

    public function boot(): void
    {
        Blade::if('permission', fn (string $permission) => auth()->user()?->hasPermission($permission) ?? false);

        $manifestPath = public_path('build/manifest.json');

        if (file_exists($manifestPath)) {
            $manifest = json_decode(file_get_contents($manifestPath), true);

            View::share('viteCss', isset($manifest['resources/css/app.css']['file'])
                ? '/build/'.$manifest['resources/css/app.css']['file']
                : null);

            View::share('viteJs', isset($manifest['resources/js/app.js']['file'])
                ? '/build/'.$manifest['resources/js/app.js']['file']
                : null);
        }

        View::composer('*', function ($view) {
            if (! app()->bound(SettingsService::class)) {
                return;
            }

            $settings = app(SettingsService::class);
            $view->with('companyName', $settings->get('company_name', config('recruitment.company_name')));
        });

        View::composer('layouts.admin', function ($view) {
            $pendingApplicationsCount = 0;

            if (auth()->check() && auth()->user()->hasPermission('applications.view')) {
                $pendingApplicationsCount = DriverApplication::query()
                    ->where('status', 'submitted')
                    ->count();
            }

            $view->with('pendingApplicationsCount', $pendingApplicationsCount);
        });
    }
}
