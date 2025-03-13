<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;
use App\Models\Patient;

class PatientFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Patient::class;

    /**
     * Define the model's default state.
     */
    public function definition(): array
    {
        return [
            'name' => fake()->name(),
            'date_of_birth' => fake()->date(),
            'gender' => fake()->word(),
            'address' => fake()->text(),
            'phone' => fake()->phoneNumber(),
            'email' => fake()->safeEmail(),
            'emergency_contact' => fake()->word(),
        ];
    }
}
