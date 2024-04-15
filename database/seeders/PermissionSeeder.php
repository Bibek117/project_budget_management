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

            //role related permissions
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

            //records related permissions
            'view-transaction',
            'create-transaction',
            'edit-transaction',
            'delete-transaction',

            //timeline related permissions
            'view-timeline',
            'create-timeline',
            'edit-timeline',
            'delete-timeline',

            //project relalted permissions
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

    
            
        ];

        foreach ($permissions as $permission) {
            Permission::create(['name' => $permission]);
        }
    }
}
