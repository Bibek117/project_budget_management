<?php

namespace App\Repostories;

use App\Models\Budget;

class BudgetRepository extends CommonInterfaceRepository
{
    public function __construct(Budget $budget)
    {
        parent::__construct($budget);
    }
}

?>