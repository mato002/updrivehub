<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\StoreUserRequest;
use App\Http\Requests\Admin\UpdateUserRequest;
use App\Models\User;
use App\Support\RolePermissions;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class UserController extends Controller
{
    public function index(): View
    {
        $users = User::query()->orderBy('name')->paginate(20);

        return view('admin.users.index', [
            'users' => $users,
            'roles' => RolePermissions::roles(),
        ]);
    }

    public function create(): View
    {
        return view('admin.users.create', [
            'roles' => RolePermissions::roles(),
        ]);
    }

    public function store(StoreUserRequest $request): RedirectResponse
    {
        $data = $this->normalizeUserData($request->validated());
        User::query()->create($data);

        return redirect()
            ->route('admin.users.index')
            ->with('success', 'Team member created successfully.');
    }

    public function edit(User $user): View
    {
        return view('admin.users.edit', [
            'user' => $user,
            'roles' => RolePermissions::roles(),
        ]);
    }

    public function update(UpdateUserRequest $request, User $user): RedirectResponse
    {
        $data = $this->normalizeUserData($request->validated());

        if (empty($data['password'])) {
            unset($data['password']);
        }

        $user->update($data);

        return redirect()
            ->route('admin.users.index')
            ->with('success', 'Team member updated successfully.');
    }

    public function destroy(User $user): RedirectResponse
    {
        if ($user->id === auth()->id()) {
            return back()->withErrors(['user' => 'You cannot deactivate your own account.']);
        }

        $user->update(['is_active' => false]);

        return redirect()
            ->route('admin.users.index')
            ->with('success', 'Team member deactivated successfully.');
    }

    private function normalizeUserData(array $data): array
    {
        $data['is_admin'] = filter_var($data['is_admin'], FILTER_VALIDATE_BOOL);
        $data['is_active'] = filter_var($data['is_active'], FILTER_VALIDATE_BOOL);

        return $data;
    }
}
