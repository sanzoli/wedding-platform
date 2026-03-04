<?php

namespace App\Http\Controllers\Api;

use App\Actions\BudgetItems\DeleteBudgetItem;
use App\Actions\BudgetItems\SearchBudgetItems;
use App\Actions\BudgetItems\StoreBudgetItem;
use App\Actions\BudgetItems\UpdateBudgetItem;
use App\Http\Controllers\Controller;
use App\Http\Requests\StoreBudgetRequest;
use App\Http\Requests\UpdateBudgetItemRequest;
use App\Models\BudgetItem;
use Illuminate\Http\Request;

class BudgetItemController extends Controller
{
    public function index(Request $request, SearchBudgetItems $action)
    {
        return $action->search($request->query())
            ->paginate()
            ->toResourceCollection();
    }

    public function store(StoreBudgetRequest $request, StoreBudgetItem $action)
    {
        return $action->store($request->validated())->toResource();
    }

    public function show(BudgetItem $budgetItem)
    {
        return $budgetItem->toResource();
    }

    public function update(UpdateBudgetItemRequest $request, BudgetItem $budgetItem, UpdateBudgetItem $action)
    {
        return $action->update($budgetItem, $request->validated())->toResource();
    }

    public function destroy(BudgetItem $budgetItem, DeleteBudgetItem $action)
    {
        $action->delete($budgetItem);
        return response()->noContent();
    }
}
