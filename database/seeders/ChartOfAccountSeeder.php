<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class ChartOfAccountSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run()
    {
        $categories = [
            'Receivables' => [
                'Grants and Funding',
                'Donations and Contributions',
                'Pledges and Commitments',
                'Membership and Subscriptions',
                'Program Fees and Sales'
            ],
            'Payables' => [
                'Salaries and Compensation',
                'Accounts Payable',
                'Rent and Leases',
                'Utilities and Services',
                'Taxes and Duties'
            ],
            'Bank/Cash' => [
                'Bank Accounts',
                'Cash in Hand'
            ],
            'Expenses' => [
                'Program Costs and Services',
                'Fundraising and Marketing',
                'Administrative and Overhead',
                'Advocacy and Awareness',
                'Grants and Projects'
            ]
        ];


        $parentIds = [];
        foreach ($categories as $parent => $children) {
            $parentId = DB::table('account_categories')->insertGetId([
                'name' => $parent
            ]);


            foreach ($children as $child) {
                DB::table('account_sub_categories')->insert([
                    'name' => $child,
                    'account_category_id' => $parentId
                ]);
            }
        }
    }
}
