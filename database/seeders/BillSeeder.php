<?php

namespace Database\Seeders;

use App\Models\Bill;
use Faker\Factory as Faker;
use Illuminate\Database\Seeder;

class BillSeeder extends Seeder
{
    public function run(): void
    {
        $faker = Faker::create();
        $insuranceProviders = ['BlueCross', 'Aetna', 'Cigna', null];
        $billDescriptions = [
            'Heart surgery consultation', 'Cardiac stress test', 'Knee replacement surgery',
            'Fracture treatment', 'Child checkup', 'Brain scan and consultation',
            'X-ray imaging', 'Chemotherapy session', 'Emergency room visit', 'Endoscopy',
            'Urological consultation', 'MRI scan', 'Physical therapy session',
        ];
        $patients = \App\Models\Patient::all()->pluck('id')->toArray();
        $departments = \App\Models\Department::all()->pluck('id')->toArray();

        for ($i = 0; $i < 5000; $i++) {
            $amount = number_format(floatval($faker->randomFloat(2, 100, 15000)), 2, '.', '');
            $insuranceCoverage = $faker->randomElement($insuranceProviders) ? number_format(floatval($faker->randomFloat(2, 0, $amount * 0.8)), 2, '.', '') : '0.00';
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
    }
}