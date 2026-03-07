<?php

namespace App\Http\Controllers\Api;

use App\Actions\Budget\DeleteBudget;
use App\Actions\Budget\StoreBudget;
use App\Actions\Budget\UpdateBudget;
use App\Http\Controllers\Controller;
use App\Http\Requests\StoreBudgetRequest;
use App\Http\Requests\UpdateBudgetRequest;
use App\Models\Budget;
use Illuminate\Support\Facades\Auth;

class BudgetController extends Controller
{
    public function index()
    {
        return Budget::with('items')
            ->where('wedding_id', Auth::user()->current_wedding_id)
            ->paginate()
            ->toResourceCollection();
    }

    public function store(StoreBudgetRequest $request, StoreBudget $action)
    {
        return $action->store($request->validated())->toResource();
    }

    public function show(Budget $budget)
    {
        return $budget->toResource();
    }

    public function update(UpdateBudgetRequest $request, Budget $budget, UpdateBudget $action)
    {
        return $action->update($budget, $request->validated())->toResource();
    }

    public function destroy(Budget $budget, DeleteBudget $action)
    {
        $action->delete($budget);

        return response()->noContent();
    }
}
