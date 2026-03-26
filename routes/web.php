<?php

use App\Http\Controllers\BudgetController;
use App\Http\Controllers\BudgetItemsController;
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

Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('budget', BudgetController::class)->name('budget');

    Route::resource('items', BudgetItemsController::class)
        ->only(['store', 'update', 'destroy']);
});

require __DIR__.'/settings.php';
