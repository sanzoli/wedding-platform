<?php

namespace App\Http\Controllers\Api;

use App\Actions\BudgetItems\SearchBudgetItems;
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

    public function store(StoreBudgetRequest $request)
    {
        //
    }

    public function show(BudgetItem $provider)
    {
        //
    }

    public function update(UpdateBudgetItemRequest $request, BudgetItem $provider)
    {
        //
    }

    public function destroy(BudgetItem $provider)
    {
        //
    }
}
