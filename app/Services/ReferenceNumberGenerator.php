<?php

namespace App\Services;

use App\Models\DriverApplication;
use Illuminate\Support\Str;

class ReferenceNumberGenerator
{
    public function generate(): string
    {
        $date = now()->format('Ymd');
        $prefix = "DRV-{$date}-";

        $latest = DriverApplication::query()
            ->where('reference_number', 'like', $prefix.'%')
            ->orderByDesc('reference_number')
            ->value('reference_number');

        $sequence = 1;

        if ($latest) {
            $sequence = (int) Str::afterLast($latest, '-') + 1;
        }

        return $prefix.str_pad((string) $sequence, 6, '0', STR_PAD_LEFT);
    }
}
