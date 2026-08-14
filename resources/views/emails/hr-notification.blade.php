<x-mail::message>
# New Driver Application Received

A new driver application has been submitted.

**Reference Number:** {{ $application->reference_number }}

## Applicant Details

| | |
|---|---|
| **Name** | {{ $application->full_name }} |
| **Phone** | {{ $application->phone }} |
| **Email** | {{ $application->email }} |
| **ID Number** | {{ $application->national_id }} |
| **Licence Number** | {{ $application->licence_number }} |
| **Years of Experience** | {{ $application->years_of_experience }} |

## Driving Career

{{ $application->driving_career }}

<x-mail::button :url="$adminUrl">
Review Application in Admin Portal
</x-mail::button>

Documents are attached to this email where file size permits. Additional files can be viewed securely in the admin portal.

Thanks,<br>
{{ config('recruitment.company_name') }} Recruitment System
</x-mail::message>
