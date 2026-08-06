<x-mail::message>
# Application Received

Dear Applicant,

Thank you for applying to join the driving team at **{{ $companyName }}**.

Your application has been received successfully.

**Reference Number:** {{ $application->reference_number }}

We appreciate your interest in working with us. Only shortlisted candidates will be contacted regarding the next steps.

Regards,<br>
Recruitment Team<br>
{{ $companyName }}
</x-mail::message>
