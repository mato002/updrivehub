@extends('layouts.admin')

@section('title', 'Forgot Password')

@section('content')
    <div class="mx-auto max-w-md admin-stat-card mt-10">
        <h1 class="text-xl font-bold text-slate-900">Reset your password</h1>
        <p class="mt-2 text-sm text-slate-500">Enter your admin email and we will send a reset link.</p>
        <form method="POST" action="{{ route('admin.password.email') }}" class="mt-6 space-y-4">
            @csrf
            <div>
                <label class="form-label">Email</label>
                <input type="email" name="email" value="{{ old('email') }}" class="form-input" required>
            </div>
            <button type="submit" class="btn-primary w-full">Send Reset Link</button>
        </form>
        <a href="{{ route('admin.login') }}" class="mt-4 inline-block text-sm text-brand-600">Back to login</a>
    </div>
@endsection
