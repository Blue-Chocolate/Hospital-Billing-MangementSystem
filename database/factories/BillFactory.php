<?php

namespace Database\Factories;

use App\Models\Bill;
use App\Models\Patient;
use App\Models\Department;
use Illuminate\Database\Eloquent\Factories\Factory;

class BillFactory extends Factory
{
    protected $model = Bill::class;

    public function definition(): array
    {
        $patients = Patient::pluck('id')->toArray();
        $departments = Department::pluck('id')->toArray();

        return [
            'patient_id' => $this->faker->randomElement($patients),
            'department_id' => $this->faker->randomElement($departments),
            'amount' => $this->faker->randomFloat(2, 100, 15000),
            'bill_date' => $this->faker->date(),
            'description' => $this->faker->sentence(),
            'insurance_provider' => $this->faker->randomElement(['BlueCross', 'Aetna', 'Cigna', null]),
            'insurance_coverage' => $this->faker->randomFloat(2, 0, 5000),
            'payment_status' => $this->faker->randomElement(['Pending', 'Paid', 'Partially Paid']),
            'is_anomaly' => false, // default
        ];
    }
}
