<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Validator;

class StoreDriverApplicationRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    protected function prepareForValidation(): void
    {
        if ($this->boolean('no_employment_history')) {
            $this->merge(['employment_history' => []]);

            return;
        }

        $history = collect($this->input('employment_history', []))
            ->filter(fn (array $entry) => filled($entry['company_name'] ?? null))
            ->values()
            ->all();

        $this->merge(['employment_history' => $history]);
    }

    public function rules(): array
    {
        $maxKb = config('recruitment.max_upload_size_kb');
        $mimes = config('recruitment.allowed_mimes');
        $vehicleKeys = array_keys(config('recruitment.vehicle_types'));

        return [
            'full_name' => ['required', 'string', 'max:255'],
            'national_id' => ['required', 'string', 'max:50'],
            'date_of_birth' => ['required', 'date', 'before:today', 'before:-18 years'],
            'gender' => ['required', Rule::in(['male', 'female', 'other'])],
            'phone' => ['required', 'string', 'max:20'],
            'alternative_phone' => ['nullable', 'string', 'max:20'],
            'email' => ['required', 'email', 'max:255'],
            'county' => ['required', 'string', 'max:100'],
            'town' => ['required', 'string', 'max:100'],
            'address' => ['required', 'string', 'max:500'],
            'emergency_contact_name' => ['required', 'string', 'max:255'],
            'emergency_contact_phone' => ['required', 'string', 'max:20'],
            'emergency_contact_relationship' => ['required', 'string', 'max:100'],
            'licence_number' => ['required', 'string', 'max:50'],
            'licence_class' => ['required', 'string', 'max:10'],
            'licence_issue_date' => ['required', 'date', 'before_or_equal:today'],
            'licence_expiry_date' => ['required', 'date', 'after:licence_issue_date', 'after:today'],
            'years_of_experience' => ['required', 'integer', 'min:0', 'max:60'],
            'vehicle_types' => ['required', 'array', 'min:1'],
            'vehicle_types.*' => ['string', Rule::in($vehicleKeys)],
            'employment_history' => ['nullable', 'array'],
            'employment_history.*.company_name' => ['required', 'string', 'max:255'],
            'employment_history.*.position' => ['required', 'string', 'max:255'],
            'employment_history.*.start_date' => ['required', 'date'],
            'employment_history.*.end_date' => ['nullable', 'date'],
            'employment_history.*.supervisor_name' => ['required', 'string', 'max:255'],
            'employment_history.*.supervisor_phone' => ['required', 'string', 'max:20'],
            'employment_history.*.reason_for_leaving' => ['required', 'string', 'max:500'],
            'no_employment_history' => ['nullable', 'boolean'],
            'driving_career' => ['required', 'string', 'min:50', 'max:5000'],
            'id_front' => ['required', 'file', 'mimes:'.implode(',', $mimes), 'max:'.$maxKb],
            'id_back' => ['required', 'file', 'mimes:'.implode(',', $mimes), 'max:'.$maxKb],
            'selfie' => ['required', 'file', 'mimes:'.implode(',', $mimes), 'max:'.$maxKb],
            'licence_document' => ['required', 'file', 'mimes:'.implode(',', $mimes), 'max:'.$maxKb],
            'cv' => ['nullable', 'file', 'mimes:'.implode(',', $mimes), 'max:'.$maxKb],
            'good_conduct' => ['nullable', 'file', 'mimes:'.implode(',', $mimes), 'max:'.$maxKb],
            'medical' => ['nullable', 'file', 'mimes:'.implode(',', $mimes), 'max:'.$maxKb],
            'recommendation' => ['nullable', 'file', 'mimes:'.implode(',', $mimes), 'max:'.$maxKb],
            'defensive_driving' => ['nullable', 'file', 'mimes:'.implode(',', $mimes), 'max:'.$maxKb],
            'declaration' => ['accepted'],
            'digital_signature' => ['required', 'string', 'max:255'],
        ];
    }

    public function withValidator(Validator $validator): void
    {
        $validator->after(function (Validator $validator): void {
            if (! $this->filled('digital_signature') || ! $this->filled('full_name')) {
                return;
            }

            if (strcasecmp(trim($this->input('digital_signature')), trim($this->input('full_name'))) !== 0) {
                $validator->errors()->add(
                    'digital_signature',
                    'Your digital signature must exactly match your full name.'
                );
            }
        });
    }

    public function messages(): array
    {
        $maxLabel = $this->maxUploadLabel();

        return [
            'date_of_birth.before' => 'You must be at least 18 years old to apply.',
            'licence_expiry_date.after' => 'Your driving licence must not be expired.',
            'vehicle_types.required' => 'Please select at least one vehicle type you have driven.',
            'vehicle_types.min' => 'Please select at least one vehicle type you have driven.',
            'driving_career.min' => 'Please provide at least 50 characters about your driving career.',
            'declaration.accepted' => 'You must accept the declaration to submit your application.',
            '*.max' => "Each file must not exceed {$maxLabel}.",
            '*.mimes' => 'Only PDF, JPG, JPEG, and PNG files are allowed.',
        ];
    }

    private function maxUploadLabel(): string
    {
        $kb = (int) config('recruitment.max_upload_size_kb');

        if ($kb >= 1048576) {
            $gb = $kb / 1048576;

            return (fmod($gb, 1.0) === 0.0 ? (int) $gb : round($gb, 1)).' GB';
        }

        if ($kb >= 1024) {
            return round($kb / 1024).' MB';
        }

        return $kb.' KB';
    }

    public function attributes(): array
    {
        return [
            'national_id' => 'national ID number',
            'alternative_phone' => 'alternative phone number',
            'emergency_contact_name' => 'emergency contact name',
            'emergency_contact_phone' => 'emergency contact phone',
            'emergency_contact_relationship' => 'emergency contact relationship',
            'licence_number' => 'driving licence number',
            'licence_class' => 'licence class',
            'licence_issue_date' => 'licence issue date',
            'licence_expiry_date' => 'licence expiry date',
            'years_of_experience' => 'years of experience',
            'vehicle_types' => 'vehicle types',
            'driving_career' => 'driving career description',
            'id_front' => 'national ID (front)',
            'id_back' => 'national ID (back)',
            'selfie' => 'passport selfie photo',
            'licence_document' => 'driving licence document',
            'good_conduct' => 'certificate of good conduct',
            'defensive_driving' => 'defensive driving certificate',
            'digital_signature' => 'digital signature',
        ];
    }
}
