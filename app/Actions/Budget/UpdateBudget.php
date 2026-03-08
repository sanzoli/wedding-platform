<?php

namespace App\Actions\Budget;

use App\Models\Budget;

class UpdateBudget
{
    public function update(Budget $budget, array $params): Budget
    {
        $budget->name = $params['name'] ?? $budget->name;
        $budget->draft = array_key_exists('draft', $params) ? $params['draft'] : $budget->draft;

        $budget->save();

        return $budget;
    }
}
