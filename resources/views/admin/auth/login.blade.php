<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Sign In — {{ $companyName }} Admin</title>
    <link rel="stylesheet" href="https://fonts.bunny.net/css?family=instrument-sans:400,500,600,700">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css" crossorigin="anonymous" referrerpolicy="no-referrer" />
    @if (! empty($viteCss))
        <link rel="stylesheet" href="{{ $viteCss }}">
    @endif
</head>
<body class="antialiased">
    <div class="login-shell">
        {{-- Brand panel --}}
        <aside class="login-brand-panel">
            <div class="login-brand-overlay"></div>
            <div class="relative z-10">
                <div class="flex items-center gap-3">
                    <div class="flex h-12 w-12 items-center justify-center rounded-2xl bg-brand-600 shadow-xl shadow-brand-900/40">
                        <i class="fa-solid fa-truck-fast text-lg"></i>
                    </div>
                    <div>
                        <p class="text-lg font-bold">{{ $companyName }}</p>
                        <p class="text-sm text-brand-200">Recruitment Admin Portal</p>
                    </div>
                </div>

                <div class="mt-16 max-w-md">
                    <h1 class="text-4xl font-bold leading-tight">Manage driver applications with confidence.</h1>
                    <p class="mt-4 text-lg text-slate-300">
                        Review submissions, update statuses, download documents, and keep your hiring pipeline organised — all in one place.
                    </p>
                </div>

                <ul class="mt-12 space-y-4">
                    @foreach([
                        ['icon' => 'fa-gauge-high', 'text' => 'Real-time dashboard & analytics'],
                        ['icon' => 'fa-folder-open', 'text' => 'Full application review workflow'],
                        ['icon' => 'fa-shield-halved', 'text' => 'Secure document access'],
                        ['icon' => 'fa-users', 'text' => 'Team-ready admin access'],
                    ] as $feature)
                        <li class="flex items-center gap-3 text-slate-200">
                            <span class="flex h-9 w-9 items-center justify-center rounded-lg bg-white/10">
                                <i class="fa-solid {{ $feature['icon'] }} text-brand-300"></i>
                            </span>
                            {{ $feature['text'] }}
                        </li>
                    @endforeach
                </ul>
            </div>

            <div class="relative z-10 text-sm text-slate-400">
                &copy; {{ date('Y') }} {{ $companyName }}. Enterprise Recruitment Platform.
            </div>
        </aside>

        {{-- Form panel --}}
        <main class="login-form-panel">
            <div class="mx-auto w-full max-w-md">
                {{-- Mobile brand --}}
                <div class="mb-8 flex items-center gap-3 lg:hidden">
                    <div class="flex h-10 w-10 items-center justify-center rounded-xl bg-brand-600 text-white">
                        <i class="fa-solid fa-truck-fast"></i>
                    </div>
                    <div>
                        <p class="font-bold text-slate-900">{{ $companyName }}</p>
                        <p class="text-xs text-slate-500">Admin Portal</p>
                    </div>
                </div>

                <div class="mb-8">
                    <h2 class="text-2xl font-bold text-slate-900">Welcome back</h2>
                    <p class="mt-1 text-sm text-slate-500">Sign in to your admin account to continue</p>
                </div>

                @if (session('success'))
                    <div class="mb-6 flex items-center gap-2 rounded-xl border border-green-200 bg-green-50 px-4 py-3 text-sm text-green-800">
                        <i class="fa-solid fa-circle-check"></i>
                        {{ session('success') }}
                    </div>
                @endif

                @if ($errors->any())
                    <div class="mb-6 flex items-start gap-3 rounded-xl border border-red-200 bg-red-50 px-4 py-3 text-sm text-red-700">
                        <i class="fa-solid fa-circle-exclamation mt-0.5"></i>
                        <div>
                            @foreach ($errors->all() as $error)
                                <p>{{ $error }}</p>
                            @endforeach
                        </div>
                    </div>
                @endif

                <form id="login-form" method="POST" action="{{ route('admin.login.store') }}" class="space-y-5">
                    @csrf
                    <div>
                        <label for="email" class="form-label">Work Email</label>
                        <div class="login-input-wrap">
                            <i class="fa-solid fa-envelope login-input-icon"></i>
                            <input type="email" name="email" id="email" value="{{ old('email') }}" required autofocus class="login-input" placeholder="you@company.com">
                        </div>
                    </div>
                    <div>
                        <div class="mb-1.5 flex items-center justify-between">
                            <label for="password" class="form-label !mb-0">Password</label>
                            <a href="{{ route('admin.password.request') }}" class="text-xs font-medium text-brand-600 hover:text-brand-700">Forgot password?</a>
                        </div>
                        <div class="login-input-wrap">
                            <i class="fa-solid fa-lock login-input-icon"></i>
                            <input type="password" name="password" id="password" required class="login-input pr-10" placeholder="Enter your password">
                            <button type="button" id="toggle-password" class="absolute right-3 top-1/2 -translate-y-1/2 text-slate-400 hover:text-slate-600" aria-label="Toggle password visibility">
                                <i class="fa-solid fa-eye" id="toggle-password-icon"></i>
                            </button>
                        </div>
                    </div>
                    <label class="flex items-center gap-2 text-sm text-slate-600">
                        <input type="checkbox" name="remember" value="1" class="rounded border-slate-300 text-brand-600 focus:ring-brand-500">
                        Keep me signed in for 30 days
                    </label>
                    <button type="submit" class="btn-primary w-full !py-3 text-base">
                        <i class="fa-solid fa-right-to-bracket mr-2"></i>
                        Sign In to Admin
                    </button>
                </form>

                @if (count($demoAccounts) > 0)
                    <div class="mt-10">
                        <div class="relative mb-5">
                            <div class="absolute inset-0 flex items-center"><div class="w-full border-t border-slate-200"></div></div>
                            <div class="relative flex justify-center text-xs uppercase">
                                <span class="bg-white px-3 font-semibold tracking-wider text-slate-400">Demo Accounts</span>
                            </div>
                        </div>
                        <p class="mb-4 text-center text-xs text-slate-500">
                            Click a demo account to sign in instantly
                            @if($demoPassword)
                                <span class="block mt-1">Password: <code class="rounded bg-slate-100 px-1.5 py-0.5 font-mono text-slate-700">{{ $demoPassword }}</code></span>
                            @endif
                        </p>
                        <div class="space-y-2">
                            @foreach($demoAccounts as $account)
                                <button
                                    type="button"
                                    class="login-demo-card demo-login-btn"
                                    data-email="{{ $account['email'] }}"
                                    data-password="{{ $demoPassword }}"
                                >
                                    <span @class([
                                        'flex h-10 w-10 shrink-0 items-center justify-center rounded-xl text-white',
                                        'bg-brand-600' => ($account['accent'] ?? 'brand') === 'brand',
                                        'bg-emerald-600' => ($account['accent'] ?? '') === 'emerald',
                                        'bg-violet-600' => ($account['accent'] ?? '') === 'violet',
                                    ])>
                                        <i class="fa-solid {{ $account['icon'] ?? 'fa-user' }}"></i>
                                    </span>
                                    <span class="min-w-0 flex-1">
                                        <span class="block truncate text-sm font-semibold text-slate-900">{{ $account['name'] }}</span>
                                        <span class="block truncate text-xs text-slate-500">{{ $account['email'] }}</span>
                                    </span>
                                    <span class="shrink-0 rounded-full bg-white px-2.5 py-1 text-xs font-medium text-slate-600 ring-1 ring-slate-200">
                                        {{ $account['role'] }}
                                    </span>
                                </button>
                            @endforeach
                        </div>
                    </div>
                @endif

                <p class="mt-10 text-center text-sm text-slate-500">
                    <a href="{{ route('home') }}" class="font-medium text-brand-600 hover:text-brand-700">
                        <i class="fa-solid fa-arrow-left mr-1"></i> Back to public application form
                    </a>
                </p>
            </div>
        </main>
    </div>

    <script>
        document.getElementById('toggle-password')?.addEventListener('click', () => {
            const input = document.getElementById('password');
            const icon = document.getElementById('toggle-password-icon');
            if (!input || !icon) return;
            const show = input.type === 'password';
            input.type = show ? 'text' : 'password';
            icon.classList.toggle('fa-eye', !show);
            icon.classList.toggle('fa-eye-slash', show);
        });

        document.querySelectorAll('.demo-login-btn').forEach(btn => {
            btn.addEventListener('click', () => {
                const email = document.getElementById('email');
                const password = document.getElementById('password');
                const form = document.getElementById('login-form');
                if (email) email.value = btn.dataset.email || '';
                if (password) password.value = btn.dataset.password || '';
                form?.submit();
            });
        });
    </script>
</body>
</html>
