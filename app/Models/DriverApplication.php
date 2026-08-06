<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DriverApplication extends Model
{
    protected $fillable = [
        'reference_number',
        'full_name',
        'national_id',
        'date_of_birth',
        'gender',
        'phone',
        'alternative_phone',
        'email',
        'county',
        'town',
        'address',
        'emergency_contact_name',
        'emergency_contact_phone',
        'emergency_contact_relationship',
        'licence_number',
        'licence_class',
        'licence_issue_date',
        'licence_expiry_date',
        'years_of_experience',
        'vehicle_types',
        'driving_career',
        'employment_history',
        'id_front_path',
        'id_back_path',
        'selfie_path',
        'licence_path',
        'cv_path',
        'good_conduct_path',
        'medical_path',
        'recommendation_path',
        'defensive_driving_path',
        'digital_signature',
        'status',
    ];

    protected function casts(): array
    {
        return [
            'date_of_birth' => 'date',
            'licence_issue_date' => 'date',
            'licence_expiry_date' => 'date',
            'vehicle_types' => 'array',
            'employment_history' => 'array',
        ];
    }

    public function documentPaths(): array
    {
        return array_filter([
            'National ID (Front)' => $this->id_front_path,
            'National ID (Back)' => $this->id_back_path,
            'Passport Selfie Photo' => $this->selfie_path,
            'Driving Licence' => $this->licence_path,
            'Curriculum Vitae' => $this->cv_path,
            'Certificate of Good Conduct' => $this->good_conduct_path,
            'Medical Certificate' => $this->medical_path,
            'Recommendation Letter' => $this->recommendation_path,
            'Defensive Driving Certificate' => $this->defensive_driving_path,
        ]);
    }
}
