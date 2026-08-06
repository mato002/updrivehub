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

## Uploaded Documents

@foreach($documentLinks as $document)
- [{{ $document['label'] }}]({{ $document['url'] }})
@endforeach

Documents are attached to this email where file size permits. Use the links above for any files not attached.

Thanks,<br>
{{ config('recruitment.company_name') }} Recruitment System
</x-mail::message>
