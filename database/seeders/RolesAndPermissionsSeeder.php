<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class RolesAndPermissionsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // Clear cached roles and permissions
        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();

        // List of all permissions
        $permissions = [
            'User can add branchAdmin',
            'User can update branchAdmin',
            'User can delete branchAdmin',
            'User can get branchAdmin',
            'User can count branchAdmin',
            'User can add employees',
            'User can add users (employee)',
            'User can get employees',
            'User can manage all users (delete)',
            'User can manage all users (update)',
            'User can delete customers',
            'User can update customers',
            'User can add customers',
            'User can delete products',
            'User can update products',
            'User can add products',
            'User can delete sales',
            'User can update sales',
            'User can add sales',
            'User can delete installments',
            'User can update installments',
            'User can add installments',
            'User can see all installments',
            'User can see customer installments',
            'User can see all sales',
            'User can see customer sales',
            'User can see monthly sales',
            'User can see daily sales',
            'User can see all employee',
            'User can see all employee count',
            'User can get salary invoice',
            'User can see Attendance Records',
            'User can count products',
            'User can get daily sales count',
            'User can update their info',
        ];

        // Create all permissions
        foreach ($permissions as $permission) {
            Permission::firstOrCreate(['name' => $permission]);
        }

        // Super Admin Role
        $superAdmin = Role::firstOrCreate(['name' => 'admin']);
        $superAdmin->syncPermissions($permissions);

        // Branch Admin Role
        $branchAdminPermissions = [
            'User can add employees',
            'User can add users (employee)',
            'User can get employees',
            'User can manage all users (delete)',
            'User can manage all users (update)',
            'User can delete customers',
            'User can update customers',
            'User can add customers',
            'User can delete products',
            'User can update products',
            'User can add products',
            'User can delete sales',
            'User can update sales',
            'User can add sales',
            'User can delete installments',
            'User can update installments',
            'User can add installments',
            'User can see all installments',
            'User can see customer installments',
            'User can see all sales',
            'User can see customer sales',
            'User can see monthly sales',
            'User can see daily sales',
            'User can see all employee',
            'User can see all employee count',
            'User can get salary invoice',
            'User can see Attendance Records',
            'User can count products',
            'User can get daily sales count',
            'User can update their info',
        ];
        $branchAdmin = Role::firstOrCreate(['name' => 'branch admin']);
        $branchAdmin->syncPermissions($branchAdminPermissions);

        // Employee Role
        $employeePermissions = [
            'User can add customers',
            'User can add sales',
            'User can add installments',
        ];
        $employee = Role::firstOrCreate(['name' => 'employee']);
        $employee->syncPermissions($employeePermissions);
    }
}
