<?php

namespace App\Http\Controllers\Api;

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

    public function store(StoreBudgetRequest $request)
    {
        //
    }

    public function show(Budget $budget)
    {
        //
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
