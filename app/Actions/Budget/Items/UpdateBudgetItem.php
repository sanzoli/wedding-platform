<?php

namespace App\Actions\Budget\Items;

use App\Models\BudgetItem;

class UpdateBudgetItem
{
    public function update(BudgetItem $budgetItem, array $input): BudgetItem
    {
        $budgetItem->name = array_key_exists('name', $input) ? $input['name'] : $budgetItem->name;
        $budgetItem->importance = array_key_exists('importance', $input) ? $input['importance'] : $budgetItem->importance;
        $budgetItem->expected_amount = array_key_exists('expected_amount', $input)
            ? $input['expected_amount']
            : $budgetItem->expected_amount;

        $budgetItem->save();

        return $budgetItem;
    }
}
