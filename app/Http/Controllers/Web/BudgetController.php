<?php

namespace App\Http\Controllers\Web;

use App\Actions\Budget\StoreBudget;
use App\Http\Controllers\Controller;
use App\Http\Requests\StoreBudgetRequest;
use App\Models\Budget;
use Illuminate\Http\RedirectResponse;
use Inertia\Inertia;
use Inertia\Response;

class BudgetController extends Controller
{
    public function index(): Response|RedirectResponse
    {
        $budget = Budget::with(['items' => function ($query) {
            $query->orderBy('created_at', 'desc');
        }])->first();

        if ($budget) {
            return to_route('budgets.show', $budget);
        }

        return Inertia::render('Budgets/Index');
    }

    public function show(Budget $budget): Response
    {
        $budget->load(['items' => function ($query) {
            $query->orderBy('created_at', 'desc');
        }]);

        return Inertia::render('Budgets/Show', [
            'budget' => json_decode($budget->toResource()->toJson(), true),
        ]);
    }

    public function store(StoreBudgetRequest $request, StoreBudget $action): RedirectResponse
    {
        $budget = $action->store($request->validated());

        return to_route('budgets.show', $budget);
    }
}
