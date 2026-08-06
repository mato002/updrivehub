<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreDriverApplicationRequest;
use App\Mail\ApplicantConfirmation;
use App\Mail\HrApplicationNotification;
use App\Models\DriverApplication;
use App\Services\DocumentStorageService;
use App\Services\ReferenceNumberGenerator;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use Illuminate\View\View;

class DriverApplicationController extends Controller
{
    public function create(): View
    {
        $backgrounds = config('recruitment.backgrounds', []);

        return view('applications.create', [
            'vehicleTypes' => config('recruitment.vehicle_types'),
            'licenceClasses' => config('recruitment.licence_classes'),
            'counties' => config('recruitment.kenya_counties'),
            'companyName' => config('recruitment.company_name'),
            'backgroundImage' => $backgrounds ? $backgrounds[array_rand($backgrounds)] : null,
        ]);
    }

    public function store(
        StoreDriverApplicationRequest $request,
        ReferenceNumberGenerator $referenceNumberGenerator,
        DocumentStorageService $documentStorage,
    ): RedirectResponse {
        $application = DB::transaction(function () use ($request, $referenceNumberGenerator, $documentStorage) {
            $paths = [
                'id_front_path' => $documentStorage->store($request->file('id_front'), 'id_front'),
                'id_back_path' => $documentStorage->store($request->file('id_back'), 'id_back'),
                'selfie_path' => $documentStorage->store($request->file('selfie'), 'selfie'),
                'licence_path' => $documentStorage->store($request->file('licence_document'), 'licence'),
            ];

            foreach (['cv', 'good_conduct', 'medical', 'recommendation', 'defensive_driving'] as $field) {
                if ($request->hasFile($field)) {
                    $paths[$field.'_path'] = $documentStorage->store($request->file($field), $field);
                }
            }

            return DriverApplication::query()->create([
                'reference_number' => $referenceNumberGenerator->generate(),
                'full_name' => $request->input('full_name'),
                'national_id' => $request->input('national_id'),
                'date_of_birth' => $request->input('date_of_birth'),
                'gender' => $request->input('gender'),
                'phone' => $request->input('phone'),
                'alternative_phone' => $request->input('alternative_phone'),
                'email' => $request->input('email'),
                'county' => $request->input('county'),
                'town' => $request->input('town'),
                'address' => $request->input('address'),
                'emergency_contact_name' => $request->input('emergency_contact_name'),
                'emergency_contact_phone' => $request->input('emergency_contact_phone'),
                'emergency_contact_relationship' => $request->input('emergency_contact_relationship'),
                'licence_number' => $request->input('licence_number'),
                'licence_class' => $request->input('licence_class'),
                'licence_issue_date' => $request->input('licence_issue_date'),
                'licence_expiry_date' => $request->input('licence_expiry_date'),
                'years_of_experience' => $request->input('years_of_experience'),
                'vehicle_types' => $request->input('vehicle_types'),
                'driving_career' => $request->input('driving_career'),
                'employment_history' => $request->input('employment_history'),
                'digital_signature' => $request->input('digital_signature'),
                'status' => 'submitted',
                ...$paths,
            ]);
        });

        Mail::to(config('recruitment.hr_email'))->send(new HrApplicationNotification($application));
        Mail::to($application->email)->send(new ApplicantConfirmation($application));

        return redirect()
            ->route('applications.success', ['reference' => $application->reference_number])
            ->with('reference_number', $application->reference_number);
    }

    public function success(string $reference): View
    {
        return view('applications.success', [
            'referenceNumber' => $reference,
            'companyName' => config('recruitment.company_name'),
        ]);
    }
}
