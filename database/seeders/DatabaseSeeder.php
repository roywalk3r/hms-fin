<?php

namespace Database\Seeders;

use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call([
            RolesAndPermissionsSeeder::class,
            AdminUserSeeder::class,
        ]);
      Models\User::factory(10)->create();
      Models\Staff::factory(10)->create();
      Models\Patient::factory(10)->create();
      Models\Appointment::factory(10)->create();
      Models\Billing::factory(10)->create();
      Models\MedicalRecord::factory(10)->create();
      Models\Department::factory(10)->create();
    }

}
