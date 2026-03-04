<?php

namespace App\Actions\BudgetItems;

use App\Models\BudgetItem;

class UpdateBudgetItem
{
    public function update(BudgetItem $budgetItem, array $params): BudgetItem
    {
        $budgetItem->name = array_key_exists('name', $params) ? $params['name'] : $budgetItem->name;
        $budgetItem->importance = array_key_exists('importance', $params) ? $params['importance'] : $budgetItem->importance;
        $budgetItem->expected_amount = array_key_exists('expected_amount', $params)
            ? $params['expected_amount']
            : $budgetItem->expected_amount;

        $budgetItem->save();

        return $budgetItem;
    }
}
