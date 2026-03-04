<?php

namespace App\Actions\BudgetItems;

use App\Models\BudgetItem;

class DeleteBudgetItem
{
    public function delete(BudgetItem $item)
    {
        return $item->delete();
    }
}
