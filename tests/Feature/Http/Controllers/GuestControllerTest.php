<?php

use App\Models\Guest;
use App\Models\User;
use Inertia\Testing\AssertableInertia as Assert;

test('can list guests', function () {
    Guest::factory()->count(3)->create();

    $this
        ->actingAs(User::factory()->create())
        ->get(route('guests.index'))
        ->assertInertia(fn (Assert $page) => $page
            ->component('Guests')
            ->has('guests.data', 3)
        );
});

test('can search list guests by name', function () {
    Guest::factory()->count(3)->create();
    Guest::factory()->create(['first_name' => 'John', 'last_name' => 'Doe']);

    $this
        ->actingAs(User::factory()->create())
        ->get(route('guests.index', ['search' => 'john doe']))
        ->assertInertia(fn (Assert $page) => $page
            ->component('Guests')
            ->has('guests.data', 1)
        );
});

test('can search list guests by mobile', function () {
    Guest::factory()->count(3)->create();
    Guest::factory()->create(['mobile' => '+573005999999']);

    $this
        ->actingAs(User::factory()->create())
        ->get(route('guests.index', ['search' => '059']))
        ->assertInertia(fn (Assert $page) => $page
            ->component('Guests')
            ->has('guests.data', 1)
        );
});

test('does not list guests when unauthenticated', function () {
    $this->getJson(route('guests.index'))
        ->assertStatus(401);
});

test('can delete guest', function () {
    $guest = Guest::factory()->create();
    Guest::factory()->count(3)->create();

    $this->actingAs(User::factory()->create())
        ->delete(route('guests.destroy', $guest))
        ->assertRedirectBack();

    $this->assertDatabaseMissing('guests', $guest->toArray());
    $this->assertDatabaseCount('guests', 3);
});
