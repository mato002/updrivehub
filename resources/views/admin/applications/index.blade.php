@extends('layouts.admin')

@section('title', 'Applications')
@section('page-title', 'Applications')
@section('page-subtitle', 'Search, filter, export, and manage driver applications')

@section('content')
    @php
        $activeFilterCount = collect($filters ?? [])->filter(fn ($value) => filled($value))->count();
    @endphp

    {{-- Mobile toolbar --}}
    <div class="mb-4 flex flex-wrap items-center gap-2 lg:hidden">
        <button type="button" id="filter-modal-open" class="btn-primary flex-1 sm:flex-none">
            <i class="fa-solid fa-filter mr-1.5"></i>
            Filters
            @if ($activeFilterCount > 0)
                <span class="ml-1.5 rounded-full bg-white/20 px-2 py-0.5 text-xs">{{ $activeFilterCount }}</span>
            @endif
        </button>
        @if ($activeFilterCount > 0)
            <a href="{{ route('admin.applications.index') }}" class="btn-secondary">Clear</a>
        @endif
        @permission('applications.export')
            <a href="{{ route('admin.applications.export', $filters) }}" class="btn-secondary">
                <i class="fa-solid fa-file-csv"></i>
            </a>
        @endpermission
    </div>

    @if ($activeFilterCount > 0)
        <div class="mb-4 flex flex-wrap gap-2 lg:hidden">
            @if (filled($filters['search'] ?? null))
                <span class="admin-filter-chip">Search: {{ $filters['search'] }}</span>
            @endif
            @if (filled($filters['status'] ?? null))
                <span class="admin-filter-chip">Status: {{ $statuses[$filters['status']]['label'] ?? $filters['status'] }}</span>
            @endif
            @if (filled($filters['county'] ?? null))
                <span class="admin-filter-chip">County: {{ $filters['county'] }}</span>
            @endif
            @if (filled($filters['date_from'] ?? null) || filled($filters['date_to'] ?? null))
                <span class="admin-filter-chip">
                    Dates: {{ $filters['date_from'] ?? '…' }} – {{ $filters['date_to'] ?? '…' }}
                </span>
            @endif
        </div>
    @endif

    {{-- Desktop filters --}}
    <div class="admin-stat-card mb-6 hidden lg:block">
        @include('admin.applications._filters-form', ['formId' => 'applications-filter-form-desktop'])
    </div>

    {{-- Mobile filter modal --}}
    <div id="filter-modal" class="admin-modal hidden" aria-hidden="true">
        <div id="filter-modal-backdrop" class="admin-modal-backdrop"></div>
        <div class="admin-modal-panel" role="dialog" aria-modal="true" aria-labelledby="filter-modal-title">
            <div class="admin-modal-header">
                <h2 id="filter-modal-title" class="text-lg font-bold text-slate-900">Filter Applications</h2>
                <button type="button" id="filter-modal-close" class="admin-modal-close" aria-label="Close filters">
                    <i class="fa-solid fa-xmark"></i>
                </button>
            </div>
            <div class="admin-modal-body">
                @include('admin.applications._filters-form', ['formId' => 'applications-filter-form-mobile'])
            </div>
        </div>
    </div>

    @permission('applications.bulk')
        <form method="POST" action="{{ route('admin.applications.bulk-status') }}" id="bulk-form" class="admin-stat-card mb-6">
            @csrf
            @foreach($filters as $key => $value)
                @if(filled($value))
                    <input type="hidden" name="{{ $key }}" value="{{ $value }}">
                @endif
            @endforeach
            <div id="bulk-hidden-inputs"></div>
            <div class="flex flex-col gap-3 sm:flex-row sm:flex-wrap sm:items-end">
                <div class="min-w-0 flex-1 sm:min-w-[200px]">
                    <label class="form-label">Bulk status update</label>
                    <select name="status" class="form-input" required>
                        @foreach($statuses as $key => $meta)
                            <option value="{{ $key }}">{{ $meta['label'] }}</option>
                        @endforeach
                    </select>
                </div>
                <button type="submit" class="btn-primary w-full sm:w-auto" id="bulk-submit" disabled>
                    <i class="fa-solid fa-layer-group mr-1.5"></i> Update Selected
                </button>
            </div>
        </form>
    @endpermission

    {{-- Mobile card list --}}
    <div class="mb-4 space-y-3 lg:hidden">
        <p class="text-sm text-slate-600">
            Showing {{ $applications->firstItem() ?? 0 }}–{{ $applications->lastItem() ?? 0 }} of {{ $applications->total() }} applications
        </p>
        @forelse($applications as $application)
            <article class="admin-mobile-card">
                <div class="flex items-start justify-between gap-3">
                    <div class="min-w-0">
                        @permission('applications.bulk')
                            <label class="mb-2 flex items-center gap-2 text-xs text-slate-500">
                                <input type="checkbox" value="{{ $application->id }}" class="row-checkbox rounded border-slate-300">
                                Select
                            </label>
                        @endpermission
                        <p class="font-mono text-xs font-semibold text-brand-700">{{ $application->reference_number }}</p>
                        <p class="mt-1 font-semibold text-slate-900">{{ $application->full_name }}</p>
                        <p class="text-xs text-slate-500">{{ $application->email }}</p>
                    </div>
                    <x-admin.status-badge :status="$application->status" />
                </div>
                <div class="mt-3 grid grid-cols-2 gap-2 text-xs text-slate-600">
                    <div class="rounded-lg bg-slate-50 px-3 py-2">
                        <p class="text-slate-400">County</p>
                        <p class="font-medium text-slate-800">{{ $application->county }}</p>
                    </div>
                    <div class="rounded-lg bg-slate-50 px-3 py-2">
                        <p class="text-slate-400">Experience</p>
                        <p class="font-medium text-slate-800">{{ $application->years_of_experience }} yrs</p>
                    </div>
                </div>
                <div class="mt-3 flex items-center justify-between gap-3">
                    <p class="text-xs text-slate-500">{{ $application->created_at->format('M j, Y g:i A') }}</p>
                    <a href="{{ route('admin.applications.show', $application) }}" class="inline-flex items-center gap-1 rounded-lg bg-brand-50 px-3 py-1.5 text-xs font-semibold text-brand-700 hover:bg-brand-100">
                        <i class="fa-solid fa-eye"></i> View
                    </a>
                </div>
            </article>
        @empty
            <div class="admin-mobile-card py-10 text-center">
                <i class="fa-solid fa-inbox text-3xl text-slate-300"></i>
                <p class="mt-3 text-slate-500">No applications found matching your filters.</p>
            </div>
        @endforelse
        @if ($applications->hasPages())
            <div class="pt-2">{{ $applications->links() }}</div>
        @endif
    </div>

    {{-- Desktop table --}}
    <div class="admin-table-wrap hidden lg:block">
        <div class="flex items-center justify-between border-b border-slate-200 px-5 py-4">
            <p class="text-sm text-slate-600">
                Showing {{ $applications->firstItem() ?? 0 }}–{{ $applications->lastItem() ?? 0 }} of {{ $applications->total() }} applications
            </p>
        </div>
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-slate-200 text-sm">
                <thead class="bg-slate-50">
                    <tr>
                        @permission('applications.bulk')
                            <th class="px-5 py-3 text-left"><input type="checkbox" id="select-all" class="rounded border-slate-300"></th>
                        @endpermission
                        <th class="px-5 py-3 text-left font-semibold text-slate-600">Reference</th>
                        <th class="px-5 py-3 text-left font-semibold text-slate-600">Applicant</th>
                        <th class="px-5 py-3 text-left font-semibold text-slate-600">County</th>
                        <th class="px-5 py-3 text-left font-semibold text-slate-600">Experience</th>
                        <th class="px-5 py-3 text-left font-semibold text-slate-600">Status</th>
                        <th class="px-5 py-3 text-left font-semibold text-slate-600">Submitted</th>
                        <th class="px-5 py-3 text-right font-semibold text-slate-600">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @forelse($applications as $application)
                        <tr class="hover:bg-slate-50">
                            @permission('applications.bulk')
                                <td class="px-5 py-3">
                                    <input type="checkbox" value="{{ $application->id }}" class="row-checkbox rounded border-slate-300">
                                </td>
                            @endpermission
                            <td class="px-5 py-3 font-mono text-xs font-medium text-brand-700">{{ $application->reference_number }}</td>
                            <td class="px-5 py-3">
                                <p class="font-medium text-slate-900">{{ $application->full_name }}</p>
                                <p class="text-xs text-slate-500">{{ $application->email }}</p>
                                <p class="text-xs text-slate-400">{{ $application->phone }}</p>
                            </td>
                            <td class="px-5 py-3 text-slate-700">{{ $application->county }}</td>
                            <td class="px-5 py-3 text-slate-700">{{ $application->years_of_experience }} yrs</td>
                            <td class="px-5 py-3">
                                <x-admin.status-badge :status="$application->status" />
                            </td>
                            <td class="px-5 py-3 text-slate-500">
                                {{ $application->created_at->format('M j, Y') }}
                                <span class="block text-xs">{{ $application->created_at->format('g:i A') }}</span>
                            </td>
                            <td class="px-5 py-3 text-right">
                                <a href="{{ route('admin.applications.show', $application) }}" class="inline-flex items-center gap-1 rounded-lg bg-brand-50 px-3 py-1.5 text-xs font-semibold text-brand-700 hover:bg-brand-100">
                                    <i class="fa-solid fa-eye"></i> View
                                </a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8" class="px-5 py-16 text-center">
                                <i class="fa-solid fa-inbox text-3xl text-slate-300"></i>
                                <p class="mt-3 text-slate-500">No applications found matching your filters.</p>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        @if ($applications->hasPages())
            <div class="border-t border-slate-200 px-5 py-4">
                {{ $applications->links() }}
            </div>
        @endif
    </div>
