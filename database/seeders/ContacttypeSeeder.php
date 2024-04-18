<?php

namespace Database\Seeders;

use App\Models\Contact;
use App\Models\Contacttype;
use Illuminate\Database\Seeder;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class ContacttypeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $contacttypes = [
            ['name'=>'Donor'],
            ['name'=>'Staff'],
            ['name'=>'Organization'],
            ['name'=>'Supplier'],
            
        ];

        Contacttype::insert($contacttypes);

        $contactAssign = [
            ['user_id'=>5, 'contacttype_id'=>1],
            ['user_id' => 5, 'contacttype_id' => 4],
            ['user_id'=>4,'contacttype_id'=>2]
        ];

        Contact::insert($contactAssign);
    }
}
