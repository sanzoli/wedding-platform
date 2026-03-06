<?php

namespace App\Actions\Budget;

use App\Models\Budget;

class StoreBudget
{
    public function store(array $params): Budget
    {
        $budget = new Budget;

        $budget->name = $params['name'];
        $budget->draft = $params['draft'] ?? false;

        $budget->save();

        return $budget;
    }
}
