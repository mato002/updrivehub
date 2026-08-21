@extends('layouts.admin')

@section('title', 'My Profile')
@section('page-title', 'My Profile')
@section('page-subtitle', 'Manage your account details and password')

@section('content')
    <div class="grid gap-6 xl:grid-cols-3">
        <div class="admin-stat-card xl:col-span-2">
            <form method="POST" action="{{ route('admin.profile.update') }}" class="space-y-5">
                @csrf
                @method('PATCH')

                <div>
                    <label for="name" class="form-label">Full Name</label>
                    <input type="text" name="name" id="name" value="{{ old('name', $user->name) }}" required class="form-input">
                    @error('name')<p class="form-error">{{ $message }}</p>@enderror
                </div>

                <div>
                    <label for="email" class="form-label">Email Address</label>
                    <input type="email" name="email" id="email" value="{{ old('email', $user->email) }}" required class="form-input">
                    @error('email')<p class="form-error">{{ $message }}</p>@enderror
                </div>

                <div class="rounded-xl border border-slate-200 bg-slate-50 p-5">
                    <h3 class="mb-4 text-sm font-bold text-slate-900">Change Password</h3>
                    <p class="mb-4 text-sm text-slate-500">Leave blank to keep your current password.</p>
                    <div class="space-y-4">
                        <div>
                            <label for="current_password" class="form-label">Current Password</label>
                            <input type="password" name="current_password" id="current_password" class="form-input" autocomplete="current-password">
                            @error('current_password')<p class="form-error">{{ $message }}</p>@enderror
                        </div>
                        <div>
                            <label for="password" class="form-label">New Password</label>
                            <input type="password" name="password" id="password" class="form-input" autocomplete="new-password">
                            @error('password')<p class="form-error">{{ $message }}</p>@enderror
                        </div>
                        <div>
                            <label for="password_confirmation" class="form-label">Confirm New Password</label>
                            <input type="password" name="password_confirmation" id="password_confirmation" class="form-input" autocomplete="new-password">
                        </div>
                    </div>
                </div>

                <button type="submit" class="btn-primary">
                    <i class="fa-solid fa-floppy-disk mr-1.5"></i>
                    Save Changes
                </button>
            </form>
        </div>

        <div class="space-y-6">
            <section class="admin-stat-card text-center">
                <span class="mx-auto flex h-20 w-20 items-center justify-center rounded-full bg-brand-600 text-2xl font-bold text-white">
                    {{ strtoupper(substr($user->name, 0, 1)) }}
                </span>
                <h2 class="mt-4 text-lg font-bold text-slate-900">{{ $user->name }}</h2>
                <p class="text-sm text-slate-500">{{ $user->email }}</p>
                <span class="mt-3 inline-flex rounded-full bg-brand-50 px-3 py-1 text-xs font-semibold text-brand-700">
                    {{ $user->roleLabel() }}
                </span>
            </section>

            <section class="admin-stat-card">
                <h3 class="mb-3 text-sm font-bold uppercase tracking-wide text-slate-500">Account</h3>
                <dl class="space-y-3 text-sm">
                    <div class="flex justify-between gap-4">
                        <dt class="text-slate-500">Status</dt>
                        <dd class="font-medium {{ $user->is_active ? 'text-green-700' : 'text-red-600' }}">
                            {{ $user->is_active ? 'Active' : 'Inactive' }}
                        </dd>
                    </div>
                    <div class="flex justify-between gap-4">
                        <dt class="text-slate-500">Member since</dt>
                        <dd class="font-medium text-slate-900">{{ $user->created_at->format('M j, Y') }}</dd>
                    </div>
                </dl>
            </section>
        </div>
    </div>
@endsection
