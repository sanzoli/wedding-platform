<?php

namespace App\Actions\Budget;

use App\Models\Budget;

class UpdateBudget
{
    public function update(Budget $budget, array $input): Budget
    {
        $budget->name = $input['name'] ?? $budget->name;
        $budget->draft = array_key_exists('draft', $input) ? $input['draft'] : $budget->draft;

        $budget->save();

        return $budget;
    }
}
