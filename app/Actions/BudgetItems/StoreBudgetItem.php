<?php

namespace App\Actions\BudgetItems;

use App\Enum\Status;
use App\Models\BudgetItem;

class StoreBudgetItem
{
    public function store(array $params): BudgetItem
    {
        $budgetItem = new BudgetItem();

        $budgetItem->name = $params['name'];
        $budgetItem->status = Status::Pending;
        $budgetItem->importance = $params['importance'] ?? null;
        $budgetItem->expected_amount = $params['expected_amount'] ?? null;

        $budgetItem->save();

        return $budgetItem;
    }
}
