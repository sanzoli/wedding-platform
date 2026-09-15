<?php

namespace Tests\Http\Controllers;

use App\Enum\Language;
use App\Models\Guest;
use App\Models\Invitation;
use Inertia\Testing\AssertableInertia as Assert;

test('guest can view save the date', function () {
    $invitation = Invitation::factory()->saveTheDate()->create();
    $guest = $invitation->guest;

    $this->get(route('save-the-date.view', $invitation))
        ->assertSuccessful()
        ->assertInertia(fn (Assert $page) => $page
            ->component('SaveTheDate')
            ->has('currentGuest', fn (Assert $page) => $page
                ->where('id', $guest->id)
                ->etc()
            )->has('guestGroup', 1, fn (Assert $page) => $page
            ->where('id', $guest->id)
            ->etc()
            )->where('lang', 'es')
            ->has('languages', 3)
            ->where('options', [
                'yes' => trans('save_the_date.options.yes'),
                'probably_yes' => trans('save_the_date.options.probably_yes'),
                'probably_no' => trans('save_the_date.options.probably_no'),
                'no' => trans('save_the_date.options.no'),
            ])
        );
});

test('guest can view save the date with invitation default language', function (string $lang) {
    $invitation = Invitation::factory()->saveTheDate()->create(['default_language' => $lang]);
    $guest = $invitation->guest;

    $this->get(route('save-the-date.view', $invitation))
        ->assertSuccessful()
        ->assertInertia(fn (Assert $page) => $page
            ->component('SaveTheDate')
            ->has('currentGuest', fn (Assert $page) => $page
                ->where('id', $guest->id)
                ->etc()
            )->has('guestGroup', 1, fn (Assert $page) => $page
            ->where('id', $guest->id)
            ->etc()
            )->where('lang', $lang)
            ->has('languages', 3)
            ->where('options', [
                'yes' => trans('save_the_date.options.yes', locale: $lang),
                'probably_yes' => trans('save_the_date.options.probably_yes', locale: $lang),
                'probably_no' => trans('save_the_date.options.probably_no', locale: $lang),
                'no' => trans('save_the_date.options.no', locale: $lang),
            ])
        );
})->with([
    Language::English->name => ['lang' => Language::English->value],
    Language::Spanish->name => ['lang' => Language::Spanish->value],
    Language::Portuguese->name => ['lang' => Language::Portuguese->value],
]);

test('guest can view group guests', function () {
    $invitation = Invitation::factory()->saveTheDate()->create();
    $guest = $invitation->guest;
    $companion = Guest::factory()->companion($guest)->create();

    $this->get(route('save-the-date.view', $invitation))
        ->assertSuccessful()
        ->assertInertia(fn (Assert $page) => $page
            ->component('SaveTheDate')
            ->has('guestGroup', 2)
            ->has('guestGroup.0', fn (Assert $page) => $page
                ->where('id', $guest->id)
                ->etc()
            )->has('guestGroup.1', fn (Assert $page) => $page
            ->where('id', $companion->id)
            ->etc()
            )->etc()
        );
});

test('guest cannot see outside group', function () {
    $invitation = Invitation::factory()->saveTheDate()->create();
    $anotherGuest = Guest::factory()->create();

    $this->get(route('save-the-date.view', $invitation))
        ->assertSuccessful()
        ->assertInertia(fn (Assert $page) => $page
            ->component('SaveTheDate')
            ->has('guestGroup', 1)
            ->has('guestGroup.0', fn (Assert $page) => $page
                ->whereNot('id', $anotherGuest->id)
                ->etc()
            )->etc()
        );
});

test('cannot see save the date with wedding invitation', function () {
    $invitation = Invitation::factory()->wedding()->create();

    $this->get(route('save-the-date.view', $invitation))
        ->assertForbidden();
});
