<?php

use App\Models\Guest;
use App\Models\User;

beforeEach(function () {
    $this->actingAs(User::factory()->create());
});

test('can delete guest', function () {
    $guest = Guest::factory()->create();
    Guest::factory()->count(3)->create();

    $this->delete(route('guests.destroy', $guest))
        ->assertRedirectBackWithoutErrors();

    $this->assertDatabaseMissing('guests', [
        'id' => $guest->id,
    ]);
    $this->assertDatabaseCount('guests', 3);
});

test('cannot delete guest with companion', function () {
    $guest = Guest::factory()->create();
    Guest::factory()->companion($guest)->create();

    $this->delete(route('guests.destroy', $guest))
        ->assertRedirectBackWithErrors([
            'is_primary' => 'It cannot delete guest with companions.',
        ]);

    $this->assertDatabaseCount('guests', 2);
    $this->assertDatabaseHas('guests', [
        'id' => $guest->id,
    ]);
});

test('cannot delete guest when unauthenticated', function () {
    $guest = Guest::factory()->create();

    $this->actingAsGuest();
    $this->delete(route('guests.destroy', $guest))
        ->assertRedirect(route('login'));

    $this->assertDatabaseHas('guests', [
        'id' => $guest->id,
    ]);
});
