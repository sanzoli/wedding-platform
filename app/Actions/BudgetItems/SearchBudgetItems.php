<?php

namespace App\Actions\BudgetItems;

use App\Models\BudgetItem;
use Illuminate\Database\Eloquent\Builder;

class SearchBudgetItems
{
    public function search(array $parameters)
    {
        return BudgetItem::when(isset($parameters['search']), fn (Builder $query) => $query
            ->whereLike('name', '%' . $parameters['search'] . '%')
        );
    }
}
