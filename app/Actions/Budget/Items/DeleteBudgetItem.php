<?php

namespace App\Actions\Budget\Items;

use App\Models\BudgetItem;

class DeleteBudgetItem
{
    public function delete(BudgetItem $item): ?bool
    {
        return $item->delete();
    }
}
