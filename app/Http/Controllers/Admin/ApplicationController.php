<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\BulkUpdateApplicationStatusRequest;
use App\Http\Requests\Admin\UpdateApplicationStatusRequest;
use App\Mail\ApplicantStatusUpdate;
use App\Models\DriverApplication;
use App\Services\ActivityLogger;
use App\Services\SettingsService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Illuminate\View\View;
use Symfony\Component\HttpFoundation\StreamedResponse;

class ApplicationController extends Controller
{
    public function index(Request $request): View
    {
        $filters = $request->only(['search', 'status', 'county', 'date_from', 'date_to']);

        $applications = DriverApplication::query()
            ->filtered($filters)
            ->latest()
            ->paginate(15)
            ->withQueryString();

        return view('admin.applications.index', [
            'applications' => $applications,
            'statuses' => DriverApplication::statuses(),
            'counties' => config('recruitment.kenya_counties'),
            'filters' => $filters,
        ]);
    }

    public function show(DriverApplication $application): View
    {
        $application->load(['reviewer', 'activityLogs.user']);

        return view('admin.applications.show', [
            'application' => $application,
            'statuses' => DriverApplication::statuses(),
            'vehicleTypes' => config('recruitment.vehicle_types'),
            'documents' => config('recruitment.document_fields'),
        ]);
    }

    public function updateStatus(
        UpdateApplicationStatusRequest $request,
        DriverApplication $application,
        ActivityLogger $activityLogger,
        SettingsService $settings,
    ): RedirectResponse {
        $previousStatus = $application->status;
        $newStatus = $request->validated('status');

        $application->update([
            'status' => $newStatus,
            'admin_notes' => $request->validated('admin_notes'),
            'reviewed_at' => now(),
            'reviewed_by' => $request->user()->id,
        ]);

        $activityLogger->log(
            $application,
            'status_updated',
            'Status changed from '.(DriverApplication::statuses()[$previousStatus]['label'] ?? $previousStatus).' to '.(DriverApplication::statuses()[$newStatus]['label'] ?? $newStatus),
            $request->user(),
            ['from' => $previousStatus, 'to' => $newStatus],
        );

        if ($previousStatus !== $newStatus && $settings->notifyApplicantOnStatusChange()) {
            try {
                Mail::to($application->email)->send(new ApplicantStatusUpdate($application, $previousStatus));
            } catch (\Throwable $e) {
                Log::warning('Failed to send applicant status email', [
                    'application_id' => $application->id,
                    'error' => $e->getMessage(),
                ]);
            }
        }

        return redirect()
            ->route('admin.applications.show', $application)
            ->with('success', 'Application status updated successfully.');
    }

    public function bulkUpdateStatus(
        BulkUpdateApplicationStatusRequest $request,
        ActivityLogger $activityLogger,
        SettingsService $settings,
    ): RedirectResponse {
        $ids = $request->validated('application_ids');
        $newStatus = $request->validated('status');

        $applications = DriverApplication::query()->whereIn('id', $ids)->get();
        $updated = 0;

        foreach ($applications as $application) {
            $previousStatus = $application->status;

            if ($previousStatus === $newStatus) {
                continue;
            }

            $application->update([
                'status' => $newStatus,
                'reviewed_at' => now(),
                'reviewed_by' => $request->user()->id,
            ]);

            $activityLogger->log(
                $application,
                'status_updated',
                'Bulk status change to '.(DriverApplication::statuses()[$newStatus]['label'] ?? $newStatus),
                $request->user(),
                ['from' => $previousStatus, 'to' => $newStatus, 'bulk' => true],
            );

            if ($settings->notifyApplicantOnStatusChange()) {
                try {
                    Mail::to($application->email)->send(new ApplicantStatusUpdate($application, $previousStatus));
                } catch (\Throwable $e) {
                    Log::warning('Failed to send applicant status email', [
                        'application_id' => $application->id,
                        'error' => $e->getMessage(),
                    ]);
                }
            }

            $updated++;
        }

        return redirect()
            ->route('admin.applications.index', $request->only(['search', 'status', 'county', 'date_from', 'date_to']))
            ->with('success', "{$updated} application(s) updated successfully.");
    }

    public function export(Request $request): StreamedResponse
    {
        $filters = $request->only(['search', 'status', 'county', 'date_from', 'date_to']);
        $filename = 'driver-applications-'.now()->format('Y-m-d-His').'.csv';

        return response()->streamDownload(function () use ($filters) {
            $handle = fopen('php://output', 'w');

            fputcsv($handle, [
                'Reference', 'Full Name', 'Email', 'Phone', 'County', 'Status',
                'Experience (yrs)', 'Licence Class', 'Submitted At', 'Reviewed At',
            ]);

            DriverApplication::query()
                ->filtered($filters)
                ->latest()
                ->chunk(200, function ($applications) use ($handle) {
                    foreach ($applications as $application) {
                        fputcsv($handle, [
                            $application->reference_number,
                            $application->full_name,
                            $application->email,
                            $application->phone,
                            $application->county,
                            $application->statusLabel(),
                            $application->years_of_experience,
                            $application->licence_class,
                            $application->created_at?->toDateTimeString(),
                            $application->reviewed_at?->toDateTimeString(),
                        ]);
                    }
                });

            fclose($handle);
        }, $filename, [
            'Content-Type' => 'text/csv',
        ]);
    }
}
