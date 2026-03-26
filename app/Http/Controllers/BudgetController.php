<?php

namespace App\Http\Controllers;

use App\Enum\Importance;
use App\Models\Budget;
use App\Models\BudgetItem;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;
use Inertia\Inertia;

class BudgetController extends Controller
{
    public function __invoke(Request $request)
    {
        $budget = Budget::first();

        return Inertia::render('Budget', [
            'name' => $budget->name,
            'items' => BudgetItem::query()
                ->where('budget_id', $budget->id)
                ->when($request->input('search'), fn($query) => $query
                    ->where('name', 'like', '%' . $request->input('search') . '%')
                )->when($request->input('sortBy'), fn (Builder $query) => $query
                    ->orderBy($request->input('sortBy'), $request->input('sort'))
                )->latest()
                ->get(),
            'filters' => $request->only('search', 'sortBy', 'sort'),
            'importanceOptions' => Importance::options(),
            'trans.budget' => Arr::except(trans('budget'), 'table'),
            'trans.table' => Inertia::mergeShared('trans.table', trans('budget.table')),
        ]);
    }
}
