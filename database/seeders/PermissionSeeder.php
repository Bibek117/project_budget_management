<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;

class PermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $permissions = [
            'create-role',
            'edit-role',
            'delete-role',
            'assign-role',
            'register-user',
            'edit-user',
            'delete-user',
            'assign-project-to-user',
            'assign-contact',
            'create-transaction',
            'edit-transaction',
            'delete-transaction',
            'create-timeline',
            'edit-timeline',
            'delete-timeline',
            'assign-user-to-project',
            'create-project',
            'edit-project',
            'delete_project',
            'create-contacttype',
            'edit-contacttype',
            'delete-contacttype',
            'create-budget',
            'edit-budget',
            'delete_budget'
        ];

        foreach ($permissions as $permission) {
            Permission::create(['name' => $permission]);
        }
    }
}
