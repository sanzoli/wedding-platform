<?php

use App\Models\Guest;
use App\Models\User;

beforeEach(function () {
    $this->actingAs(User::factory()->create());
});

test('can update a guest', function () {
    $guest = Guest::factory()->create();
    $data = Guest::factory()->make()->toArray();

    $this->put(route('guests.update', $guest), $data)
        ->assertRedirectBackWithoutErrors();

    $this->assertDatabaseCount('guests', 1);
    $this->assertDatabaseHas('guests', [
        'id' => $guest->id,
        'first_name' => $data['first_name'],
        'last_name' => $data['last_name'],
        'mobile' => $data['mobile'],
        'lang' => $data['lang'],
    ]);
});

test('can update a companion as anonymous', function () {
    $companion = Guest::factory()->companion()->create();

    $this->put(route('guests.update', $companion), [
        'first_name' => '',
        'last_name' => '']
    )->assertRedirectBackWithoutErrors();

    $this->assertDatabaseHas('guests', [
        'id' => $companion->id,
        'first_name' => null,
        'last_name' => null,
    ]);
});

test('cannot update a guest with invalid data', function ($properties, $key, $error) {
    $guest = Guest::factory()->create();
    $data = array_replace([
        'first_name' => 'John',
        'last_name' => 'Doe',
        'lang' => 'en',
        'mobile' => '+573005999999',
    ], $properties);

    $this->put(route('guests.update', $guest), $data)
        ->assertRedirectBackWithErrors([$key => $error]);

    $this->assertDatabaseMissing('guests', $data);
})->with([
    'primary empty name' => [
        ['first_name' => '', 'last_name' => ''],
        'first_name',
        'The first name field is required.',
    ],
    'first name not a string' => [
        ['first_name' => []],
        'first_name',
        'The first name field must be a string.',
    ],
    'last name not a string' => [
        ['last_name' => []],
        'last_name',
        'The last name field must be a string.',
    ],
    'invalid language' => [
        ['lang' => 'invalid-language'],
        'lang',
        'The selected lang is invalid.',
    ],
    'invalid mobile format' => [
        ['mobile' => '(+57)300-599-99'],
        'mobile',
        'The mobile field format is invalid.',
    ],
]);

test('cannot store guest when unauthenticated', function () {
    $guest = Guest::factory()->create();
    $data = [
        'first_name' => 'John',
        'last_name' => 'Doe',
        'lang' => 'en',
        'mobile' => '+573005999999',
    ];

    $this->actingAsGuest();
    $this->put(route('guests.update', $guest), $data)
        ->assertRedirect(route('login'));

    $this->assertDatabaseMissing('guests', $data);
});
