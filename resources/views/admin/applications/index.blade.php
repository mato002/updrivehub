@extends('layouts.admin')

@section('title', 'Applications')
@section('page-title', 'Applications')
@section('page-subtitle', 'Search, filter, export, and manage driver applications')

@section('content')
    {{-- Filters --}}
    <div class="admin-stat-card mb-6">
        <form method="GET" action="{{ route('admin.applications.index') }}" class="grid gap-4 md:grid-cols-2 xl:grid-cols-5">
            <div class="xl:col-span-2">
                <label class="form-label">Search</label>
                <input type="text" name="search" value="{{ $filters['search'] ?? '' }}" placeholder="Name, email, phone, reference..." class="form-input">
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
            <div>
                <label class="form-label">County</label>
                <select name="county" class="form-input">
                    <option value="">All counties</option>
                    @foreach($counties as $county)
                        <option value="{{ $county }}" @selected(($filters['county'] ?? '') === $county)>{{ $county }}</option>
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
            <div class="flex flex-wrap items-end gap-2 md:col-span-2 xl:col-span-5">
                <button type="submit" class="btn-primary w-full sm:w-auto">
                    <i class="fa-solid fa-filter mr-1.5"></i> Apply Filters
                </button>
                <a href="{{ route('admin.applications.index') }}" class="btn-secondary w-full sm:w-auto">Clear</a>
                @permission('applications.export')
                    <a href="{{ route('admin.applications.export', $filters) }}" class="btn-secondary w-full sm:w-auto">
                        <i class="fa-solid fa-file-csv mr-1.5"></i> Export CSV
                    </a>
                @endpermission
            </div>
        </form>
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
            <div class="flex flex-wrap items-end gap-3">
                <div class="min-w-[200px] flex-1">
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

    {{-- Table --}}
    <div class="admin-table-wrap">
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
