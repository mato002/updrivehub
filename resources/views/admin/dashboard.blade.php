@extends('layouts.admin')

@section('title', 'Dashboard')
@section('page-title', 'Dashboard')
@section('page-subtitle', 'Overview of driver recruitment applications')

@section('content')
    {{-- Stats --}}
    <div class="mb-8 grid gap-4 sm:grid-cols-2 xl:grid-cols-4">
        <div class="admin-stat-card">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-slate-500">Total Applications</p>
                    <p class="mt-1 text-3xl font-bold text-slate-900">{{ number_format($stats['total']) }}</p>
                </div>
                <div class="flex h-12 w-12 items-center justify-center rounded-xl bg-brand-100 text-brand-600">
                    <i class="fa-solid fa-users text-lg"></i>
                </div>
            </div>
        </div>
        <div class="admin-stat-card">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-slate-500">Today</p>
                    <p class="mt-1 text-3xl font-bold text-slate-900">{{ number_format($stats['today']) }}</p>
                </div>
                <div class="flex h-12 w-12 items-center justify-center rounded-xl bg-green-100 text-green-600">
                    <i class="fa-solid fa-calendar-day text-lg"></i>
                </div>
            </div>
        </div>
        <div class="admin-stat-card">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-slate-500">This Week</p>
                    <p class="mt-1 text-3xl font-bold text-slate-900">{{ number_format($stats['this_week']) }}</p>
                </div>
                <div class="flex h-12 w-12 items-center justify-center rounded-xl bg-amber-100 text-amber-600">
                    <i class="fa-solid fa-chart-line text-lg"></i>
                </div>
            </div>
        </div>
        <div class="admin-stat-card">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-slate-500">Pending Review</p>
                    <p class="mt-1 text-3xl font-bold text-slate-900">{{ number_format($byStatus['submitted'] ?? 0) }}</p>
                </div>
                <div class="flex h-12 w-12 items-center justify-center rounded-xl bg-blue-100 text-blue-600">
                    <i class="fa-solid fa-inbox text-lg"></i>
                </div>
            </div>
        </div>
    </div>

    <div class="grid gap-6 xl:grid-cols-3">
        {{-- Status breakdown --}}
        <div class="admin-stat-card xl:col-span-1">
            <h2 class="mb-4 flex items-center gap-2 text-base font-bold text-slate-900">
                <i class="fa-solid fa-chart-pie text-brand-500"></i>
                By Status
            </h2>
            <div class="space-y-3">
                @foreach($statuses as $key => $meta)
                    <div class="flex items-center justify-between rounded-xl bg-slate-50 px-4 py-3">
                        <x-admin.status-badge :status="$key" />
                        <span class="text-lg font-bold text-slate-900">{{ $byStatus[$key] ?? 0 }}</span>
                    </div>
                @endforeach
            </div>
        </div>

        {{-- Recent applications --}}
        <div class="admin-table-wrap xl:col-span-2">
            <div class="flex items-center justify-between border-b border-slate-200 px-5 py-4">
                <h2 class="flex items-center gap-2 text-base font-bold text-slate-900">
                    <i class="fa-solid fa-clock-rotate-left text-brand-500"></i>
                    Recent Applications
                </h2>
                <a href="{{ route('admin.applications.index') }}" class="text-sm font-medium text-brand-600 hover:text-brand-700">View all &rarr;</a>
            </div>
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-slate-200 text-sm">
                    <thead class="bg-slate-50">
                        <tr>
                            <th class="px-5 py-3 text-left font-semibold text-slate-600">Reference</th>
                            <th class="px-5 py-3 text-left font-semibold text-slate-600">Applicant</th>
                            <th class="px-5 py-3 text-left font-semibold text-slate-600">Status</th>
                            <th class="px-5 py-3 text-left font-semibold text-slate-600">Submitted</th>
                            <th class="px-5 py-3"></th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100">
                        @forelse($recent as $application)
                            <tr class="hover:bg-slate-50">
                                <td class="px-5 py-3 font-mono text-xs font-medium text-brand-700">{{ $application->reference_number }}</td>
                                <td class="px-5 py-3">
                                    <p class="font-medium text-slate-900">{{ $application->full_name }}</p>
                                    <p class="text-xs text-slate-500">{{ $application->email }}</p>
                                </td>
                                <td class="px-5 py-3">
                                    <x-admin.status-badge :status="$application->status" />
                                </td>
                                <td class="px-5 py-3 text-slate-500">{{ $application->created_at->format('M j, Y') }}</td>
                                <td class="px-5 py-3 text-right">
                                    <a href="{{ route('admin.applications.show', $application) }}" class="text-brand-600 hover:text-brand-700">
                                        <i class="fa-solid fa-eye"></i>
                                    </a>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="px-5 py-10 text-center text-slate-500">No applications yet.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
@endsection