@endsection

@push('scripts')
    <script>
        const filterModal = document.getElementById('filter-modal');
        const filterModalOpen = document.getElementById('filter-modal-open');
        const filterModalClose = document.getElementById('filter-modal-close');
        const filterModalBackdrop = document.getElementById('filter-modal-backdrop');

        const openFilterModal = () => {
            filterModal?.classList.remove('hidden');
            filterModal?.setAttribute('aria-hidden', 'false');
            document.body.classList.add('overflow-hidden');
        };

        const closeFilterModal = () => {
            filterModal?.classList.add('hidden');
            filterModal?.setAttribute('aria-hidden', 'true');
            document.body.classList.remove('overflow-hidden');
        };

        filterModalOpen?.addEventListener('click', openFilterModal);
        filterModalClose?.addEventListener('click', closeFilterModal);
        filterModalBackdrop?.addEventListener('click', closeFilterModal);

        document.addEventListener('keydown', (event) => {
            if (event.key === 'Escape' && !filterModal?.classList.contains('hidden')) {
                closeFilterModal();
            }
        });
    </script>
@endpush

@permission('applications.bulk')
    @push('scripts')
        <script>
            const bulkForm = document.getElementById('bulk-form');
            const hiddenInputs = document.getElementById('bulk-hidden-inputs');
            const checkboxes = document.querySelectorAll('.row-checkbox');
            const selectAll = document.getElementById('select-all');
            const bulkSubmit = document.getElementById('bulk-submit');

            function syncBulkState() {
                hiddenInputs.innerHTML = '';
                const checked = [...checkboxes].filter(cb => cb.checked);
                checked.forEach(cb => {
                    const input = document.createElement('input');
                    input.type = 'hidden';
                    input.name = 'application_ids[]';
                    input.value = cb.value;
                    hiddenInputs.appendChild(input);
                });
                bulkSubmit.disabled = checked.length === 0;
            }

            selectAll?.addEventListener('change', () => {
                checkboxes.forEach(cb => { cb.checked = selectAll.checked; });
                syncBulkState();
            });

            checkboxes.forEach(cb => cb.addEventListener('change', syncBulkState));
        </script>
    @endpush
@endpermission
