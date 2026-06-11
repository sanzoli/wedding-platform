<?php

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
    Route::get('dashboard', function () {
        return Inertia::render('Dashboard');
    })->name('dashboard');

    Route::apiResource('guests', GuestController::class)->except('show');
    Route::post('guests/{guest}/group/leave', [GuestGroupController::class, 'leave'])->name('guests.group.leave');
});

require __DIR__.'/settings.php';
