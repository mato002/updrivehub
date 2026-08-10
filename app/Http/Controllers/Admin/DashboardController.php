<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\DriverApplication;
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
        ];

        $byStatus = collect($statuses)->mapWithKeys(function (array $meta, string $key) {
            return [$key => DriverApplication::where('status', $key)->count()];
        });

        $recent = DriverApplication::query()
            ->latest()
            ->limit(8)
            ->get();

        return view('admin.dashboard', compact('stats', 'byStatus', 'recent', 'statuses'));
    }
}
