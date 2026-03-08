<?php

namespace App\Actions\Budget\Items;

use App\Models\Budget;
use App\Models\BudgetItem;

class CreateNewBudgetItem
{
    public function store(Budget $budget, array $input): BudgetItem
    {
        $budgetItem = new BudgetItem;

        $budgetItem->name = $input['name'];
        $budgetItem->importance = $input['importance'] ?? null;
        $budgetItem->expected_amount = $input['expected_amount'] ?? null;

        $budgetItem->budget()->associate($budget);
        $budgetItem->save();

        return $budgetItem;
    }
}
