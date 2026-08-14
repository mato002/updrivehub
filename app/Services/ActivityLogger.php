<?php

namespace App\Services;

use App\Models\ApplicationActivityLog;
use App\Models\DriverApplication;
use App\Models\User;

class ActivityLogger
{
    public function log(
        DriverApplication $application,
        string $action,
        string $description,
        ?User $user = null,
        ?array $properties = null,
    ): ApplicationActivityLog {
        return ApplicationActivityLog::query()->create([
            'driver_application_id' => $application->id,
            'user_id' => $user?->id,
            'action' => $action,
            'description' => $description,
            'properties' => $properties,
        ]);
    }
}
