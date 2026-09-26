<?php

use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\GuestController;
use App\Http\Controllers\Admin\GuestGroupController;
use App\Http\Controllers\Guest\SaveTheDateController;
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
});

Route::controller(SaveTheDateController::class)->group(function () {
    Route::get('save-the-date/{invitation}', 'view')->name('save-the-date.view');
    Route::post('save-the-date/{invitation}/response', 'response')->name('save-the-date.response');
});

require __DIR__.'/settings.php';
