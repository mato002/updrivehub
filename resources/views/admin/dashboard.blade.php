@extends('layouts.admin')

@section('title', 'Dashboard')
@section('page-title', 'Dashboard')
@section('page-subtitle', 'Enterprise overview of driver recruitment operations')

@section('content')
    <div class="mb-6 grid grid-cols-2 gap-3 sm:gap-4 xl:grid-cols-5">
        @foreach([
            ['label' => 'Total Applications', 'value' => $stats['total'], 'icon' => 'fa-users', 'color' => 'brand'],
            ['label' => 'Today', 'value' => $stats['today'], 'icon' => 'fa-calendar-day', 'color' => 'green'],
            ['label' => 'This Week', 'value' => $stats['this_week'], 'icon' => 'fa-chart-line', 'color' => 'amber'],
            ['label' => 'Pending Review', 'value' => $stats['pending'], 'icon' => 'fa-inbox', 'color' => 'blue'],
            ['label' => 'Avg Review Days', 'value' => $stats['avg_review_days'], 'icon' => 'fa-stopwatch', 'color' => 'violet'],
        ] as $card)
            <div class="admin-stat-card admin-stat-card-compact">
                <div class="flex items-center justify-between gap-2">
                    <div class="min-w-0">
                        <p class="truncate text-xs font-medium text-slate-500 sm:text-sm">{{ $card['label'] }}</p>
                        <p class="mt-1 text-xl font-bold text-slate-900 sm:text-3xl">{{ number_format($card['value']) }}</p>
                    </div>
                    <div class="flex h-9 w-9 shrink-0 items-center justify-center rounded-xl bg-brand-100 text-brand-600 sm:h-12 sm:w-12">
                        <i class="fa-solid {{ $card['icon'] }} text-sm sm:text-lg"></i>
                    </div>
                </div>
            </div>
        @endforeach
    </div>

    <div class="grid gap-4 sm:gap-6 xl:grid-cols-3">
        <div class="admin-stat-card xl:col-span-2">
            <h2 class="mb-4 flex items-center gap-2 text-sm font-bold text-slate-900 sm:text-base">
                <i class="fa-solid fa-chart-column text-brand-500"></i>
                Submissions (Last 30 Days)
            </h2>
            <div class="flex h-36 items-end gap-1 sm:h-48">
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
            <h2 class="mb-4 flex items-center gap-2 text-sm font-bold text-slate-900 sm:text-base">
                <i class="fa-solid fa-chart-pie text-brand-500"></i>
                By Status
            </h2>
            <div class="grid grid-cols-2 gap-2 sm:grid-cols-1 sm:gap-3">
                @foreach($statuses as $key => $meta)
                    <div class="flex items-center justify-between rounded-xl bg-slate-50 px-3 py-2.5 sm:px-4 sm:py-3">
                        <x-admin.status-badge :status="$key" />
                        <span class="text-base font-bold text-slate-900 sm:text-lg">{{ $byStatus[$key] ?? 0 }}</span>
                    </div>
                @endforeach
            </div>
        </div>
    </div>

    <div class="mt-4 grid grid-cols-2 gap-4 sm:mt-6 sm:gap-6 xl:grid-cols-2">
        <div class="admin-stat-card">
            <h2 class="mb-4 text-sm font-bold text-slate-900 sm:text-base">Top Counties</h2>
            <div class="grid grid-cols-1 gap-2 sm:gap-3">
                @forelse($topCounties as $county => $total)
                    <div class="flex items-center justify-between rounded-lg bg-slate-50 px-3 py-2.5 sm:px-4 sm:py-3">
                        <span class="truncate pr-2 text-sm font-medium text-slate-800 sm:text-base">{{ $county }}</span>
                        <span class="shrink-0 font-bold text-brand-700">{{ $total }}</span>
                    </div>
                @empty
                    <p class="text-sm text-slate-500">No county data yet.</p>
                @endforelse
            </div>
        </div>

        <div class="admin-stat-card">
            <h2 class="mb-4 text-sm font-bold text-slate-900 sm:text-base">Reviewer Workload</h2>
            <div class="grid grid-cols-1 gap-2 sm:gap-3">
                @forelse($reviewerWorkload as $name => $total)
                    <div class="flex items-center justify-between rounded-lg bg-slate-50 px-3 py-2.5 sm:px-4 sm:py-3">
                        <span class="truncate pr-2 text-sm font-medium text-slate-800 sm:text-base">{{ $name }}</span>
                        <span class="shrink-0 font-bold text-brand-700">{{ $total }}</span>
                    </div>
                @empty
                    <p class="text-sm text-slate-500">No reviews recorded yet.</p>
                @endforelse
            </div>
        </div>
    </div>

    <div class="admin-table-wrap mt-4 hidden sm:block sm:mt-6">
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

    {{-- Mobile recent applications --}}
    <div class="mt-4 space-y-3 sm:hidden">
        <div class="flex items-center justify-between">
            <h2 class="flex items-center gap-2 text-sm font-bold text-slate-900">
                <i class="fa-solid fa-clock-rotate-left text-brand-500"></i>
                Recent Applications
            </h2>
            <a href="{{ route('admin.applications.index') }}" class="text-xs font-medium text-brand-600">View all</a>
        </div>
        @forelse($recent as $application)
            <article class="admin-mobile-card">
                <div class="flex items-start justify-between gap-2">
                    <div class="min-w-0">
                        <p class="font-mono text-xs font-semibold text-brand-700">{{ $application->reference_number }}</p>
                        <p class="mt-1 font-semibold text-slate-900">{{ $application->full_name }}</p>
                        <p class="text-xs text-slate-500">{{ $application->created_at->format('M j, Y') }}</p>
                    </div>
                    <x-admin.status-badge :status="$application->status" />
                </div>
                <a href="{{ route('admin.applications.show', $application) }}" class="mt-3 inline-flex items-center gap-1 text-xs font-semibold text-brand-700">
                    <i class="fa-solid fa-eye"></i> View application
                </a>
            </article>
        @empty
            <div class="admin-mobile-card py-8 text-center text-sm text-slate-500">No applications yet.</div>
        @endforelse
    </div>
@endsection
