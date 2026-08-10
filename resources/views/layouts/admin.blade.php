<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Admin') — {{ config('recruitment.company_name') }}</title>
    <link rel="stylesheet" href="https://fonts.bunny.net/css?family=instrument-sans:400,500,600,700">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css" crossorigin="anonymous" referrerpolicy="no-referrer" />
    @if (! empty($viteCss))
        <link rel="stylesheet" href="{{ $viteCss }}">
    @endif
    @stack('head')
</head>
<body class="bg-slate-100 antialiased">
    <div class="admin-shell">
        {{-- Sidebar --}}
        <aside id="admin-sidebar" class="admin-sidebar">
            <div class="admin-sidebar-brand">
                <div class="flex h-10 w-10 items-center justify-center rounded-xl bg-brand-600 text-white">
                    <i class="fa-solid fa-truck-fast"></i>
                </div>
                <div>
                    <p class="text-sm font-bold">{{ config('recruitment.company_name') }}</p>
                    <p class="text-xs text-slate-400">Admin Panel</p>
                </div>
            </div>

            <nav class="flex-1 space-y-1 px-3 py-4">
                <a href="{{ route('admin.dashboard') }}" class="admin-nav-link {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">
                    <i class="fa-solid fa-gauge-high w-5 text-center"></i>
                    Dashboard
                </a>
                <a href="{{ route('admin.applications.index') }}" class="admin-nav-link {{ request()->routeIs('admin.applications.*') ? 'active' : '' }}">
                    <i class="fa-solid fa-folder-open w-5 text-center"></i>
                    Applications
                </a>
                <a href="{{ route('home') }}" target="_blank" class="admin-nav-link">
                    <i class="fa-solid fa-arrow-up-right-from-square w-5 text-center"></i>
                    Public Form
                </a>
            </nav>

            <div class="border-t border-white/10 p-4">
                <div class="mb-3 flex items-center gap-3 px-2">
                    <div class="flex h-9 w-9 items-center justify-center rounded-full bg-brand-600 text-sm font-bold">
                        {{ strtoupper(substr(auth()->user()->name, 0, 1)) }}
                    </div>
                    <div class="min-w-0 flex-1">
                        <p class="truncate text-sm font-medium">{{ auth()->user()->name }}</p>
                        <p class="truncate text-xs text-slate-400">{{ auth()->user()->email }}</p>
                    </div>
                </div>
                <form action="{{ route('admin.logout') }}" method="POST">
                    @csrf
                    <button type="submit" class="admin-nav-link w-full text-left text-red-300 hover:text-red-200">
                        <i class="fa-solid fa-right-from-bracket w-5 text-center"></i>
                        Sign Out
                    </button>
                </form>
            </div>
        </aside>

        <div id="admin-overlay" class="fixed inset-0 z-40 hidden bg-black/50 lg:hidden"></div>

        <div class="admin-main">
            {{-- Header --}}
            <header class="admin-header">
                <div class="flex items-center justify-between gap-4">
                    <div class="flex items-center gap-3">
                        <button type="button" id="sidebar-toggle" class="rounded-lg border border-slate-200 p-2 text-slate-600 hover:bg-slate-50 lg:hidden">
                            <i class="fa-solid fa-bars"></i>
                        </button>
                        <div>
                            <h1 class="text-lg font-bold text-slate-900">@yield('page-title', 'Dashboard')</h1>
                            @hasSection('page-subtitle')
                                <p class="text-sm text-slate-500">@yield('page-subtitle')</p>
                            @endif
                        </div>
                    </div>
                    <div class="hidden items-center gap-2 text-sm text-slate-500 sm:flex">
                        <i class="fa-regular fa-clock"></i>
                        {{ now()->format('M j, Y — g:i A') }}
                    </div>
                </div>
            </header>

            {{-- Content --}}
            <main class="admin-content">
                @if (session('success'))
                    <div class="mb-6 flex items-center gap-2 rounded-xl border border-green-200 bg-green-50 px-4 py-3 text-sm text-green-800">
                        <i class="fa-solid fa-circle-check"></i>
                        {{ session('success') }}
                    </div>
                @endif

                @yield('content')
            </main>

            {{-- Footer --}}
            <footer class="admin-footer">
                &copy; {{ date('Y') }} {{ config('recruitment.company_name') }} — Driver Recruitment Admin
            </footer>
        </div>
    </div>

    <script>
        document.getElementById('sidebar-toggle')?.addEventListener('click', () => {
            document.getElementById('admin-sidebar')?.classList.toggle('open');
            document.getElementById('admin-overlay')?.classList.toggle('hidden');
        });
        document.getElementById('admin-overlay')?.addEventListener('click', () => {
            document.getElementById('admin-sidebar')?.classList.remove('open');
            document.getElementById('admin-overlay')?.classList.add('hidden');
        });
    </script>
    @stack('scripts')
</body>
</html>
