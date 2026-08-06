@extends('layouts.app')

@section('title', 'Driver Application Form')
@section('hero', true)

@push('head')
@if ($backgroundImage)
<style>
    :root {
        --form-bg-image: url('{{ $backgroundImage }}');
    }
</style>
@endif
@endpush

@php
    $steps = [
        ['label' => 'Personal', 'icon' => 'fa-user'],
        ['label' => 'Driving', 'icon' => 'fa-id-card'],
        ['label' => 'Employment', 'icon' => 'fa-building'],
        ['label' => 'Experience', 'icon' => 'fa-road'],
        ['label' => 'Documents', 'icon' => 'fa-cloud-arrow-up'],
        ['label' => 'Review', 'icon' => 'fa-clipboard-check'],
    ];

    $vehicleIcons = [
        'saloon' => 'fa-car',
        'suv' => 'fa-truck-monster',
        'pickup' => 'fa-truck-pickup',
        'van' => 'fa-shuttle-van',
        'minibus' => 'fa-van-shuttle',
        'bus' => 'fa-bus',
        'truck' => 'fa-truck',
        'trailer' => 'fa-trailer',
        'tanker' => 'fa-gas-pump',
        'construction_equipment' => 'fa-hard-hat',
        'psv_driver' => 'fa-users',
        'long_distance_driver' => 'fa-route',
    ];

    $documentIcons = [
        'id_front' => 'fa-id-card',
        'id_back' => 'fa-id-card-clip',
        'selfie' => 'fa-camera',
        'licence_document' => 'fa-address-card',
        'cv' => 'fa-file-lines',
        'good_conduct' => 'fa-certificate',
        'medical' => 'fa-file-medical',
        'recommendation' => 'fa-envelope-open-text',
        'defensive_driving' => 'fa-shield-halved',
    ];

    $errorSteps = [
        'full_name' => 1, 'national_id' => 1, 'date_of_birth' => 1, 'gender' => 1,
        'phone' => 1, 'alternative_phone' => 1, 'email' => 1, 'county' => 1,
        'town' => 1, 'address' => 1, 'emergency_contact_name' => 1,
        'emergency_contact_phone' => 1, 'emergency_contact_relationship' => 1,
        'licence_number' => 2, 'licence_class' => 2, 'licence_issue_date' => 2,
        'licence_expiry_date' => 2, 'years_of_experience' => 2, 'vehicle_types' => 2,
        'employment_history' => 3, 'driving_career' => 4,
        'id_front' => 5, 'id_back' => 5, 'selfie' => 5, 'licence_document' => 5,
        'cv' => 5, 'good_conduct' => 5, 'medical' => 5, 'recommendation' => 5,
        'defensive_driving' => 5, 'declaration' => 6, 'digital_signature' => 6,
    ];
    $firstErrorStep = 1;
    if ($errors->any()) {
        foreach ($errors->keys() as $key) {
            $baseKey = preg_replace('/\.\d+\./', '.', $key);
            $baseKey = str_replace(['employment_history.', '.company_name', '.position', '.start_date', '.end_date', '.supervisor_name', '.supervisor_phone', '.reason_for_leaving'], 'employment_history', $baseKey);
            if (isset($errorSteps[$baseKey])) {
                $firstErrorStep = min($firstErrorStep, $errorSteps[$baseKey]);
            }
        }
    }
@endphp

