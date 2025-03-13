<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;
use App\Models\Department;
use App\Models\Staff;

class StaffFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Staff::class;

    /**
     * Define the model's default state.
     */
    public function definition(): array
    {
        return [
            'name' => fake()->name(),
            'role' => fake()->word(),
            'department_id' => Department::factory(),
            'email' => fake()->safeEmail(),
            'phone' => fake()->phoneNumber(),
            'hire_date' => fake()->date(),
        ];
    }
}
