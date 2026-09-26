<?php

use App\Http\Controllers\Guest\SaveTheDateController;

Route::controller(SaveTheDateController::class)->group(function () {
    Route::get('save-the-date/{invitation}', 'view')->name('save-the-date.view');
    Route::post('save-the-date/{invitation}/response', 'response')->name('save-the-date.response');
});
