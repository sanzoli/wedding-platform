<?php

namespace App\Actions\Budget;

use App\Models\Budget;
use Illuminate\Support\Facades\Auth;

class StoreBudget
{
    public function store(array $params): Budget
    {
        $budget = new Budget;

        $budget->name = $params['name'];
        $budget->draft = $params['draft'] ?? false;
        $budget->wedding_id = Auth::user()->current_wedding_id;

        $budget->save();

        return $budget;
    }
}
