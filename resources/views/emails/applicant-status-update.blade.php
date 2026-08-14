@component('mail::message')
# Application Status Update

Hello {{ $application->full_name }},

Your driver application (**{{ $application->reference_number }}**) with **{{ $companyName }}** has been updated.

**Previous status:** {{ $previousStatusLabel }}  
**Current status:** **{{ $statusLabel }}**

@if ($application->status === 'shortlisted')
Our team was impressed with your application. We will contact you shortly with next steps.
@elseif ($application->status === 'hired')
Congratulations! We are pleased to move forward with your application.
@elseif ($application->status === 'rejected')
Thank you for your interest. Unfortunately, we will not be proceeding with your application at this time.
@elseif ($application->status === 'under_review')
Your application is now being reviewed by our recruitment team.
@else
We will keep you informed as your application progresses.
@endif

Thanks,<br>
{{ $companyName }} Recruitment Team
@endcomponent
