<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ApplicationActivityLog;
use Illuminate\Http\Request;
use Illuminate\View\View;

class ActivityLogController extends Controller
{
    public function index(Request $request): View
    {
        $filters = $request->only(['search', 'action', 'user_id', 'date_from', 'date_to']);

        $logs = ApplicationActivityLog::query()
            ->with(['user', 'application:id,reference_number,full_name'])
            ->when(filled($filters['search'] ?? null), function ($query) use ($filters) {
                $term = $filters['search'];
                $query->where(function ($q) use ($term) {
                    $q->where('description', 'like', "%{$term}%")
                        ->orWhereHas('application', fn ($app) => $app
                            ->where('reference_number', 'like', "%{$term}%")
                            ->orWhere('full_name', 'like', "%{$term}%"));
                });
            })
            ->when(filled($filters['action'] ?? null), fn ($q) => $q->where('action', $filters['action']))
            ->when(filled($filters['user_id'] ?? null), fn ($q) => $q->where('user_id', $filters['user_id']))
            ->when(filled($filters['date_from'] ?? null), fn ($q) => $q->whereDate('created_at', '>=', $filters['date_from']))
            ->when(filled($filters['date_to'] ?? null), fn ($q) => $q->whereDate('created_at', '<=', $filters['date_to']))
            ->latest()
            ->paginate(20)
            ->withQueryString();

        $actions = ApplicationActivityLog::query()
            ->select('action')
            ->distinct()
            ->orderBy('action')
            ->pluck('action');

        $teamMembers = \App\Models\User::query()
            ->where('is_admin', true)
            ->orderBy('name')
            ->get(['id', 'name']);

        return view('admin.activity.index', compact('logs', 'filters', 'actions', 'teamMembers'));
    }
}
