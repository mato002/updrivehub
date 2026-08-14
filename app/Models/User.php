<?php

namespace App\Models;

use App\Support\RolePermissions;
use Database\Factories\UserFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Attributes\Hidden;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

#[Fillable(['name', 'email', 'password', 'is_admin', 'role', 'is_active'])]
#[Hidden(['password', 'remember_token'])]
class User extends Authenticatable
{
    /** @use HasFactory<UserFactory> */
    use HasFactory, Notifiable;

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
            'is_admin' => 'boolean',
            'is_active' => 'boolean',
        ];
    }

    public function activityLogs(): HasMany
    {
        return $this->hasMany(ApplicationActivityLog::class);
    }

    public function roleLabel(): string
    {
        return RolePermissions::roles()[$this->role] ?? ucfirst(str_replace('_', ' ', (string) $this->role));
    }

    public function hasPermission(string $permission): bool
    {
        if (! $this->is_active) {
            return false;
        }

        if (! $this->is_admin) {
            return false;
        }

        return RolePermissions::allows($this->role, $permission);
    }

    public function canAccessAdmin(): bool
    {
        return $this->is_active && $this->is_admin;
    }
}
