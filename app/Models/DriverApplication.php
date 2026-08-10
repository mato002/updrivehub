<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Builder;

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
        'admin_notes',
        'reviewed_at',
        'reviewed_by',
    ];

    protected function casts(): array
    {
        return [
            'date_of_birth' => 'date',
            'licence_issue_date' => 'date',
            'licence_expiry_date' => 'date',
            'vehicle_types' => 'array',
            'employment_history' => 'array',
            'reviewed_at' => 'datetime',
        ];
    }

    public function reviewer(): BelongsTo
    {
        return $this->belongsTo(User::class, 'reviewed_by');
    }

    public function scopeSearch(Builder $query, ?string $term): Builder
    {
        if (! filled($term)) {
            return $query;
        }

        return $query->where(function (Builder $q) use ($term) {
            $q->where('reference_number', 'like', "%{$term}%")
                ->orWhere('full_name', 'like', "%{$term}%")
                ->orWhere('email', 'like', "%{$term}%")
                ->orWhere('phone', 'like', "%{$term}%")
                ->orWhere('national_id', 'like', "%{$term}%");
        });
    }

    public function scopeStatus(Builder $query, ?string $status): Builder
    {
        if (! filled($status)) {
            return $query;
        }

        return $query->where('status', $status);
    }

    public function scopeCounty(Builder $query, ?string $county): Builder
    {
        if (! filled($county)) {
            return $query;
        }

        return $query->where('county', $county);
    }

    public function scopeSubmittedBetween(Builder $query, ?string $from, ?string $to): Builder
    {
        if (filled($from)) {
            $query->whereDate('created_at', '>=', $from);
        }

        if (filled($to)) {
            $query->whereDate('created_at', '<=', $to);
        }

        return $query;
    }

    public static function statuses(): array
    {
        return config('recruitment.application_statuses', []);
    }

    public function statusMeta(): array
    {
        return self::statuses()[$this->status] ?? ['label' => ucfirst($this->status), 'color' => 'slate'];
    }

    public function statusLabel(): string
    {
        return $this->statusMeta()['label'];
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

    public function pathForDocument(string $key): ?string
    {
        $field = config("recruitment.document_fields.{$key}.path");

        return $field ? $this->{$field} : null;
    }
}
