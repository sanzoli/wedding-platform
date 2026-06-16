<?php

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
    Route::post('guests/{guest}/group/leave', [GuestGroupController::class, 'leave'])->name('guests.group.leave');
    Route::post('guests/group/{group}/split', [GuestGroupController::class, 'split'])->name('guests.group.split');
    Route::put('guests/{guest}/group/{group}/change', [GuestGroupController::class, 'change'])->name('guests.group.change');
});

require __DIR__.'/settings.php';
