<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Driver Recruitment') — {{ config('recruitment.company_name') }}</title>
    <link rel="stylesheet" href="https://fonts.bunny.net/css?family=instrument-sans:400,500,600">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css" crossorigin="anonymous" referrerpolicy="no-referrer" />
    @if (file_exists(public_path('build/manifest.json')))
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    @endif
    @stack('head')
</head>
@php($hasHeroSection = $__env->hasSection('hero'))
<body @class([
    'min-h-screen flex flex-col',
    'form-page-bg' => $hasHeroSection,
])>
    <header @class([
        'sticky top-0 z-40 border-b',
        'glass-header' => $hasHeroSection,
        'border-slate-200 bg-white/95 backdrop-blur' => ! $hasHeroSection,
    ])>
        <div class="mx-auto flex max-w-6xl items-center justify-between px-4 py-4 sm:px-6">
            <a href="{{ route('home') }}" class="flex items-center gap-3">
                <div class="flex h-10 w-10 items-center justify-center rounded-xl bg-brand-600 text-sm font-bold text-white shadow-lg shadow-brand-600/30">
                    <i class="fa-solid fa-truck-fast text-sm"></i>
                </div>
                <div>
                    <p class="text-sm font-bold text-slate-900">{{ config('recruitment.company_name') }}</p>
                    <p class="text-xs text-slate-500">Driver Recruitment</p>
                </div>
            </a>
            <nav class="flex items-center gap-3">
                <a href="{{ route('info') }}" class="hidden items-center gap-1.5 text-sm font-medium text-slate-600 hover:text-brand-600 sm:inline-flex">
                    <i class="fa-solid fa-circle-info"></i> About
                </a>
                <a href="{{ route('home') }}" class="btn-primary !px-4 !py-2">
                    <i class="fa-solid fa-pen-to-square mr-1.5"></i> Apply Now
                </a>
            </nav>
        </div>
    </header>

    <main class="flex-1">
        @yield('content')
    </main>

    <footer @class([
        'border-t',
        'glass-footer' => $hasHeroSection,
        'border-slate-200 bg-white' => ! $hasHeroSection,
    ])>
        <div class="mx-auto max-w-6xl px-4 py-8 sm:px-6">
            <div class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
                <div>
                    <p class="flex items-center gap-2 font-semibold text-slate-900">
                        <i class="fa-solid fa-building text-brand-500"></i>
                        {{ config('recruitment.company_name') }}
                    </p>
                    <p class="mt-1 flex items-center gap-2 text-sm text-slate-500">
                        <i class="fa-solid fa-location-dot text-brand-400"></i>
                        {{ config('recruitment.address') }}
                    </p>
                </div>
                <div class="space-y-1 text-sm text-slate-500">
                    <p class="flex items-center gap-2"><i class="fa-solid fa-phone text-brand-400"></i>{{ config('recruitment.phone') }}</p>
                    <p class="flex items-center gap-2"><i class="fa-solid fa-envelope text-brand-400"></i>{{ config('recruitment.email') }}</p>
                </div>
            </div>
            <p class="mt-6 text-center text-xs text-slate-400">&copy; {{ date('Y') }} {{ config('recruitment.company_name') }}. All rights reserved.</p>
        </div>
    </footer>

    @stack('scripts')
</body>
</html>
