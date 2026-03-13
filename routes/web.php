<?php

use App\Http\Controllers\Web\BudgetController;
use App\Http\Controllers\Web\BudgetItemController;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;
use Laravel\Fortify\Features;

Route::get('/', function () {
    return Inertia::render('Welcome', [
        'canRegister' => Features::enabled(Features::registration()),
    ]);
})->name('home');

Route::get('/landing', function () {
    return Inertia::render('Landing', [
        'canLogin' => true,
        'canRegister' => Features::enabled(Features::registration()),
    ]);
})->name('landing');

Route::get('/our-wedding', function () {
    return Inertia::render('OurWedding');
})->name('our-wedding');

Route::get('dashboard', function () {
    return Inertia::render('Dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('/budgets', [BudgetController::class, 'index'])->name('budgets.index');
    Route::get('/budgets/{budget}', [BudgetController::class, 'show'])->name('budgets.show');
    Route::post('/budgets', [BudgetController::class, 'store'])->name('budgets.store');

    Route::post('/budgets/{budget}/items', [BudgetItemController::class, 'store'])->name('budgets.items.store');
    Route::patch('/items/{item}', [BudgetItemController::class, 'update'])->name('items.update');
    Route::delete('/items/{item}', [BudgetItemController::class, 'destroy'])->name('items.destroy');
});

require __DIR__.'/settings.php';
