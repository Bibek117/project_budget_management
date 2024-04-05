<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $superAdmin = User::create([
            'username'=>'Bibek Angde',
            'email'=>'bibekangdembay@gmail.com',
            'password'=>Hash::make('password1234')
        ]);
        $superAdmin->assignRole('Super Admin');

        $admin = User::create([
            'username' => 'admin',
            'email' => 'admin@gmail.com',
            'password' => Hash::make('password1234')
        ]);
        $admin->assignRole(['Admin','Manager']);
    }
}
