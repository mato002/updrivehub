<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\DriverApplication;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;

class ReportsController extends Controller
{
    public function index(Request $request): View
    {
        $filters = $request->only(['date_from', 'date_to', 'county', 'status']);
        $dateFrom = $filters['date_from'] ?? now()->subDays(29)->toDateString();
        $dateTo = $filters['date_to'] ?? now()->toDateString();

        $baseQuery = DriverApplication::query()
            ->submittedBetween($dateFrom, $dateTo)
            ->when(filled($filters['county'] ?? null), fn ($q) => $q->where('county', $filters['county']))
            ->when(filled($filters['status'] ?? null), fn ($q) => $q->where('status', $filters['status']));

        $stats = [
            'total' => (clone $baseQuery)->count(),
            'pending' => (clone $baseQuery)->where('status', 'submitted')->count(),
            'shortlisted' => (clone $baseQuery)->where('status', 'shortlisted')->count(),
            'hired' => (clone $baseQuery)->where('status', 'hired')->count(),
            'rejected' => (clone $baseQuery)->where('status', 'rejected')->count(),
        ];

        $statuses = DriverApplication::statuses();
        $byStatus = collect($statuses)->mapWithKeys(function (array $meta, string $key) use ($baseQuery) {
            return [$key => (clone $baseQuery)->where('status', $key)->count()];
        });

        $byCounty = (clone $baseQuery)
            ->select('county', DB::raw('COUNT(*) as total'))
            ->groupBy('county')
            ->orderByDesc('total')
            ->limit(10)
            ->pluck('total', 'county');

        $byExperience = (clone $baseQuery)
            ->get(['years_of_experience'])
            ->groupBy(function ($application) {
                $years = (int) $application->years_of_experience;

                return match (true) {
                    $years < 2 => '0-1 years',
                    $years < 5 => '2-4 years',
                    $years < 10 => '5-9 years',
                    default => '10+ years',
                };
            })
            ->map->count()
            ->sortKeys();

        $dailyTrend = (clone $baseQuery)
            ->select(DB::raw('DATE(created_at) as date'), DB::raw('COUNT(*) as total'))
            ->groupBy('date')
            ->orderBy('date')
            ->pluck('total', 'date');

        return view('admin.reports.index', [
            'stats' => $stats,
            'byStatus' => $byStatus,
            'byCounty' => $byCounty,
            'byExperience' => $byExperience,
            'dailyTrend' => $dailyTrend,
            'statuses' => $statuses,
            'counties' => config('recruitment.kenya_counties'),
            'filters' => array_merge($filters, [
                'date_from' => $dateFrom,
                'date_to' => $dateTo,
            ]),
        ]);
    }
}
