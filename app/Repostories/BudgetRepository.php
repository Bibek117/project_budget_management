<?php

namespace App\Repostories;

use App\Models\Budget;

class BudgetRepository extends CommonInterfaceRepository
{
    public function __construct(Budget $budget)
    {
        parent::__construct($budget);
    }

    //override create parent function
    public function create(array $request){
        $timeline_id = $request['timeline_id'];
        $budgetsData = [];

        foreach ($request['budgets'] as $budget) {
            $budgetsData[] = [
                'timeline_id' => $timeline_id,
                'name' => $budget['name'],
                'amount' => $budget['amount'],
            ];
        }

        if (count($budgetsData) == 1) {
            Budget::create($budgetsData[0]);
        } else {
            Budget::insert($budgetsData);
        }
    }
}

?>