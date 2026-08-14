<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    public function run(): void
    {
        $password = config('recruitment.admin_demo_password');

        $accounts = [
            ['email' => env('ADMIN_EMAIL', 'admin@example.com'), 'role' => 'super_admin'],
            ['email' => 'hr.manager@example.com', 'role' => 'hr_manager'],
            ['email' => 'recruiter@example.com', 'role' => 'recruiter'],
        ];

        foreach (config('recruitment.demo_admin_accounts') as $index => $account) {
            User::query()->updateOrCreate(
                ['email' => $account['email']],
                [
                    'name' => $account['name'],
                    'password' => $password,
                    'is_admin' => true,
                    'is_active' => true,
                    'role' => $accounts[$index]['role'] ?? 'recruiter',
                ],
            );
        }
    }
}
