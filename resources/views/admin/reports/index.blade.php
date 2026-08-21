@extends('layouts.admin')

@section('title', 'Reports')
@section('page-title', 'Reports & Analytics')
@section('page-subtitle', 'Recruitment performance insights and export tools')

@section('content')
    <div class="admin-stat-card mb-6">
        <form method="GET" action="{{ route('admin.reports.index') }}" class="grid gap-4 md:grid-cols-2 xl:grid-cols-5">
            <div>
                <label class="form-label">From</label>
                <input type="date" name="date_from" value="{{ $filters['date_from'] }}" class="form-input">
            </div>
            <div>
                <label class="form-label">To</label>
                <input type="date" name="date_to" value="{{ $filters['date_to'] }}" class="form-input">
            </div>
            <div>
                <label class="form-label">County</label>
                <select name="county" class="form-input">
                    <option value="">All counties</option>
                    @foreach($counties as $county)
                        <option value="{{ $county }}" @selected(($filters['county'] ?? '') === $county)>{{ $county }}</option>
                    @endforeach
                </select>
            </div>
            <div>
                <label class="form-label">Status</label>
                <select name="status" class="form-input">
                    <option value="">All statuses</option>
                    @foreach($statuses as $key => $meta)
                        <option value="{{ $key }}" @selected(($filters['status'] ?? '') === $key)>{{ $meta['label'] }}</option>
                    @endforeach
                </select>
            </div>
            <div class="flex items-end gap-2 xl:col-span-1">
                <button type="submit" class="btn-primary">Apply</button>
                <a href="{{ route('admin.reports.index') }}" class="btn-secondary">Reset</a>
            </div>
        </form>
    </div>

    <div class="mb-6 grid grid-cols-2 gap-3 sm:gap-4 xl:grid-cols-5">
        @foreach([
            ['label' => 'Total', 'value' => $stats['total'], 'icon' => 'fa-users'],
            ['label' => 'Pending', 'value' => $stats['pending'], 'icon' => 'fa-inbox'],
            ['label' => 'Shortlisted', 'value' => $stats['shortlisted'], 'icon' => 'fa-star'],
            ['label' => 'Hired', 'value' => $stats['hired'], 'icon' => 'fa-user-check'],
            ['label' => 'Rejected', 'value' => $stats['rejected'], 'icon' => 'fa-user-xmark'],
        ] as $card)
            <div class="admin-stat-card admin-stat-card-compact">
                <p class="text-xs font-medium text-slate-500 sm:text-sm">{{ $card['label'] }}</p>
                <p class="mt-1 flex items-center justify-between gap-2">
                    <span class="text-xl font-bold text-slate-900 sm:text-3xl">{{ number_format($card['value']) }}</span>
                    <i class="fa-solid {{ $card['icon'] }} text-brand-500"></i>
                </p>
            </div>
        @endforeach
    </div>

    <div class="grid gap-6 xl:grid-cols-3">
        <div class="admin-stat-card xl:col-span-2">
            <h2 class="mb-4 text-base font-bold text-slate-900">Daily Submissions</h2>
            <div class="flex h-40 items-end gap-1 sm:h-52">
                @php $max = max($dailyTrend->max() ?: 1, 1); @endphp
                @forelse($dailyTrend as $date => $total)
                    <div class="flex flex-1 flex-col items-center justify-end gap-2">
                        <div class="w-full rounded-t bg-brand-500" style="height: {{ max(4, ($total / $max) * 100) }}%"></div>
                        <span class="text-[10px] text-slate-400">{{ \Illuminate\Support\Carbon::parse($date)->format('d') }}</span>
                    </div>
                @empty
                    <p class="text-sm text-slate-500">No data for this period.</p>
                @endforelse
            </div>
        </div>

        <div class="admin-stat-card">
            <h2 class="mb-4 text-base font-bold text-slate-900">By Status</h2>
            <div class="space-y-3">
                @foreach($statuses as $key => $meta)
                    <div class="flex items-center justify-between rounded-xl bg-slate-50 px-4 py-3">
                        <x-admin.status-badge :status="$key" />
                        <span class="font-bold text-slate-900">{{ $byStatus[$key] ?? 0 }}</span>
                    </div>
                @endforeach
            </div>
        </div>
    </div>

    <div class="mt-6 grid gap-6 xl:grid-cols-2">
        <div class="admin-stat-card">
            <h2 class="mb-4 text-base font-bold text-slate-900">Top Counties</h2>
            <div class="space-y-3">
                @forelse($byCounty as $county => $total)
                    <div class="flex items-center justify-between rounded-lg bg-slate-50 px-4 py-3">
                        <span class="font-medium text-slate-800">{{ $county }}</span>
                        <span class="font-bold text-brand-700">{{ $total }}</span>
                    </div>
                @empty
                    <p class="text-sm text-slate-500">No county data for this period.</p>
                @endforelse
            </div>
        </div>

        <div class="admin-stat-card">
            <h2 class="mb-4 text-base font-bold text-slate-900">Experience Bands</h2>
            <div class="space-y-3">
                @forelse($byExperience as $band => $total)
                    <div class="flex items-center justify-between rounded-lg bg-slate-50 px-4 py-3">
                        <span class="font-medium text-slate-800">{{ $band }}</span>
                        <span class="font-bold text-brand-700">{{ $total }}</span>
                    </div>
                @empty
                    <p class="text-sm text-slate-500">No experience data for this period.</p>
                @endforelse
            </div>
        </div>
    </div>

    @permission('applications.export')
        <div class="admin-stat-card mt-6 flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
            <div>
                <h2 class="text-base font-bold text-slate-900">Export Data</h2>
                <p class="mt-1 text-sm text-slate-500">Download filtered applications as CSV for reporting.</p>
            </div>
            <a href="{{ route('admin.applications.export', $filters) }}" class="btn-primary">
                <i class="fa-solid fa-file-csv mr-1.5"></i>
                Export CSV
            </a>
        </div>
    @endpermission
@endsection
