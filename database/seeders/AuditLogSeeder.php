<?php

namespace Database\Seeders;

use App\Models\Bill;
use App\Models\Department;
use App\Models\Employee;
use App\Models\Patient;
use App\Models\Appointment;
use App\Models\Inventory;
use App\Models\AuditLog;
use App\Models\User;
use Faker\Factory as Faker;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;

class AuditLogSeeder extends Seeder
{
    public function run(): void
    {
        $faker = Faker::create();

        // Create or use existing admin users for audit logs
        $adminUsers = User::whereHas('roles', function ($query) {
            $query->where('name', 'Admin')->where('guard_name', 'web');
        })->pluck('id')->toArray();

        if (count($adminUsers) < 5) {
            // Create additional admin users if needed
            for ($i = count($adminUsers); $i < 5; $i++) {
                $user = User::create([
                    'name' => $faker->name,
                    'email' => $faker->unique()->safeEmail,
                    'password' => bcrypt('password'),
                ]);
                $user->assignRole(Role::where('name', 'Admin')->where('guard_name', 'web')->first());
                $adminUsers[] = $user->id;
            }
        }

        // Merge with existing user IDs
        $userIds = array_unique(array_merge($adminUsers, User::all()->pluck('id')->toArray()));

        // Models and actions for audit logs
        $models = [
            'App\Models\Patient' => Patient::all()->pluck('id')->toArray(),
            'App\Models\Employee' => Employee::all()->pluck('id')->toArray(),
            'App\Models\Bill' => Bill::all()->pluck('id')->toArray(),
            'App\Models\Inventory' => Inventory::all()->pluck('id')->toArray(),
            'App\Models\Appointment' => Appointment::all()->pluck('id')->toArray(),
        ];
        $actions = ['created', 'updated', 'deleted'];

        // Generate 10,000 audit logs
        for ($i = 0; $i < 1000; $i++) {
            $modelType = $faker->randomElement(array_keys($models));
            $modelIds = $models[$modelType];
            if (empty($modelIds)) {
                continue; // Skip if no records exist for the model
            }
            AuditLog::create([
                'user_id' => $faker->randomElement($userIds),
                'action' => $faker->randomElement($actions),
                'model_type' => $modelType,
                'model_id' => $faker->randomElement($modelIds),
                'description' => $faker->sentence,
                'created_at' => $faker->dateTimeBetween('-1 year', 'now'),
                'updated_at' => now(),
            ]);
        }
    }
}