<?php

use App\Http\Controllers\Api\BudgetController;
use App\Http\Controllers\Api\BudgetItemController;
use App\Http\Controllers\Api\InvitationController;
use Illuminate\Support\Facades\Route;

Route::name('api.')->group(fn () => [
    Route::middleware('auth:sanctum')->group(fn () => [
        Route::apiResource('budgets', BudgetController::class),
        Route::apiResource('budgets.items', BudgetItemController::class)->shallow(),

        Route::apiResource('invitations', InvitationController::class),
    ]),

    Route::post('invitations/{invitation}/accept', [InvitationController::class, 'accept'])
        ->name('invitations.accept'),
]);
