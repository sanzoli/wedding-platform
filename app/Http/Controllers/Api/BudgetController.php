<?php

namespace App\Http\Controllers\Api;

use App\Actions\Budget\StoreBudget;
use App\Http\Controllers\Controller;
use App\Http\Requests\StoreBudgetRequest;
use App\Http\Requests\UpdateBudgetRequest;
use App\Models\Budget;

class BudgetController extends Controller
{
    public function index()
    {
        return Budget::with('items')->paginate()->toResourceCollection();
    }

    public function store(StoreBudgetRequest $request, StoreBudget $action)
    {
        return $action->store($request->validated())->toResource();
    }

    public function show(Budget $budget)
    {
        return $budget->toResource();
    }

    public function update(UpdateBudgetRequest $request, Budget $budget)
    {
        //
    }

    public function destroy(Budget $budget)
    {
        //
    }
}
