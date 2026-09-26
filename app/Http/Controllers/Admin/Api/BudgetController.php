<?php

namespace App\Http\Controllers\Admin\Api;

use App\Actions\Budget\DeleteBudget;
use App\Actions\Budget\StoreBudget;
use App\Actions\Budget\UpdateBudget;
use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\Budget\StoreRequest;
use App\Http\Requests\Admin\Budget\UpdateRequest;
use App\Models\Budget;

class BudgetController extends Controller
{
    public function index()
    {
        return Budget::with('items')->paginate()->toResourceCollection();
    }

    public function store(StoreRequest $request, StoreBudget $action)
    {
        return $action->store($request->validated())->toResource();
    }

    public function show(Budget $budget)
    {
        return $budget->toResource();
    }

    public function update(UpdateRequest $request, Budget $budget, UpdateBudget $action)
    {
        return $action->update($budget, $request->validated())->toResource();
    }

    public function destroy(Budget $budget, DeleteBudget $action)
    {
        $action->delete($budget);

        return response()->noContent();
    }
}
