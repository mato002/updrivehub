@extends('layouts.admin')

@section('title', 'Dashboard')
@section('page-title', 'Dashboard')
@section('page-subtitle', 'Enterprise overview of driver recruitment operations')

@section('content')
    <div class="mb-8 grid gap-4 sm:grid-cols-2 xl:grid-cols-5">
        @foreach([
            ['label' => 'Total Applications', 'value' => $stats['total'], 'icon' => 'fa-users', 'color' => 'brand'],
            ['label' => 'Today', 'value' => $stats['today'], 'icon' => 'fa-calendar-day', 'color' => 'green'],
            ['label' => 'This Week', 'value' => $stats['this_week'], 'icon' => 'fa-chart-line', 'color' => 'amber'],
            ['label' => 'Pending Review', 'value' => $stats['pending'], 'icon' => 'fa-inbox', 'color' => 'blue'],
            ['label' => 'Avg Review Days', 'value' => $stats['avg_review_days'], 'icon' => 'fa-stopwatch', 'color' => 'violet'],
        ] as $card)
            <div class="admin-stat-card">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm text-slate-500">{{ $card['label'] }}</p>
                        <p class="mt-1 text-3xl font-bold text-slate-900">{{ number_format($card['value']) }}</p>
                    </div>
                    <div class="flex h-12 w-12 items-center justify-center rounded-xl bg-brand-100 text-brand-600">
                        <i class="fa-solid {{ $card['icon'] }} text-lg"></i>
                    </div>
                </div>
            </div>
        @endforeach
    </div>

    <div class="grid gap-6 xl:grid-cols-3">
        <div class="admin-stat-card xl:col-span-2">
            <h2 class="mb-4 flex items-center gap-2 text-base font-bold text-slate-900">
                <i class="fa-solid fa-chart-column text-brand-500"></i>
                Submissions (Last 30 Days)
            </h2>
            <div class="flex h-48 items-end gap-1">
                @php $max = max($submissionsTrend->max() ?: 1, 1); @endphp
                @forelse($submissionsTrend as $date => $total)
                    <div class="flex flex-1 flex-col items-center justify-end gap-2">
                        <div class="w-full rounded-t bg-brand-500" style="height: {{ max(4, ($total / $max) * 100) }}%"></div>
                        <span class="text-[10px] text-slate-400">{{ \Illuminate\Support\Carbon::parse($date)->format('d') }}</span>
                    </div>
                @empty
                    <p class="text-sm text-slate-500">No submission data yet.</p>
                @endforelse
            </div>
        </div>

        <div class="admin-stat-card">
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
    </div>

    <div class="mt-6 grid gap-6 xl:grid-cols-2">
        <div class="admin-stat-card">
            <h2 class="mb-4 text-base font-bold text-slate-900">Top Counties</h2>
            <div class="space-y-3">
                @forelse($topCounties as $county => $total)
                    <div class="flex items-center justify-between rounded-lg bg-slate-50 px-4 py-3">
                        <span class="font-medium text-slate-800">{{ $county }}</span>
                        <span class="font-bold text-brand-700">{{ $total }}</span>
                    </div>
                @empty
                    <p class="text-sm text-slate-500">No county data yet.</p>
                @endforelse
            </div>
        </div>

        <div class="admin-stat-card">
            <h2 class="mb-4 text-base font-bold text-slate-900">Reviewer Workload</h2>
            <div class="space-y-3">
                @forelse($reviewerWorkload as $name => $total)
                    <div class="flex items-center justify-between rounded-lg bg-slate-50 px-4 py-3">
                        <span class="font-medium text-slate-800">{{ $name }}</span>
                        <span class="font-bold text-brand-700">{{ $total }}</span>
                    </div>
                @empty
                    <p class="text-sm text-slate-500">No reviews recorded yet.</p>
                @endforelse
            </div>
        </div>
    </div>

    <div class="admin-table-wrap mt-6">
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
                            <td class="px-5 py-3"><x-admin.status-badge :status="$application->status" /></td>
                            <td class="px-5 py-3 text-slate-500">{{ $application->created_at->format('M j, Y') }}</td>
                            <td class="px-5 py-3 text-right">
                                <a href="{{ route('admin.applications.show', $application) }}" class="text-brand-600 hover:text-brand-700">
                                    <i class="fa-solid fa-eye"></i>
                                </a>
                            </td>
                        </tr>
                    @empty
                        <tr><td colspan="5" class="px-5 py-10 text-center text-slate-500">No applications yet.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
@endsection