@section('content')
    <div id="loading-overlay" class="fixed inset-0 z-50 hidden flex items-center justify-center bg-black/50 backdrop-blur-sm">
        <div class="glass-card px-8 py-6 text-center">
            <i class="fa-solid fa-spinner fa-spin text-3xl text-brand-600"></i>
            <p class="mt-4 font-medium text-slate-700">Submitting your application...</p>
        </div>
    </div>

    <section class="relative mx-auto max-w-4xl px-4 py-10 sm:px-6">
        <div class="mb-8 text-center">
            <div class="mx-auto mb-4 flex h-16 w-16 items-center justify-center rounded-2xl bg-white/20 text-white shadow-lg backdrop-blur-sm">
                <i class="fa-solid fa-truck-fast text-2xl"></i>
            </div>
            <h1 class="text-3xl font-bold text-white drop-shadow-sm sm:text-4xl">Driver Application Form</h1>
            <p class="mt-2 text-brand-100">Complete all steps to submit your application to {{ $companyName }}.</p>
        </div>

        @if ($errors->any())
            <div class="mb-6 rounded-xl border border-red-300/50 bg-red-50/95 px-4 py-3 text-sm text-red-700 shadow-lg backdrop-blur server-error-step" data-server-error-step="{{ $firstErrorStep }}">
                <p class="flex items-center gap-2 font-semibold"><i class="fa-solid fa-circle-exclamation"></i> Please fix the following errors:</p>
                <ul class="mt-2 list-inside list-disc">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        {{-- Progress --}}
        <div class="mb-8 glass-card !p-5">
            <p id="step-label" class="mb-3 flex items-center justify-center gap-2 text-sm font-medium text-slate-600">
                <i class="fa-solid fa-list-check text-brand-500"></i>
                Step 1 of 6 — Personal Details
            </p>
            <div class="h-2 overflow-hidden rounded-full bg-slate-200/80">
                <div id="progress-bar" class="h-full rounded-full bg-gradient-to-r from-brand-500 to-brand-700 transition-all duration-300" style="width: 16.67%"></div>
            </div>
            <div class="mt-4 grid grid-cols-3 gap-2 sm:grid-cols-6">
                @foreach($steps as $i => $step)
                    <div data-step="{{ $i + 1 }}" class="step-pill bg-slate-100 text-slate-500">
                        <i class="fa-solid {{ $step['icon'] }}"></i>
                        <span>{{ $step['label'] }}</span>
                    </div>
                @endforeach
            </div>
        </div>

        <form id="driver-application-form" action="{{ route('applications.store') }}" method="POST" enctype="multipart/form-data" class="glass-card !p-0 overflow-hidden">
            @csrf

            {{-- Step 1 --}}
            <div data-step-panel="1" class="space-y-5 p-6 sm:p-8">
                <h2 class="section-heading"><i class="fa-solid fa-user"></i> Personal Information</h2>
                <div class="grid gap-5 sm:grid-cols-2">
                    <x-form.input icon="fa-user" label="Full Name" name="full_name" :value="old('full_name')" required :span="2" class="@error('full_name') border-red-500 @enderror" />
                    <x-form.input icon="fa-id-card" label="National ID Number" name="national_id" :value="old('national_id')" required class="@error('national_id') border-red-500 @enderror" />
                    <x-form.input icon="fa-cake-candles" label="Date of Birth" name="date_of_birth" type="date" :value="old('date_of_birth')" required class="@error('date_of_birth') border-red-500 @enderror" />
                    <x-form.select icon="fa-venus-mars" label="Gender" name="gender" required class="@error('gender') border-red-500 @enderror">
                        <option value="">Select gender</option>
                        @foreach(['male' => 'Male', 'female' => 'Female', 'other' => 'Other'] as $val => $label)
                            <option value="{{ $val }}" @selected(old('gender') === $val)>{{ $label }}</option>
                        @endforeach
                    </x-form.select>
                    <x-form.input icon="fa-phone" label="Phone Number" name="phone" type="tel" :value="old('phone')" required placeholder="+254..." class="@error('phone') border-red-500 @enderror" />
                    <x-form.input icon="fa-phone-volume" label="Alternative Phone" name="alternative_phone" type="tel" :value="old('alternative_phone')" />
                    <x-form.input icon="fa-envelope" label="Email Address" name="email" type="email" :value="old('email')" required :span="2" class="@error('email') border-red-500 @enderror" />
                    <x-form.select icon="fa-map-location-dot" label="County" name="county" required class="@error('county') border-red-500 @enderror">
                        <option value="">Select county</option>
                        @foreach($counties as $county)
                            <option value="{{ $county }}" @selected(old('county') === $county)>{{ $county }}</option>
                        @endforeach
                    </x-form.select>
                    <x-form.input icon="fa-city" label="Town" name="town" :value="old('town')" required class="@error('town') border-red-500 @enderror" />
                    <x-form.textarea icon="fa-house" label="Physical Address" name="address" :rows="2" required class="@error('address') border-red-500 @enderror">{{ old('address') }}</x-form.textarea>
                    <x-form.input icon="fa-user-shield" label="Emergency Contact Name" name="emergency_contact_name" :value="old('emergency_contact_name')" required />
                    <x-form.input icon="fa-phone-flip" label="Emergency Contact Phone" name="emergency_contact_phone" type="tel" :value="old('emergency_contact_phone')" required />
                    <x-form.input icon="fa-heart" label="Relationship" name="emergency_contact_relationship" :value="old('emergency_contact_relationship')" required placeholder="e.g. Spouse, Parent, Sibling" :span="2" />
                </div>
            </div>

            {{-- Step 2 --}}
            <div data-step-panel="2" class="hidden space-y-5 p-6 sm:p-8">
                <h2 class="section-heading"><i class="fa-solid fa-id-card"></i> Driving Information</h2>
                <div class="grid gap-5 sm:grid-cols-2">
                    <x-form.input icon="fa-address-card" label="Driving Licence Number" name="licence_number" :value="old('licence_number')" required />
                    <x-form.select icon="fa-layer-group" label="Licence Class" name="licence_class" required>
                        <option value="">Select class</option>
                        @foreach($licenceClasses as $class)
                            <option value="{{ $class }}" @selected(old('licence_class') === $class)>Class {{ $class }}</option>
                        @endforeach
                    </x-form.select>
                    <x-form.input icon="fa-calendar-plus" label="Issue Date" name="licence_issue_date" type="date" :value="old('licence_issue_date')" required />
                    <x-form.input icon="fa-calendar-xmark" label="Expiry Date" name="licence_expiry_date" type="date" :value="old('licence_expiry_date')" required />
                    <x-form.input icon="fa-gauge-high" label="Years of Experience" name="years_of_experience" type="number" :value="old('years_of_experience')" required min="0" max="60" />
                </div>
                <div>
                    <label class="form-label"><i class="fa-solid fa-truck mr-1.5 text-brand-500"></i>Vehicle Types Driven *</label>
                    <div class="mt-2 grid grid-cols-2 gap-3 sm:grid-cols-3">
                        @foreach($vehicleTypes as $key => $label)
                            <label class="flex items-center gap-2 rounded-xl border border-slate-200 bg-white/60 px-3 py-2.5 text-sm transition hover:border-brand-300 hover:bg-brand-50/50">
                                <input type="checkbox" name="vehicle_types[]" value="{{ $key }}" @checked(in_array($key, old('vehicle_types', []))) class="rounded border-slate-300 text-brand-600 focus:ring-brand-500">
                                <i class="fa-solid {{ $vehicleIcons[$key] ?? 'fa-car' }} text-brand-400"></i>
                                {{ $label }}
                            </label>
                        @endforeach
                    </div>
                    <p id="vehicle-types-error" class="form-error hidden"></p>
                    @error('vehicle_types')<p class="form-error">{{ $message }}</p>@enderror
                </div>
            </div>

            {{-- Step 3 --}}
            <div data-step-panel="3" class="hidden space-y-5 p-6 sm:p-8">
                <div class="flex items-center justify-between">
                    <h2 class="section-heading"><i class="fa-solid fa-building"></i> Employment History</h2>
                    <button type="button" id="add-employer" class="inline-flex items-center gap-1.5 text-sm font-semibold text-brand-600 hover:text-brand-700">
                        <i class="fa-solid fa-plus"></i> Add Another Employer
                    </button>
                </div>
                <div id="employers-container" class="space-y-6">
                    @php $oldEmployers = old('employment_history', [['company_name' => '']]); @endphp
                    @foreach($oldEmployers as $index => $employer)
                        <div class="employer-entry rounded-xl border border-slate-200 bg-white/50 p-5">
                            <div class="mb-4 flex items-center justify-between">
                                <h3 class="flex items-center gap-2 font-semibold text-slate-800">
                                    <i class="fa-solid fa-briefcase text-brand-500"></i>
                                    Employer <span class="employer-number">{{ $index + 1 }}</span>
                                </h3>
                                <button type="button" class="remove-employer inline-flex items-center gap-1 text-sm text-red-600 hover:text-red-700 {{ $index === 0 ? 'hidden' : '' }}">
                                    <i class="fa-solid fa-trash-can"></i> Remove
                                </button>
                            </div>
                            <div class="grid gap-4 sm:grid-cols-2">
                                <div class="sm:col-span-2">
                                    <label class="form-label"><i class="fa-solid fa-building mr-1.5 text-brand-500"></i>Company Name *</label>
                                    <input type="text" name="employment_history[{{ $index }}][company_name]" value="{{ $employer['company_name'] ?? '' }}" required class="form-input">
                                </div>
                                <div>
                                    <label class="form-label"><i class="fa-solid fa-user-tie mr-1.5 text-brand-500"></i>Position *</label>
                                    <input type="text" name="employment_history[{{ $index }}][position]" value="{{ $employer['position'] ?? '' }}" required class="form-input">
                                </div>
                                <div>
                                    <label class="form-label"><i class="fa-solid fa-calendar mr-1.5 text-brand-500"></i>Start Date *</label>
                                    <input type="date" name="employment_history[{{ $index }}][start_date]" value="{{ $employer['start_date'] ?? '' }}" required class="form-input">
                                </div>
                                <div>
                                    <label class="form-label"><i class="fa-solid fa-calendar-check mr-1.5 text-brand-500"></i>End Date</label>
                                    <input type="date" name="employment_history[{{ $index }}][end_date]" value="{{ $employer['end_date'] ?? '' }}" class="form-input">
                                </div>
                                <div>
                                    <label class="form-label"><i class="fa-solid fa-user mr-1.5 text-brand-500"></i>Supervisor Name *</label>
                                    <input type="text" name="employment_history[{{ $index }}][supervisor_name]" value="{{ $employer['supervisor_name'] ?? '' }}" required class="form-input">
                                </div>
                                <div>
                                    <label class="form-label"><i class="fa-solid fa-phone mr-1.5 text-brand-500"></i>Supervisor Phone *</label>
                                    <input type="tel" name="employment_history[{{ $index }}][supervisor_phone]" value="{{ $employer['supervisor_phone'] ?? '' }}" required class="form-input">
                                </div>
                                <div class="sm:col-span-2">
                                    <label class="form-label"><i class="fa-solid fa-door-open mr-1.5 text-brand-500"></i>Reason for Leaving *</label>
                                    <textarea name="employment_history[{{ $index }}][reason_for_leaving]" rows="2" required class="form-input">{{ $employer['reason_for_leaving'] ?? '' }}</textarea>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
                @error('employment_history')<p class="form-error">{{ $message }}</p>@enderror
            </div>

            {{-- Step 4 --}}
            <div data-step-panel="4" class="hidden space-y-5 p-6 sm:p-8">
                <h2 class="section-heading"><i class="fa-solid fa-road"></i> Driving Career</h2>
                <div>
                    <label for="driving_career" class="form-label"><i class="fa-solid fa-pen-fancy mr-1.5 text-brand-500"></i>Tell us about your driving career *</label>
                    <p class="mb-3 text-sm text-slate-500">Include previous employers, types of vehicles, routes covered, driving achievements, special skills, awards, languages spoken, and customer service experience.</p>
                    <textarea name="driving_career" id="driving_career" rows="10" required maxlength="5000" class="form-input @error('driving_career') border-red-500 @enderror" placeholder="Describe your driving career in detail...">{{ old('driving_career') }}</textarea>
                    <p id="career-counter" class="mt-2 text-xs text-slate-500"><i class="fa-solid fa-text-width mr-1"></i>0 / 5000 characters (minimum 50)</p>
                    @error('driving_career')<p class="form-error">{{ $message }}</p>@enderror
                </div>
            </div>

            {{-- Step 5 --}}
            <div data-step-panel="5" class="hidden space-y-6 p-6 sm:p-8">
                <h2 class="section-heading"><i class="fa-solid fa-cloud-arrow-up"></i> Upload Documents</h2>
                <p class="flex items-center gap-2 text-sm text-slate-500">
                    <i class="fa-solid fa-circle-info text-brand-400"></i>
                    Accepted formats: PDF, JPG, JPEG, PNG. Maximum 5 MB per file.
                </p>

                @php
                    $documents = [
                        ['name' => 'id_front', 'label' => 'National ID Front', 'required' => true],
                        ['name' => 'id_back', 'label' => 'National ID Back', 'required' => true],
                        ['name' => 'selfie', 'label' => 'Passport Selfie Photo', 'required' => true],
                        ['name' => 'licence_document', 'label' => 'Driving Licence', 'required' => true],
                        ['name' => 'cv', 'label' => 'Curriculum Vitae', 'required' => false],
                        ['name' => 'good_conduct', 'label' => 'Certificate of Good Conduct', 'required' => false],
                        ['name' => 'medical', 'label' => 'Medical Certificate', 'required' => false],
                        ['name' => 'recommendation', 'label' => 'Recommendation Letter', 'required' => false],
                        ['name' => 'defensive_driving', 'label' => 'Defensive Driving Certificate', 'required' => false],
                    ];
                @endphp

                <div class="grid gap-5 sm:grid-cols-2">
                    @foreach($documents as $doc)
                        <div data-file-upload>
                            <label class="form-label">
                                <i class="fa-solid {{ $documentIcons[$doc['name']] ?? 'fa-file' }} mr-1.5 text-brand-500"></i>
                                {{ $doc['label'] }} @if($doc['required']) * @endif
                            </label>
                            <div data-dropzone class="cursor-pointer rounded-xl border-2 border-dashed border-slate-300 bg-white/40 px-4 py-6 text-center transition hover:border-brand-400 hover:bg-brand-50/60">
                                <input type="file" name="{{ $doc['name'] }}" class="hidden" accept=".pdf,.jpg,.jpeg,.png" @if($doc['required']) required @endif>
                                <i class="fa-solid fa-cloud-arrow-up text-2xl text-brand-400"></i>
                                <p class="mt-2 text-sm text-slate-500">Drag & drop or click to upload</p>
                                <p data-filename class="mt-1 text-xs font-medium text-brand-600"></p>
                            </div>
                            <div data-progress class="mt-2 hidden">
                                <div class="h-1.5 overflow-hidden rounded-full bg-slate-200">
                                    <div data-progress-bar class="h-full w-0 rounded-full bg-brand-600 transition-all"></div>
                                </div>
                            </div>
                            <div data-preview class="hidden"></div>
                            @error($doc['name'])<p class="form-error">{{ $message }}</p>@enderror
                        </div>
                    @endforeach
                </div>
            </div>

            {{-- Step 6 --}}
            <div data-step-panel="6" class="hidden space-y-5 p-6 sm:p-8">
                <h2 class="section-heading"><i class="fa-solid fa-clipboard-check"></i> Review & Submit</h2>
                <div id="review-content" class="rounded-xl border border-slate-200 bg-white/60 p-5"></div>
                <div class="rounded-xl border border-slate-200 bg-white/60 p-5">
                    <label class="flex items-start gap-3">
                        <input type="checkbox" name="declaration" id="declaration" value="1" @checked(old('declaration')) required class="mt-1 rounded border-slate-300 text-brand-600 focus:ring-brand-500">
                        <span class="text-sm text-slate-700"><i class="fa-solid fa-file-signature mr-1 text-brand-500"></i>I declare that all information provided is true and accurate to the best of my knowledge. *</span>
                    </label>
                    @error('declaration')<p class="form-error">{{ $message }}</p>@enderror
                </div>
                <x-form.input icon="fa-signature" label="Type Full Name as Digital Signature" name="digital_signature" :value="old('digital_signature')" required placeholder="Type your full name exactly as entered above" class="font-serif italic" />
            </div>

            {{-- Navigation --}}
            <div class="sticky bottom-0 flex items-center justify-between border-t border-white/30 bg-white/90 px-6 py-4 backdrop-blur sm:px-8">
                <button type="button" id="prev-step" class="btn-secondary hidden">
                    <i class="fa-solid fa-arrow-left mr-1.5"></i> Previous
                </button>
                <div class="flex-1"></div>
                <button type="button" id="next-step" class="btn-primary">
                    Next <i class="fa-solid fa-arrow-right ml-1.5"></i>
                </button>
                <button type="submit" id="submit-application" class="btn-primary hidden">
                    <i class="fa-solid fa-paper-plane mr-1.5"></i> Submit Application
                </button>
            </div>
        </form>
    </section>

    <template id="employer-template">
        <div class="employer-entry rounded-xl border border-slate-200 bg-white/50 p-5">
            <div class="mb-4 flex items-center justify-between">
                <h3 class="flex items-center gap-2 font-semibold text-slate-800">
                    <i class="fa-solid fa-briefcase text-brand-500"></i>
                    Employer <span class="employer-number">1</span>
                </h3>
                <button type="button" class="remove-employer inline-flex items-center gap-1 text-sm text-red-600 hover:text-red-700">
                    <i class="fa-solid fa-trash-can"></i> Remove
                </button>
            </div>
            <div class="grid gap-4 sm:grid-cols-2">
                <div class="sm:col-span-2">
                    <label class="form-label"><i class="fa-solid fa-building mr-1.5 text-brand-500"></i>Company Name *</label>
                    <input type="text" name="employment_history[__INDEX__][company_name]" required class="form-input">
                </div>
                <div>
                    <label class="form-label"><i class="fa-solid fa-user-tie mr-1.5 text-brand-500"></i>Position *</label>
                    <input type="text" name="employment_history[__INDEX__][position]" required class="form-input">
                </div>
                <div>
                    <label class="form-label"><i class="fa-solid fa-calendar mr-1.5 text-brand-500"></i>Start Date *</label>
                    <input type="date" name="employment_history[__INDEX__][start_date]" required class="form-input">
                </div>
                <div>
                    <label class="form-label"><i class="fa-solid fa-calendar-check mr-1.5 text-brand-500"></i>End Date</label>
                    <input type="date" name="employment_history[__INDEX__][end_date]" class="form-input">
                </div>
                <div>
                    <label class="form-label"><i class="fa-solid fa-user mr-1.5 text-brand-500"></i>Supervisor Name *</label>
                    <input type="text" name="employment_history[__INDEX__][supervisor_name]" required class="form-input">
                </div>
                <div>
                    <label class="form-label"><i class="fa-solid fa-phone mr-1.5 text-brand-500"></i>Supervisor Phone *</label>
                    <input type="tel" name="employment_history[__INDEX__][supervisor_phone]" required class="form-input">
                </div>
                <div class="sm:col-span-2">
                    <label class="form-label"><i class="fa-solid fa-door-open mr-1.5 text-brand-500"></i>Reason for Leaving *</label>
                    <textarea name="employment_history[__INDEX__][reason_for_leaving]" rows="2" required class="form-input"></textarea>
                </div>
            </div>
        </div>
    </template>
@endsection
