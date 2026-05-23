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

test('does not list guests when unauthenticated', function () {
    $this->getJson(route('guests.index'))
        ->assertStatus(401);
});
