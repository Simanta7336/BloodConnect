<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class AdminSeeder extends Seeder
{
    /**
     * Sprint 4 — Seed a default admin account.
     * Admin should not be able to register publicly.
     *
     * Run: php artisan db:seed --class=AdminSeeder
     */
    public function run(): void
    {
        User::updateOrCreate(
            ['email' => 'admin@bloodconnect.com'],
            [
                'name' => 'System Admin',
                'email' => 'admin@bloodconnect.com',
                'password' => Hash::make('admin123'),
                'role' => 'admin',
                'email_verified_at' => now(),
            ]
        );
    }
}
