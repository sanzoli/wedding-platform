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
        ->assertOk()
        ->assertInertia(fn (Assert $page) => $page
            ->component('Guests')
            ->has('guestGroups.data', 3)
        );
});

test('can search list guests by name', function () {
    Guest::factory()->count(3)->create();
    Guest::factory()->create(['first_name' => 'John', 'last_name' => 'Doe']);

    $this->get(route('guests.index', ['search' => 'john doe']))
        ->assertOk()
        ->assertInertia(fn (Assert $page) => $page
            ->component('Guests')
            ->has('guestGroups.data', 1)
        );
});

test('can search list guests by mobile', function () {
    Guest::factory()->count(3)->create();
    Guest::factory()->create(['mobile' => '+573005999999']);

    $this->get(route('guests.index', ['search' => '059']))
        ->assertOk()
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
