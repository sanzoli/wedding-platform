<?php

namespace App\Actions\Budget;

use App\Models\Budget;
use Illuminate\Support\Facades\Auth;

class CreateNewBudget
{
    public function create(array $input): Budget
    {
        $budget = new Budget;

        $budget->name = $input['name'];
        $budget->draft = $input['draft'] ?? false;
        $budget->wedding_id = Auth::user()->current_wedding_id;

        $budget->save();

        return $budget;
    }
}
