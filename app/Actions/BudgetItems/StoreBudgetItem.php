<?php

namespace App\Actions\BudgetItems;

use App\Models\Budget;
use App\Models\BudgetItem;

class StoreBudgetItem
{
    public function store(Budget $budget, array $params): BudgetItem
    {
        $budgetItem = new BudgetItem;

        $budgetItem->name = $params['name'];
        $budgetItem->importance = $params['importance'] ?? null;
        $budgetItem->expected_amount = $params['expected_amount'] ?? null;

        $budgetItem->budget()->associate($budget);
        $budgetItem->save();

        return $budgetItem;
    }
}
