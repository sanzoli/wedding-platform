<?php

use App\Http\Controllers\Admin\Api\BudgetController;
use App\Http\Controllers\Admin\Api\BudgetItemController;
use Illuminate\Support\Facades\Route;

Route::name('api.')->group(fn () => [
    Route::middleware('auth:sanctum')->group(fn () => [
        Route::apiResource('budgets', BudgetController::class),
        Route::apiResource('budgets.items', BudgetItemController::class)->shallow(),
    ]),
]);
