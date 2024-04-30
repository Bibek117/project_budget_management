<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;

class RoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Role::create(['name' => 'Super Admin']);
        $admin = Role::create(['name' => 'Admin']);
        $manager = Role::create(['name' => 'Manager']);
        $accountant = Role::create(['name' => 'Accountant']);
        $projectManager = Role::create(['name'=>'Project Manager']);
        $user = Role::create(['name'=>'Employee']);

        $manager->givePermissionTo([
            'register-user',
        ]);

        $user->givePermissionTo([
             'view-budget',
            'view-project',
            'view-timeline',
        ]);

        $projectManager->givePermissionTo([
              'view-transaction',
            'view-user',
            'assign-project-to-user',

            'view-timeline',
            'create-timeline',
            'edit-timeline',
            'delete-timeline',

            'assign-user-to-project',
            'create-project',
            'edit-project',
            'delete-project',
            'view-project',

            'view-budget'
        ]);

        $accountant->givePermissionTo([
            'view-user',
            'assign-contact',
            'view-transaction',
            'create-transaction',
            'edit-transaction',
            'delete-transaction',
            'create-contacttype',
            'edit-contacttype',
            'delete-contacttype',
            'view-project',
        ]);

        $admin->givePermissionTo([
            'view-role',
            'create-role',
            'edit-role',
            'delete-role',
            'assign-role',

            //user related permissions
            'register-user',
            'edit-user',
            'view-user',
            'delete-user',
            'assign-project-to-user',
            'assign-contact',

            //transactions related permissions
            'view-transaction',
            'create-transaction',
            'edit-transaction',
            'delete-transaction',

            //record related permission
            'view-record',
            'create-record',
            'edit-record',
            'delete-record',

            //timeline related permissions
            'view-timeline',
            'create-timeline',
            'edit-timeline',
            'delete-timeline',

            //project related permissions
            'assign-user-to-project',
            'create-project',
            'edit-project',
            'delete-project',
            'view-project',

            //contact type related permissions
            'create-contacttype',
            'edit-contacttype',
            'delete-contacttype',

            //budget related transations
            'create-budget',
            'edit-budget',
            'delete-budget',
            'view-budget'
        ]);
    }
}
