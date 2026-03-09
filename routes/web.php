<?php

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

require __DIR__.'/settings.php';
