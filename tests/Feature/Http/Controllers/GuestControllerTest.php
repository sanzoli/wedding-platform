<?php

use App\Models\Guest;
use App\Models\User;
use Inertia\Testing\AssertableInertia as Assert;

beforeEach(function () {
    $this->actingAs(User::factory()->create());
});

test('can list guests', function () {
    Guest::factory()->count(3)->create();

    $this->get(route('guests.index'))
        ->assertInertia(fn (Assert $page) => $page
            ->component('Guests')
            ->has('guestGroups.data', 3)
        );
});

test('can search list guests by name', function () {
    Guest::factory()->count(3)->create();
    Guest::factory()->create(['first_name' => 'John', 'last_name' => 'Doe']);

    $this->get(route('guests.index', ['search' => 'john doe']))
        ->assertInertia(fn (Assert $page) => $page
            ->component('Guests')
            ->has('guestGroups.data', 1)
        );
});

test('can search list guests by mobile', function () {
    Guest::factory()->count(3)->create();
    Guest::factory()->create(['mobile' => '+573005999999']);

    $this->get(route('guests.index', ['search' => '059']))
        ->assertInertia(fn (Assert $page) => $page
            ->component('Guests')
            ->has('guestGroups.data', 1)
        );
});

test('does not list guests when unauthenticated', function () {
    $this->actingAsGuest();
    $this->getJson(route('guests.index'))
        ->assertStatus(401);
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

test('can delete guest', function () {
    $guest = Guest::factory()->create();
    Guest::factory()->count(3)->create();

    $this->delete(route('guests.destroy', $guest))
        ->assertRedirectBackWithoutErrors();

    $this->assertDatabaseMissing('guests', $guest->toArray());
    $this->assertDatabaseCount('guests', 3);
});

test('cannot delete guest with companion', function () {
    $guest = Guest::factory()->create();
    Guest::factory()->companion($guest)->create();

    $this->delete(route('guests.destroy', $guest))
        ->assertRedirectBackWithErrors([
            'guest' => 'It cannot delete guest with companions.',
        ]);

    $this->assertDatabaseCount('guests', 2);
    $this->assertDatabaseHas('guests', [
        'id' => $guest->id,
    ]);
});
