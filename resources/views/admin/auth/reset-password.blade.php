@extends('layouts.admin')

@section('title', 'Reset Password')

@section('content')
    <div class="mx-auto max-w-md admin-stat-card mt-10">
        <h1 class="text-xl font-bold text-slate-900">Choose a new password</h1>
        <form method="POST" action="{{ route('admin.password.update') }}" class="mt-6 space-y-4">
            @csrf
            <input type="hidden" name="token" value="{{ $token }}">
            <div>
                <label class="form-label">Email</label>
                <input type="email" name="email" value="{{ old('email', $email) }}" class="form-input" required>
            </div>
            <div>
                <label class="form-label">New Password</label>
                <input type="password" name="password" class="form-input" required>
            </div>
            <div>
                <label class="form-label">Confirm Password</label>
                <input type="password" name="password_confirmation" class="form-input" required>
            </div>
            <button type="submit" class="btn-primary w-full">Reset Password</button>
        </form>
    </div>
@endsection
