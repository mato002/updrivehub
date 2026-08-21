<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\DriverApplication;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;

class DashboardController extends Controller
{
    public function index(): View
    {
        $statuses = DriverApplication::statuses();

        $stats = [
            'total' => DriverApplication::count(),
            'today' => DriverApplication::whereDate('created_at', today())->count(),
            'this_week' => DriverApplication::where('created_at', '>=', now()->startOfWeek())->count(),
            'pending' => DriverApplication::where('status', 'submitted')->count(),
            'avg_review_days' => (int) round(
                DriverApplication::query()
                    ->whereNotNull('reviewed_at')
                    ->get(['created_at', 'reviewed_at'])
                    ->avg(fn ($application) => $application->created_at->diffInDays($application->reviewed_at)) ?? 0
            ),
        ];

        $byStatus = collect($statuses)->mapWithKeys(function (array $meta, string $key) {
            return [$key => DriverApplication::where('status', $key)->count()];
        });

        $recent = DriverApplication::query()
            ->latest()
            ->limit(8)
            ->get();

        $submissionsTrend = DriverApplication::query()
            ->select(DB::raw('DATE(created_at) as date'), DB::raw('COUNT(*) as total'))
            ->where('created_at', '>=', now()->subDays(29)->startOfDay())
            ->groupBy('date')
            ->orderBy('date')
            ->pluck('total', 'date');

        $topCounties = DriverApplication::query()
            ->select('county', DB::raw('COUNT(*) as total'))
            ->groupBy('county')
            ->orderByDesc('total')
            ->limit(5)
            ->pluck('total', 'county');

        $reviewerWorkload = DriverApplication::query()
            ->select('users.name', DB::raw('COUNT(driver_applications.id) as total'))
            ->join('users', 'users.id', '=', 'driver_applications.reviewed_by')
            ->groupBy('users.name')
            ->orderByDesc('total')
            ->limit(5)
            ->pluck('total', 'name');

        return view('admin.dashboard', compact(
            'stats',
            'byStatus',
            'recent',
            'statuses',
            'submissionsTrend',
            'topCounties',
            'reviewerWorkload',
        ));
    }
}
