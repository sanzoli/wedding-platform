<?php

namespace App\Http\Controllers\Admin\Api;

use App\Actions\Budget\Items\DeleteBudgetItem;
use App\Actions\Budget\Items\SearchBudgetItems;
use App\Actions\Budget\Items\StoreBudgetItem;
use App\Actions\Budget\Items\UpdateBudgetItem;
use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\Budget\StoreItemRequest;
use App\Http\Requests\Admin\Budget\UpdateItemRequest;
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

    public function store(StoreItemRequest $request, Budget $budget, StoreBudgetItem $action)
    {
        return $action->store($budget, $request->validated())->toResource();
    }

    public function show(BudgetItem $item)
    {
        return $item->toResource();
    }

    public function update(UpdateItemRequest $request, BudgetItem $item, UpdateBudgetItem $action)
    {
        return $action->update($item, $request->validated())->toResource();
    }

    public function destroy(BudgetItem $item, DeleteBudgetItem $action)
    {
        $action->delete($item);

        return response()->noContent();
    }
}
