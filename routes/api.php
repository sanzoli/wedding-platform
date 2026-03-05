<?php

use App\Http\Controllers\Api\BudgetItemController;
use Illuminate\Support\Facades\Route;

Route::name('api.')->group(fn () => [
    Route::middleware('auth:sanctum')->group(fn () => [
        Route::apiResource('budgets.items', BudgetItemController::class)->shallow(),
    ]),
]);
