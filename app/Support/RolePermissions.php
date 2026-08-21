<?php

namespace App\Support;

class RolePermissions
{
    public const ROLES = [
        'super_admin' => 'Super Admin',
        'hr_manager' => 'HR Manager',
        'recruiter' => 'Recruiter',
        'viewer' => 'Viewer',
    ];

    private const PERMISSIONS = [
        'super_admin' => ['*'],
        'hr_manager' => [
            'dashboard.view',
            'applications.view',
            'applications.update',
            'applications.export',
            'applications.bulk',
            'documents.view',
            'users.view',
            'users.manage',
            'settings.view',
            'settings.manage',
            'reports.view',
            'activity.view',
        ],
        'recruiter' => [
            'dashboard.view',
            'applications.view',
            'applications.update',
            'applications.export',
            'applications.bulk',
            'documents.view',
            'reports.view',
            'activity.view',
        ],
        'viewer' => [
            'dashboard.view',
            'applications.view',
            'documents.view',
            'reports.view',
            'activity.view',
        ],
    ];

    public static function roles(): array
    {
        return self::ROLES;
    }

    public static function allows(?string $role, string $permission): bool
    {
        if ($role === null) {
            return false;
        }

        $permissions = self::PERMISSIONS[$role] ?? [];

        if (in_array('*', $permissions, true)) {
            return true;
        }

        return in_array($permission, $permissions, true);
    }

    public static function permissionsFor(?string $role): array
    {
        if ($role === null) {
            return [];
        }

        return self::PERMISSIONS[$role] ?? [];
    }
}
