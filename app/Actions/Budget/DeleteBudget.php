<?php

namespace App\Actions\Budget;

use App\Models\Budget;

class DeleteBudget
{
    public function delete(Budget $budget): ?bool
    {
        return $budget->delete();
    }
}
