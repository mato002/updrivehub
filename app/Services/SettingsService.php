<?php

namespace App\Services;

use App\Models\Setting;
use Illuminate\Database\QueryException;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Schema;

class SettingsService
{
    private const CACHE_KEY = 'app.settings.all';

    private const MANAGED_KEYS = [
        'company_name',
        'hr_email',
        'phone',
        'email',
        'address',
        'notify_applicant_on_status_change',
    ];

    public function all(): array
    {
        if (! $this->tableExists()) {
            return $this->defaults();
        }

        return Cache::remember(self::CACHE_KEY, 300, function () {
            try {
                $stored = Setting::query()->pluck('value', 'key')->all();

                return array_merge($this->defaults(), $stored);
            } catch (QueryException) {
                return $this->defaults();
            }
        });
    }

    public function get(string $key, mixed $default = null): mixed
    {
        $all = $this->all();

        return $all[$key] ?? $default;
    }

    public function set(string $key, mixed $value): void
    {
        if (! $this->tableExists()) {
            return;
        }

        Setting::query()->updateOrCreate(
            ['key' => $key],
            ['value' => is_bool($value) ? ($value ? '1' : '0') : (string) $value],
        );

        Cache::forget(self::CACHE_KEY);
    }

    public function setMany(array $values): void
    {
        foreach ($values as $key => $value) {
            if (in_array($key, self::MANAGED_KEYS, true)) {
                $this->set($key, $value);
            }
        }
    }

    public function managedKeys(): array
    {
        return self::MANAGED_KEYS;
    }

    public function defaults(): array
    {
        return [
            'company_name' => config('recruitment.company_name'),
            'hr_email' => config('recruitment.hr_email'),
            'phone' => config('recruitment.phone'),
            'email' => config('recruitment.email'),
            'address' => config('recruitment.address'),
            'notify_applicant_on_status_change' => '1',
        ];
    }

    public function notifyApplicantOnStatusChange(): bool
    {
        return filter_var($this->get('notify_applicant_on_status_change', '1'), FILTER_VALIDATE_BOOL);
    }

    private function tableExists(): bool
    {
        try {
            return Schema::hasTable('settings');
        } catch (QueryException) {
            return false;
        }
    }
}
