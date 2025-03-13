<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;
use App\Models\Billing;
use App\Models\Patient;

class BillingFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Billing::class;

    /**
     * Define the model's default state.
     */
    public function definition(): array
    {
        return [
            'patient_id' => Patient::factory(),
            'total_amount' => fake()->randomFloat(2, 0, 999999.99),
            'paid_amount' => fake()->randomFloat(2, 0, 999999.99),
            'due_date' => fake()->date('Y-m-d', 'now',),
            'status' => fake()->randomElement(['pending', 'paid', 'overdue']),
        ];
    }
}
