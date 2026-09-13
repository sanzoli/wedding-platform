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

    Route::controller(GuestGroupController::class)
        ->name('guests.group.')
        ->group(function () {
            Route::post('guests/{guest}/group/leave', 'leave')->name('leave');
            Route::post('guests/group/{group}/split', 'split')->name('split');
            Route::put('guests/{guest}/group/{group}/change', 'change')->name('change');
        });
});

require __DIR__.'/settings.php';

// Local-only preview for the unrouted SaveTheDate page. Remove before merging.
Route::middleware('web')->group(function () {
    if (! app()->environment('local')) {
        return;
    }

    Route::get('save-the-date/preview', function () {
        $guests = [
            ['id' => 1, 'first_name' => 'Carlos', 'last_name' => 'Martínez', 'response' => null],
            ['id' => 2, 'first_name' => 'Lauana', 'last_name' => 'Moraes', 'response' => null],
            ['id' => 3, 'first_name' => 'Marta', 'last_name' => 'Martínez', 'response' => null],
        ];

        $lang = request()->query('lang', 'es');

        $toGuest = fn (array $g) => [
            'id' => $g['id'],
            'full_name' => $g['first_name'].' '.$g['last_name'],
            'first_name' => $g['first_name'],
            'last_name' => $g['last_name'],
            'initials' => $g['first_name'][0].$g['last_name'][0],
            'mobile' => '+34600000000',
            'lang' => $lang,
            'flag' => \App\Enum\Language::from($lang)->flag(),
            'group_id' => 1,
            'response' => $g['response'],
        ];

        return Inertia::render('SaveTheDate', [
            'currentGuest' => $toGuest($guests[0]),
            'guestGroup' => array_map($toGuest, $guests),
            'lang' => $lang,
            'languages' => \App\Enum\Language::displayList(),
            'options' => [
                'yes' => 'Sí',
                'probably_yes' => 'Casi seguro, por confirmar',
                'probably_no' => 'Difícil, revisaré si puedo',
                'no' => 'No',
            ],
        ]);
    })->name('save-the-date.preview');

    Route::post('save-the-date/confirm', fn () => back())->name('save-the-date.confirm');
});
