<?php

namespace Database\Seeders;

use App\Models\Bill;
use App\Models\Department;
use App\Models\Employee;
use App\Models\Patient;
use App\Models\Appointment;
use App\Models\Inventory;
use Faker\Factory as Faker;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        $faker = Faker::create();

        // Roles and Permissions
        $this->call(RoleSeeder::class);

        // Users
        $this->call(UserSeeder::class);

        // Departments
        $departments = [
            ['name' => 'Cardiology'],
            ['name' => 'Orthopedics'],
            ['name' => 'Pediatrics'],
            ['name' => 'Neurology'],
            ['name' => 'Radiology'],
            ['name' => 'Oncology'],
            ['name' => 'Emergency Medicine'],
            ['name' => 'Surgery'],
            ['name' => 'Gastroenterology'],
            ['name' => 'Urology'],
        ];
        foreach ($departments as $dept) {
            Department::create($dept);
        }

        // Patients (1000 patients)
        $medicalHistories = [
            'Hypertension', 'Diabetes', 'Asthma', 'Chronic Kidney Disease', 'Heart Disease',
            'Arthritis', 'Cancer', 'COPD', 'Migraine', 'None', 'Fractured Limb', 'Anemia',
            'Thyroid Disorder', 'Obesity', 'Stroke History',
        ];
        for ($i = 0; $i < 100; $i++) {
            Patient::create([
                'name' => $faker->name,
                'email' => $faker->unique()->safeEmail,
                'phone' => $faker->phoneNumber,
                'medical_history' => $faker->randomElement($medicalHistories),
                'last_visit' => $faker->dateTimeBetween('-1 year', 'now')->format('Y-m-d'),
            ]);
        }

        // Employees (100 employees: 50 Doctors, 30 Nurses, 20 Admins)
        $roles = ['Doctor' => 50, 'Nurse' => 30, 'Admin' => 20];
        foreach ($roles as $role => $count) {
            for ($i = 0; $i < $count; $i++) {
                $emp = Employee::create([
                    'name' => $faker->name,
                    'email' => $faker->unique()->safeEmail,
                    'role' => $role,
                    'salary' => $faker->randomFloat(2, $role === 'Doctor' ? 100000 : 50000, $role === 'Doctor' ? 200000 : 100000),
                ]);
                if ($role === 'Doctor') {
                    $emp->assignRole(Role::where('name', 'Doctor')->where('guard_name', 'web')->first());
                } elseif ($role === 'Admin') {
                    $emp->assignRole(Role::where('name', 'Admin')->where('guard_name', 'web')->first());
                }
            }
        }

        // Inventories (500 items)
        $inventoryItems = [
            ['name' => 'Aspirin', 'unit_price' => 0.10, 'low_stock_threshold' => 50],
            ['name' => 'Syringes', 'unit_price' => 0.50, 'low_stock_threshold' => 20],
            ['name' => 'MRI Contrast', 'unit_price' => 100.00, 'low_stock_threshold' => 5],
            ['name' => 'Bandages', 'unit_price' => 0.20, 'low_stock_threshold' => 100],
            ['name' => 'IV Fluids', 'unit_price' => 5.00, 'low_stock_threshold' => 30],
            ['name' => 'Antibiotics', 'unit_price' => 2.50, 'low_stock_threshold' => 40],
            ['name' => 'Surgical Gloves', 'unit_price' => 0.30, 'low_stock_threshold' => 200],
            ['name' => 'Painkillers', 'unit_price' => 1.00, 'low_stock_threshold' => 50],
            ['name' => 'Insulin', 'unit_price' => 25.00, 'low_stock_threshold' => 10],
            ['name' => 'Sterile Gauze', 'unit_price' => 0.15, 'low_stock_threshold' => 150],
        ];
        for ($i = 0; $i < 500; $i++) {
            $item = $faker->randomElement($inventoryItems);
            Inventory::create([
                'name' => $item['name'],
                'quantity' => $faker->numberBetween(10, 1000),
                'unit_price' => $item['unit_price'],
                'low_stock_threshold' => $item['low_stock_threshold'],
            ]);
        }

        // Bills (5000 bills)
        $insuranceProviders = ['BlueCross', 'Aetna', 'Cigna', null];
        $billDescriptions = [
            'Heart surgery consultation', 'Cardiac stress test', 'Knee replacement surgery',
            'Fracture treatment', 'Child checkup', 'Brain scan and consultation',
            'X-ray imaging', 'Chemotherapy session', 'Emergency room visit', 'Endoscopy',
            'Urological consultation', 'MRI scan', 'Physical therapy session',
        ];
        $patients = Patient::all()->pluck('id')->toArray();
        $departments = Department::all()->pluck('id')->toArray();
        for ($i = 0; $i < 500; $i++) {
            $amount = $faker->randomFloat(2, 100, 15000);
            $insuranceCoverage = $faker->randomElement($insuranceProviders) ? $faker->randomFloat(2, 0, $amount * 0.8) : 0;
            Bill::create([
                'patient_id' => $faker->randomElement($patients),
                'department_id' => $faker->randomElement($departments),
                'amount' => $amount,
                'bill_date' => $faker->dateTimeBetween('-1 year', 'now')->format('Y-m-d'),
                'description' => $faker->randomElement($billDescriptions),
                'insurance_provider' => $faker->randomElement($insuranceProviders),
                'insurance_coverage' => $insuranceCoverage,
                'payment_status' => $faker->randomElement(['Pending', 'Paid', 'Partially Paid']),
            ]);
        }

        // Appointments (2000 appointments)
        $employees = Employee::where('role', 'Doctor')->pluck('id')->toArray();
        for ($i = 0; $i < 200; $i++) {
            $isTelemedicine = $faker->boolean(20); // 20% chance of telemedicine
            Appointment::create([
                'patient_id' => $faker->randomElement($patients),
                'employee_id' => $faker->randomElement($employees),
                'appointment_time' => $faker->dateTimeBetween('-6 months', '+1 month')->format('Y-m-d H:i:s'),
                'status' => $faker->randomElement(['Scheduled', 'Completed', 'Cancelled']),
                'notes' => $faker->sentence,
                'is_telemedicine' => $isTelemedicine,
                'telemedicine_url' => $isTelemedicine ? $faker->url : null,
            ]);
        }

        // Call AuditLogSeeder
        $this->call(AuditLogSeeder::class);
    }
}