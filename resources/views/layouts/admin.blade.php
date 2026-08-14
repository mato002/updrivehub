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
    @php
        $breadcrumb = match (true) {
            request()->routeIs('admin.dashboard') => 'Dashboard',
            request()->routeIs('admin.applications.index') => 'Applications',
            request()->routeIs('admin.applications.show') => 'Application detail',
            request()->routeIs('admin.users.create') => 'Team / Add member',
            request()->routeIs('admin.users.edit') => 'Team / Edit member',
            request()->routeIs('admin.users.*') => 'Team',
            request()->routeIs('admin.settings.*') => 'Settings',
            default => 'Admin',
        };
    @endphp

    <div class="admin-shell">
        {{-- Sidebar --}}
        <aside id="admin-sidebar" class="admin-sidebar">
            <div class="admin-sidebar-brand">
                <div class="flex h-10 w-10 shrink-0 items-center justify-center rounded-xl bg-brand-600 text-white">
                    <i class="fa-solid fa-truck-fast"></i>
                </div>
                <div class="min-w-0">
                    <p class="truncate text-sm font-bold">{{ config('recruitment.company_name') }}</p>
                    <p class="text-xs text-slate-400">Admin Panel</p>
                </div>
            </div>

            <nav class="admin-sidebar-nav space-y-1">
                <a href="{{ route('admin.dashboard') }}" class="admin-nav-link {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">
                    <i class="fa-solid fa-gauge-high w-5 text-center"></i>
                    Dashboard
                </a>
                <a href="{{ route('admin.applications.index') }}" class="admin-nav-link {{ request()->routeIs('admin.applications.*') ? 'active' : '' }}">
                    <i class="fa-solid fa-folder-open w-5 text-center"></i>
                    Applications
                </a>
                @permission('users.view')
                    <a href="{{ route('admin.users.index') }}" class="admin-nav-link {{ request()->routeIs('admin.users.*') ? 'active' : '' }}">
                        <i class="fa-solid fa-users-gear w-5 text-center"></i>
                        Team
                    </a>
                @endpermission
                @permission('settings.view')
                    <a href="{{ route('admin.settings.edit') }}" class="admin-nav-link {{ request()->routeIs('admin.settings.*') ? 'active' : '' }}">
                        <i class="fa-solid fa-gear w-5 text-center"></i>
                        Settings
                    </a>
                @endpermission
            </nav>

            <div class="admin-sidebar-footer">
                <a href="{{ route('home') }}" target="_blank" rel="noopener noreferrer" class="admin-sidebar-utility-link">
                    <i class="fa-solid fa-arrow-up-right-from-square"></i>
                    <span>View public application form</span>
                </a>
            </div>
        </aside>

        <div id="admin-overlay" class="admin-sidebar-overlay hidden"></div>

        <div class="admin-main">
            {{-- Header --}}
            <header class="admin-header">
                <div class="admin-header-inner">
                    <button type="button" id="sidebar-toggle" class="admin-mobile-menu-btn" aria-label="Open navigation">
                        <i class="fa-solid fa-bars"></i>
                    </button>

                    <div class="admin-header-title">
                        <nav class="admin-breadcrumb" aria-label="Breadcrumb">
                            <span>Admin</span>
                            <i class="fa-solid fa-chevron-right text-[10px]"></i>
                            <span>{{ $breadcrumb }}</span>
                        </nav>
                        <h1 class="truncate text-lg font-bold text-slate-900 sm:text-xl">@yield('page-title', 'Dashboard')</h1>
                        @hasSection('page-subtitle')
                            <p class="line-clamp-2 text-sm text-slate-500 sm:line-clamp-none">@yield('page-subtitle')</p>
                        @endif
                    </div>

                    <div class="admin-header-actions">
                        @permission('applications.view')
                            @if (($pendingApplicationsCount ?? 0) > 0)
                                <a href="{{ route('admin.applications.index', ['status' => 'submitted']) }}" class="admin-header-pill admin-header-pill-warning">
                                    <i class="fa-solid fa-inbox"></i>
                                    <span>{{ $pendingApplicationsCount }}</span>
                                    <span class="hidden sm:inline">pending</span>
                                </a>
                            @endif
                        @endpermission

                        <span class="admin-header-pill admin-header-pill-muted hidden md:inline-flex">
                            <i class="fa-regular fa-clock"></i>
                            <span>{{ now()->format('M j, Y') }}</span>
                            <span class="hidden lg:inline">{{ now()->format('— g:i A') }}</span>
                        </span>

                        <div class="admin-profile-wrap">
                            <button type="button" id="profile-toggle" class="admin-profile-btn" aria-expanded="false" aria-haspopup="true">
                                <span class="admin-profile-avatar">{{ strtoupper(substr(auth()->user()->name, 0, 1)) }}</span>
                                <span class="hidden min-w-0 md:block">
                                    <span class="block truncate text-sm font-semibold text-slate-900">{{ auth()->user()->name }}</span>
                                    <span class="block truncate text-xs text-slate-500">{{ auth()->user()->roleLabel() }}</span>
                                </span>
                                <i class="fa-solid fa-chevron-down hidden text-xs text-slate-400 md:inline"></i>
                            </button>

                            <div id="profile-menu" class="admin-profile-menu hidden" role="menu">
                                <div class="admin-profile-menu-header">
                                    <p class="truncate font-semibold text-slate-900">{{ auth()->user()->name }}</p>
                                    <p class="truncate text-sm text-slate-500">{{ auth()->user()->email }}</p>
                                    <span class="mt-2 inline-flex rounded-full bg-brand-50 px-2.5 py-0.5 text-xs font-semibold text-brand-700">
                                        {{ auth()->user()->roleLabel() }}
                                    </span>
                                </div>
                                @permission('settings.view')
                                    <a href="{{ route('admin.settings.edit') }}" class="admin-profile-menu-item" role="menuitem">
                                        <i class="fa-solid fa-gear w-4 text-center text-slate-400"></i>
                                        Settings
                                    </a>
                                @endpermission
                                <a href="{{ route('admin.applications.index') }}" class="admin-profile-menu-item" role="menuitem">
                                    <i class="fa-solid fa-folder-open w-4 text-center text-slate-400"></i>
                                    Applications
                                </a>
                                <div class="border-t border-slate-100">
                                    <form action="{{ route('admin.logout') }}" method="POST">
                                        @csrf
                                        <button type="submit" class="admin-profile-menu-item admin-profile-menu-item-danger" role="menuitem">
                                            <i class="fa-solid fa-right-from-bracket w-4 text-center"></i>
                                            Sign out
                                        </button>
                                    </form>
                                </div>
                            </div>
                        </div>
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
        const sidebar = document.getElementById('admin-sidebar');
        const overlay = document.getElementById('admin-overlay');
        const profileToggle = document.getElementById('profile-toggle');
        const profileMenu = document.getElementById('profile-menu');

        const closeSidebar = () => {
            sidebar?.classList.remove('open');
            overlay?.classList.add('hidden');
        };

        document.getElementById('sidebar-toggle')?.addEventListener('click', () => {
            sidebar?.classList.toggle('open');
            overlay?.classList.toggle('hidden');
        });

        overlay?.addEventListener('click', closeSidebar);

        window.addEventListener('resize', () => {
            if (window.innerWidth >= 1024) {
                closeSidebar();
            }
        });

        const closeProfileMenu = () => {
            profileMenu?.classList.add('hidden');
            profileToggle?.setAttribute('aria-expanded', 'false');
        };

        profileToggle?.addEventListener('click', (event) => {
            event.stopPropagation();
            const isOpen = !profileMenu?.classList.contains('hidden');
            if (isOpen) {
                closeProfileMenu();
            } else {
                profileMenu?.classList.remove('hidden');
                profileToggle?.setAttribute('aria-expanded', 'true');
            }
        });

        profileMenu?.addEventListener('click', (event) => event.stopPropagation());

        document.addEventListener('click', closeProfileMenu);

        document.addEventListener('keydown', (event) => {
            if (event.key === 'Escape') {
                closeProfileMenu();
                closeSidebar();
            }
        });
    </script>
    @stack('scripts')
</body>
</html>
