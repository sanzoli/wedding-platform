<?php

use App\Http\Controllers\BudgetController;
use App\Http\Controllers\BudgetItemsController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\GuestController;
use App\Http\Controllers\GuestGroupController;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;
use Laravel\Fortify\Features;

Route::get('/', function () {
    return Inertia::render('Welcome', [
        'canRegister' => Features::enabled(Features::registration()),
    ]);
})->name('home');

Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('dashboard', DashboardController::class)->name('dashboard');

    Route::apiResource('guests', GuestController::class)->except('show');

    Route::controller(GuestGroupController::class)
        ->name('guests.group.')
        ->group(function () {
            Route::post('guests/{guest}/group/leave', 'leave')->name('leave');
            Route::post('guests/group/{group}/split', 'split')->name('split');
            Route::put('guests/{guest}/group/{group}/change', 'change')->name('change');
        });

    Route::get('budget', BudgetController::class)->name('budget');

    Route::resource('items', BudgetItemsController::class)
        ->only(['store', 'update', 'destroy']);
});

require __DIR__.'/settings.php';
