<?php

use App\Actions\Budget\Items\StoreBudgetItem;
use App\Actions\Budget\StoreBudget;
use App\Http\Requests\StoreBudgetItemRequest;
use App\Http\Requests\StoreBudgetRequest;
use App\Models\Budget;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;
use Laravel\Fortify\Features;

Route::get('/', function () {
    return Inertia::render('Welcome', [
        'canRegister' => Features::enabled(Features::registration()),
    ]);
})->name('home');

Route::get('dashboard', function () {
    return Inertia::render('Dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::get('/budgets', function () {
    return Inertia::render('Budgets/Index');
})->middleware(['auth', 'verified'])->name('budgets.index');

Route::get('/budgets/{budget}', function ($budget) {
    return Inertia::render('Budgets/Show', [
        'budgetId' => $budget
    ]);
})->middleware(['auth', 'verified'])->name('budgets.show');

Route::get('/web/budgets', function () {
    return Budget::with('items')->get()->map->toResource();
})->middleware(['auth', 'verified'])->name('web.budgets.index');

Route::post('/web/budgets', function (StoreBudgetRequest $request, StoreBudget $action) {
    return $action->store($request->validated())->toResource();
})->middleware(['auth', 'verified'])->name('web.budgets.store');

Route::post('/web/budgets/{budget}/items', function (StoreBudgetItemRequest $request, Budget $budget, StoreBudgetItem $action) {
    return $action->store($budget, $request->validated())->toResource();
})->middleware(['auth', 'verified'])->name('web.budgets.items.store');

require __DIR__.'/settings.php';
