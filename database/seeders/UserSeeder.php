<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Role;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        // Get roles with web guard
        $adminRole = Role::where('name', 'Admin')->where('guard_name', 'web')->first();
        $doctorRole = Role::where('name', 'Doctor')->where('guard_name', 'web')->first();
        $billingRole = Role::where('name', 'Billing Staff')->where('guard_name', 'web')->first();

        if (!$adminRole || !$doctorRole || !$billingRole) {
            $this->command->warn("⚠️ Roles not found. Run RoleSeeder first.");
            return;
        }

        // Create 5 Admin users
        for ($i = 1; $i <= 5; $i++) {
            $user = User::firstOrCreate(
                ['email' => "admin{$i}@example.com"],
                [
                    'name' => "Admin User {$i}",
                    'password' => Hash::make('password'),
                ]
            );
            $user->syncRoles([$adminRole]);
        }

        // Create 5 Doctor users
        for ($i = 1; $i <= 5; $i++) {
            $user = User::firstOrCreate(
                ['email' => "doctor{$i}@example.com"],
                [
                    'name' => "Doctor User {$i}",
                    'password' => Hash::make('password'),
                ]
            );
            $user->syncRoles([$doctorRole]);
        }

        // Create 5 Billing Staff users
        for ($i = 1; $i <= 5; $i++) {
            $user = User::firstOrCreate(
                ['email' => "billing{$i}@example.com"],
                [
                    'name' => "Billing User {$i}",
                    'password' => Hash::make('password'),
                ]
            );
            $user->syncRoles([$billingRole]);
        }
    }
}