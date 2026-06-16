<?php

use App\Models\Guest;
use App\Models\User;

beforeEach(function () {
    $this->actingAs(User::factory()->create());
});

test('can create a guest', function () {
    $data = Guest::factory()->make()->toArray();

    $this->post(route('guests.store'), $data)
        ->assertRedirectBackWithoutErrors();

    $this->assertDatabaseCount('guests', 1);
    $this->assertDatabaseHas('guests', [
        'first_name' => $data['first_name'],
        'last_name' => $data['last_name'],
        'mobile' => $data['mobile'],
        'lang' => $data['lang'],
    ]);
});

test('can create an anonymous companion', function () {
    $primary = Guest::factory()->create();

    $this->post(route('guests.store'), [
        'first_name' => '',
        'last_name' => '',
        'group_id' => $primary->id,
    ])->assertRedirectBackWithoutErrors();

    $this->assertDatabaseCount('guests', 2);
    $this->assertDatabaseHas('guests', [
        'first_name' => null,
        'last_name' => null,
        'group_id' => $primary->id,
    ]);
});

test('cannot create a guest with invalid data', function ($properties, $key, $error) {
    $data = array_replace([
        'first_name' => 'John',
        'last_name' => 'Doe',
        'lang' => 'en',
        'mobile' => '+573005999999',
    ], $properties);

    $this->post(route('guests.store'), $data)
        ->assertRedirectBackWithErrors([$key => $error]);

    $this->assertDatabaseCount('guests', 0);
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
