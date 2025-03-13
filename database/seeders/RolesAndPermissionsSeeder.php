<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class RolesAndPermissionsSeeder extends Seeder
{
    public function run()
    {
        // Reset cached roles and permissions
        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();

        // Create permissions
        $permissions = [
            'view patients',
            'create patients',
            'edit patients',
            'delete patients',
            'view appointments',
            'create appointments',
            'edit appointments',
            'delete appointments',
            'view medical records',
            'create medical records',
            'edit medical records',
            'delete medical records',
            'view billing',
            'create billing',
            'edit billing',
            'delete billing',
            'view staff',
            'create staff',
            'edit staff',
            'delete staff',
            'view departments',
            'create departments',
            'edit departments',
            'delete departments',
        ];

        foreach ($permissions as $permission) {
            Permission::create(['name' => $permission]);
        }

        // Create roles and assign permissions
        $role = Role::create(['name' => 'admin']);
        $role->givePermissionTo(Permission::all());

        $role = Role::create(['name' => 'doctor']);
        $role->givePermissionTo([
            'view patients',
            'edit patients',
            'view appointments',
            'create appointments',
            'edit appointments',
            'view medical records',
            'create medical records',
            'edit medical records',
            'view billing',
        ]);

        $role = Role::create(['name' => 'nurse']);
        $role->givePermissionTo([
            'view patients',
            'view appointments',
            'view medical records',
            'create medical records',
            'edit medical records',
        ]);

        $role = Role::create(['name' => 'receptionist']);
        $role->givePermissionTo([
            'view patients',
            'create patients',
            'edit patients',
            'view appointments',
            'create appointments',
            'edit appointments',
            'view billing',
            'create billing',
        ]);
    }
}
