<?php

use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\GuestController;
use App\Http\Controllers\Admin\GuestGroupController;
use App\Http\Controllers\Admin\InvitationController;

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

    Route::post('invitations', [InvitationController::class, 'store'])->name('invitations.store');
});
