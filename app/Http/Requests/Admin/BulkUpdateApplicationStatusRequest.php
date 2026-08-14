<?php

namespace App\Http\Requests\Admin;

use App\Models\DriverApplication;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class BulkUpdateApplicationStatusRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()?->hasPermission('applications.bulk') ?? false;
    }

    public function rules(): array
    {
        return [
            'application_ids' => ['required', 'array', 'min:1'],
            'application_ids.*' => ['integer', 'exists:driver_applications,id'],
            'status' => ['required', Rule::in(array_keys(DriverApplication::statuses()))],
        ];
    }
}
