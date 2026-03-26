<?php

namespace App\Http\Controllers;

use App\Actions\Budget\Items\DeleteBudgetItem;
use App\Actions\Budget\Items\StoreBudgetItem;
use App\Actions\Budget\Items\UpdateBudgetItem;
use App\Http\Requests\StoreBudgetItemRequest;
use App\Http\Requests\UpdateBudgetItemRequest;
use App\Models\Budget;
use App\Models\BudgetItem;

class BudgetItemsController extends Controller
{
    public function store(StoreBudgetItemRequest $request, StoreBudgetItem $action)
    {
        $action->store(Budget::first(), $request->validated());

        return back();
    }

    public function update(UpdateBudgetItemRequest $request, BudgetItem $item, UpdateBudgetItem $action)
    {
        $action->update($item, $request->validated());

        return back();
    }

    public function destroy(BudgetItem $item, DeleteBudgetItem $action)
    {
        $action->delete($item);

        return back();
    }
}
