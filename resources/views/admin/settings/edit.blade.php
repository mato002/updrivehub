@extends('layouts.admin')

@section('title', 'Settings')
@section('page-title', 'Organization Settings')
@section('page-subtitle', 'Manage branding, contact details, and notification preferences')

@section('content')
    <div class="admin-stat-card max-w-3xl">
        <form method="POST" action="{{ route('admin.settings.update') }}" class="space-y-4">
            @csrf
            @method('PATCH')
            <div>
                <label class="form-label">Company Name</label>
                <input type="text" name="company_name" value="{{ old('company_name', $settings['company_name']) }}" class="form-input" required>
            </div>
            <div class="grid gap-4 md:grid-cols-2">
                <div>
                    <label class="form-label">HR Email</label>
                    <input type="email" name="hr_email" value="{{ old('hr_email', $settings['hr_email']) }}" class="form-input" required>
                </div>
                <div>
                    <label class="form-label">Recruitment Email</label>
                    <input type="email" name="email" value="{{ old('email', $settings['email']) }}" class="form-input" required>
                </div>
            </div>
            <div>
                <label class="form-label">Phone</label>
                <input type="text" name="phone" value="{{ old('phone', $settings['phone']) }}" class="form-input" required>
            </div>
            <div>
                <label class="form-label">Address</label>
                <textarea name="address" rows="3" class="form-input" required>{{ old('address', $settings['address']) }}</textarea>
            </div>
            <label class="flex items-center gap-2 text-sm text-slate-700">
                <input type="checkbox" name="notify_applicant_on_status_change" value="1" class="rounded border-slate-300" @checked(old('notify_applicant_on_status_change', $settings['notify_applicant_on_status_change']))>
                Email applicants when application status changes
            </label>
            @permission('settings.manage')
                <button type="submit" class="btn-primary">Save Settings</button>
            @endpermission
        </form>
    </div>
@endsection
