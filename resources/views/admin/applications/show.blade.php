@extends('layouts.admin')

@section('title', $application->reference_number)
@section('page-title', $application->full_name)
@section('page-subtitle', $application->reference_number)

@section('content')
    <div class="mb-6 flex flex-wrap items-center gap-3">
        <x-admin.status-badge :status="$application->status" />
        <span class="text-sm text-slate-500">
            <i class="fa-regular fa-calendar mr-1"></i>
            Submitted {{ $application->created_at->format('M j, Y \a\t g:i A') }}
        </span>
        @if($application->reviewed_at)
            <span class="text-sm text-slate-500">
                <i class="fa-solid fa-user-check mr-1"></i>
                Reviewed {{ $application->reviewed_at->format('M j, Y') }}
                @if($application->reviewer) by {{ $application->reviewer->name }} @endif
            </span>
        @endif
        <a href="{{ route('admin.applications.index') }}" class="ml-auto btn-secondary !py-2 !px-4 text-sm">
            <i class="fa-solid fa-arrow-left mr-1"></i> Back to list
        </a>
    </div>

    <div class="grid gap-6 xl:grid-cols-3">
        <div class="space-y-6 xl:col-span-2">
            {{-- Personal --}}
            <section class="admin-stat-card">
                <h2 class="section-heading mb-4 !text-lg"><i class="fa-solid fa-user"></i> Personal Information</h2>
                <div class="detail-grid">
                    @foreach([
                        'Full Name' => $application->full_name,
                        'National ID' => $application->national_id,
                        'Date of Birth' => $application->date_of_birth?->format('M j, Y'),
                        'Gender' => ucfirst($application->gender),
                        'Phone' => $application->phone,
                        'Alt. Phone' => $application->alternative_phone ?: '—',
                        'Email' => $application->email,
                        'County' => $application->county,
                        'Town' => $application->town,
                    ] as $label => $value)
                        <div class="detail-item">
                            <p class="detail-label">{{ $label }}</p>
                            <p class="detail-value">{{ $value }}</p>
                        </div>
                    @endforeach
                    <div class="detail-item sm:col-span-2">
                        <p class="detail-label">Physical Address</p>
                        <p class="detail-value">{{ $application->address }}</p>
                    </div>
                    <div class="detail-item">
                        <p class="detail-label">Emergency Contact</p>
                        <p class="detail-value">{{ $application->emergency_contact_name }}</p>
                        <p class="mt-1 text-xs text-slate-500">{{ $application->emergency_contact_phone }} · {{ $application->emergency_contact_relationship }}</p>
                    </div>
                    <div class="detail-item">
                        <p class="detail-label">Digital Signature</p>
                        <p class="detail-value font-serif italic">{{ $application->digital_signature }}</p>
                    </div>
                </div>
            </section>

            {{-- Driving --}}
            <section class="admin-stat-card">
                <h2 class="section-heading mb-4 !text-lg"><i class="fa-solid fa-id-card"></i> Driving Information</h2>
                <div class="detail-grid">
                    @foreach([
                        'Licence Number' => $application->licence_number,
                        'Licence Class' => 'Class '.$application->licence_class,
                        'Issue Date' => $application->licence_issue_date?->format('M j, Y'),
                        'Expiry Date' => $application->licence_expiry_date?->format('M j, Y'),
                        'Years of Experience' => $application->years_of_experience.' years',
                    ] as $label => $value)
                        <div class="detail-item">
                            <p class="detail-label">{{ $label }}</p>
                            <p class="detail-value">{{ $value }}</p>
                        </div>
                    @endforeach
                    <div class="detail-item sm:col-span-2">
                        <p class="detail-label">Vehicle Types</p>
                        <div class="mt-2 flex flex-wrap gap-2">
                            @foreach($application->vehicle_types ?? [] as $type)
                                <span class="rounded-full bg-brand-100 px-3 py-1 text-xs font-medium text-brand-800">
                                    {{ $vehicleTypes[$type] ?? $type }}
                                </span>
                            @endforeach
                        </div>
                    </div>
                </div>
            </section>

            {{-- Employment --}}
            <section class="admin-stat-card">
                <h2 class="section-heading mb-4 !text-lg"><i class="fa-solid fa-building"></i> Employment History</h2>
                @if(count($application->employment_history ?? []) > 0)
                    <div class="space-y-4">
                        @foreach($application->employment_history as $index => $job)
                            <div class="rounded-xl border border-slate-200 bg-slate-50 p-4">
                                <p class="font-semibold text-slate-900">{{ $job['company_name'] ?? '—' }}</p>
                                <p class="text-sm text-slate-600">{{ $job['position'] ?? '—' }}</p>
                                <p class="mt-2 text-xs text-slate-500">
                                    {{ $job['start_date'] ?? '?' }} — {{ $job['end_date'] ?? 'Present' }}
                                </p>
                                @if(!empty($job['supervisor_name']))
                                    <p class="mt-2 text-xs text-slate-500">
                                        Supervisor: {{ $job['supervisor_name'] }}
                                        @if(!empty($job['supervisor_phone'])) ({{ $job['supervisor_phone'] }}) @endif
                                    </p>
                                @endif
                                @if(!empty($job['reason_for_leaving']))
                                    <p class="mt-2 text-sm text-slate-600">{{ $job['reason_for_leaving'] }}</p>
                                @endif
                            </div>
                        @endforeach
                    </div>
                @else
                    <p class="text-sm text-slate-500">No employment history provided.</p>
                @endif
            </section>

            {{-- Career --}}
            <section class="admin-stat-card">
                <h2 class="section-heading mb-4 !text-lg"><i class="fa-solid fa-road"></i> Driving Career</h2>
                <p class="whitespace-pre-wrap text-sm leading-relaxed text-slate-700">{{ $application->driving_career }}</p>
            </section>

            {{-- Documents --}}
            <section class="admin-stat-card">
                <h2 class="section-heading mb-4 !text-lg"><i class="fa-solid fa-file-arrow-down"></i> Documents</h2>
                <div class="grid gap-4 sm:grid-cols-2">
                    @foreach($documents as $key => $doc)
                        @php $path = $application->{$doc['path']}; @endphp
                        <div class="rounded-xl border border-slate-200 p-4 {{ $path ? 'bg-white' : 'bg-slate-50 opacity-60' }}">
                            <p class="text-sm font-semibold text-slate-900">{{ $doc['label'] }}</p>
                            @if($path)
                                @php
                                    $isImage = preg_match('/\.(jpe?g|png)$/i', $path);
                                    $viewUrl = route('admin.applications.documents.show', [$application, $key]);
                                    $downloadUrl = route('admin.applications.documents.download', [$application, $key]);
                                @endphp
                                @if($isImage)
                                    <a href="{{ $viewUrl }}" target="_blank" class="mt-3 block overflow-hidden rounded-lg border border-slate-200">
                                        <img src="{{ $viewUrl }}" alt="{{ $doc['label'] }}" class="max-h-36 w-full object-cover">
                                    </a>
                                @else
                                    <p class="mt-2 text-xs text-slate-500"><i class="fa-solid fa-file-pdf text-red-500"></i> PDF document</p>
                                @endif
                                <div class="mt-3 flex gap-2">
                                    <a href="{{ $viewUrl }}" target="_blank" class="text-xs font-medium text-brand-600 hover:text-brand-700">
                                        <i class="fa-solid fa-eye mr-1"></i> View
                                    </a>
                                    <a href="{{ $downloadUrl }}" class="text-xs font-medium text-brand-600 hover:text-brand-700">
                                        <i class="fa-solid fa-download mr-1"></i> Download
                                    </a>
                                </div>
                            @else
                                <p class="mt-2 text-xs text-slate-400">Not uploaded</p>
                            @endif
                        </div>
                    @endforeach
                </div>
            </section>
        </div>

        {{-- Sidebar: Status update --}}
        <div class="space-y-6">
            <section class="admin-stat-card sticky top-24">
                <h2 class="mb-4 flex items-center gap-2 text-base font-bold text-slate-900">
                    <i class="fa-solid fa-clipboard-check text-brand-500"></i>
                    Review Application
                </h2>
                @permission('applications.update')
                <form method="POST" action="{{ route('admin.applications.update-status', $application) }}" class="space-y-4">
                    @csrf
                    @method('PATCH')
                    <div>
                        <label for="status" class="form-label">Status</label>
                        <select name="status" id="status" class="form-input" required>
                            @foreach($statuses as $key => $meta)
                                <option value="{{ $key }}" @selected($application->status === $key)>{{ $meta['label'] }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div>
                        <label for="admin_notes" class="form-label">Internal Notes</label>
                        <textarea name="admin_notes" id="admin_notes" rows="6" class="form-input" placeholder="Notes visible only to admins...">{{ old('admin_notes', $application->admin_notes) }}</textarea>
                    </div>
                    <button type="submit" class="btn-primary w-full">
                        <i class="fa-solid fa-floppy-disk mr-1.5"></i>
                        Save Review
                    </button>
                </form>
                @else
                    <p class="text-sm text-slate-500">You have read-only access to this application.</p>
                @endpermission
            </section>

            <section class="admin-stat-card">
                <h2 class="mb-4 flex items-center gap-2 text-base font-bold text-slate-900">
                    <i class="fa-solid fa-clock-rotate-left text-brand-500"></i>
                    Activity Timeline
                </h2>
                @if($application->activityLogs->isEmpty())
                    <p class="text-sm text-slate-500">No activity recorded yet.</p>
                @else
                    <div class="space-y-4">
                        @foreach($application->activityLogs as $log)
                            <div class="border-l-2 border-brand-200 pl-4">
                                <p class="text-sm font-medium text-slate-900">{{ $log->description }}</p>
                                <p class="mt-1 text-xs text-slate-500">
                                    {{ $log->created_at->format('M j, Y g:i A') }}
                                    @if($log->user) · {{ $log->user->name }} @endif
                                </p>
                            </div>
                        @endforeach
                    </div>
                @endif
            </section>

            <section class="admin-stat-card">
                <h3 class="mb-3 text-sm font-bold uppercase tracking-wide text-slate-500">Quick Info</h3>
                <dl class="space-y-3 text-sm">
                    <div class="flex justify-between gap-4">
                        <dt class="text-slate-500">Reference</dt>
                        <dd class="font-mono text-xs font-medium text-slate-900">{{ $application->reference_number }}</dd>
                    </div>
                    <div class="flex justify-between gap-4">
                        <dt class="text-slate-500">Licence Expiry</dt>
                        <dd class="font-medium {{ $application->licence_expiry_date?->isPast() ? 'text-red-600' : 'text-slate-900' }}">
                            {{ $application->licence_expiry_date?->format('M j, Y') }}
                        </dd>
                    </div>
                    <div class="flex justify-between gap-4">
                        <dt class="text-slate-500">Documents</dt>
                        <dd class="font-medium text-slate-900">
                            {{ collect($documents)->filter(fn ($doc) => filled($application->{$doc['path']}))->count() }}/{{ count($documents) }}
                        </dd>
                    </div>
                </dl>
            </section>
        </div>
    </div>
@endsection
