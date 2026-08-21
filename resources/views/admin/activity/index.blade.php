@extends('layouts.admin')

@section('title', 'Activity Log')
@section('page-title', 'Activity Log')
@section('page-subtitle', 'Audit trail of admin actions across all applications')

@section('content')
    <div class="admin-stat-card mb-6">
        <form method="GET" action="{{ route('admin.activity.index') }}" class="grid gap-4 md:grid-cols-2 xl:grid-cols-5">
            <div class="xl:col-span-2">
                <label class="form-label">Search</label>
                <input type="text" name="search" value="{{ $filters['search'] ?? '' }}" placeholder="Description, reference, applicant..." class="form-input">
            </div>
            <div>
                <label class="form-label">Action</label>
                <select name="action" class="form-input">
                    <option value="">All actions</option>
                    @foreach($actions as $action)
                        <option value="{{ $action }}" @selected(($filters['action'] ?? '') === $action)>{{ str($action)->headline() }}</option>
                    @endforeach
                </select>
            </div>
            <div>
                <label class="form-label">Team Member</label>
                <select name="user_id" class="form-input">
                    <option value="">All users</option>
                    @foreach($teamMembers as $member)
                        <option value="{{ $member->id }}" @selected(($filters['user_id'] ?? '') == $member->id)>{{ $member->name }}</option>
                    @endforeach
                </select>
            </div>
            <div class="grid grid-cols-2 gap-3">
                <div>
                    <label class="form-label">From</label>
                    <input type="date" name="date_from" value="{{ $filters['date_from'] ?? '' }}" class="form-input">
                </div>
                <div>
                    <label class="form-label">To</label>
                    <input type="date" name="date_to" value="{{ $filters['date_to'] ?? '' }}" class="form-input">
                </div>
            </div>
            <div class="flex items-end gap-2 md:col-span-2 xl:col-span-5">
                <button type="submit" class="btn-primary"><i class="fa-solid fa-filter mr-1.5"></i> Filter</button>
                <a href="{{ route('admin.activity.index') }}" class="btn-secondary">Clear</a>
            </div>
        </form>
    </div>

    <div class="admin-table-wrap">
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-slate-200 text-sm">
                <thead class="bg-slate-50">
                    <tr>
                        <th class="px-5 py-3 text-left font-semibold text-slate-600">When</th>
                        <th class="px-5 py-3 text-left font-semibold text-slate-600">Action</th>
                        <th class="px-5 py-3 text-left font-semibold text-slate-600">Description</th>
                        <th class="px-5 py-3 text-left font-semibold text-slate-600">Application</th>
                        <th class="px-5 py-3 text-left font-semibold text-slate-600">User</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @forelse($logs as $log)
                        <tr class="hover:bg-slate-50">
                            <td class="px-5 py-3 whitespace-nowrap text-slate-500">
                                {{ $log->created_at->format('M j, Y') }}
                                <span class="block text-xs">{{ $log->created_at->format('g:i A') }}</span>
                            </td>
                            <td class="px-5 py-3">
                                <span class="inline-flex rounded-full bg-slate-100 px-2.5 py-1 text-xs font-semibold text-slate-700">
                                    {{ str($log->action)->headline() }}
                                </span>
                            </td>
                            <td class="px-5 py-3 text-slate-800">{{ $log->description }}</td>
                            <td class="px-5 py-3">
                                @if($log->application)
                                    <a href="{{ route('admin.applications.show', $log->application) }}" class="font-mono text-xs font-semibold text-brand-700 hover:text-brand-800">
                                        {{ $log->application->reference_number }}
                                    </a>
                                    <p class="text-xs text-slate-500">{{ $log->application->full_name }}</p>
                                @else
                                    <span class="text-slate-400">—</span>
                                @endif
                            </td>
                            <td class="px-5 py-3 text-slate-700">{{ $log->user?->name ?? 'System' }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="px-5 py-16 text-center text-slate-500">
                                <i class="fa-solid fa-clock-rotate-left text-3xl text-slate-300"></i>
                                <p class="mt-3">No activity recorded yet.</p>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        @if ($logs->hasPages())
            <div class="border-t border-slate-200 px-5 py-4">
                {{ $logs->links() }}
            </div>
        @endif
    </div>
@endsection
