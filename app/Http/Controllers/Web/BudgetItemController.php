<?php

namespace App\Http\Controllers\Web;

use App\Actions\Budget\Items\DeleteBudgetItem;
use App\Actions\Budget\Items\StoreBudgetItem;
use App\Actions\Budget\Items\UpdateBudgetItem;
use App\Http\Controllers\Controller;
use App\Http\Requests\StoreBudgetItemRequest;
use App\Http\Requests\UpdateBudgetItemRequest;
use App\Models\Budget;
use App\Models\BudgetItem;
use Illuminate\Http\RedirectResponse;

class BudgetItemController extends Controller
{
    public function store(StoreBudgetItemRequest $request, Budget $budget, StoreBudgetItem $action): RedirectResponse
    {
        $action->store($budget, $request->validated());

        return back();
    }

    public function update(UpdateBudgetItemRequest $request, BudgetItem $item, UpdateBudgetItem $action): RedirectResponse
    {
        $action->update($item, $request->validated());

        return back();
    }

    public function destroy(BudgetItem $item, DeleteBudgetItem $action): RedirectResponse
    {
        $action->delete($item);

        return back();
    }
}
