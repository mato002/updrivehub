<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\UpdateApplicationStatusRequest;
use App\Models\DriverApplication;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class ApplicationController extends Controller
{
    public function index(Request $request): View
    {
        $applications = DriverApplication::query()
            ->search($request->input('search'))
            ->status($request->input('status'))
            ->county($request->input('county'))
            ->submittedBetween($request->input('date_from'), $request->input('date_to'))
            ->latest()
            ->paginate(15)
            ->withQueryString();

        return view('admin.applications.index', [
            'applications' => $applications,
            'statuses' => DriverApplication::statuses(),
            'counties' => config('recruitment.kenya_counties'),
            'filters' => $request->only(['search', 'status', 'county', 'date_from', 'date_to']),
        ]);
    }

    public function show(DriverApplication $application): View
    {
        $application->load('reviewer');

        return view('admin.applications.show', [
            'application' => $application,
            'statuses' => DriverApplication::statuses(),
            'vehicleTypes' => config('recruitment.vehicle_types'),
            'documents' => config('recruitment.document_fields'),
        ]);
    }

    public function updateStatus(UpdateApplicationStatusRequest $request, DriverApplication $application): RedirectResponse
    {
        $application->update([
            'status' => $request->validated('status'),
            'admin_notes' => $request->validated('admin_notes'),
            'reviewed_at' => now(),
            'reviewed_by' => $request->user()->id,
        ]);

        return redirect()
            ->route('admin.applications.show', $application)
            ->with('success', 'Application status updated successfully.');
    }
}
