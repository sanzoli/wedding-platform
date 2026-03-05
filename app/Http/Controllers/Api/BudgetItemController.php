<?php

namespace App\Http\Controllers\Api;

use App\Actions\BudgetItems\DeleteBudgetItem;
use App\Actions\BudgetItems\SearchBudgetItems;
use App\Actions\BudgetItems\StoreBudgetItem;
use App\Actions\BudgetItems\UpdateBudgetItem;
use App\Http\Controllers\Controller;
use App\Http\Requests\StoreBudgetItemRequest;
use App\Http\Requests\UpdateBudgetItemRequest;
use App\Models\Budget;
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

    public function store(StoreBudgetItemRequest $request, Budget $budget, StoreBudgetItem $action)
    {
        return $action->store($budget, $request->validated())->toResource();
    }

    public function show(BudgetItem $item)
    {
        return $item->toResource();
    }

    public function update(UpdateBudgetItemRequest $request, BudgetItem $item, UpdateBudgetItem $action)
    {
        return $action->update($item, $request->validated())->toResource();
    }

    public function destroy(BudgetItem $item, DeleteBudgetItem $action)
    {
        $action->delete($item);

        return response()->noContent();
    }
}
