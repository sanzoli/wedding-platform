<?php

use App\Models\Category;
use App\Models\Guest;
use App\Models\User;

beforeEach(function () {
    $this->actingAs(User::factory()->create());

    $this->guest = Guest::factory()->create();
    $this->category = Category::create(['name' => 'family']);

    $this->guest->categories()->attach($this->category);
});

test('can remove a category to a guest', function () {
    $this->delete(route('guests.categories.remove', [
        'guest' => $this->guest,
        'category' => $this->category,
    ]))->assertRedirectBackWithoutErrors();

    $this->assertDatabaseMissing('category_guest', [
        'guest_id' => $this->guest->id,
        'category_id' => $this->category->id,
    ]);
});

test('cannot remove unknown category to a guest', function () {
    $this->delete(route('guests.categories.remove', [
        'guest' => $this->guest,
        'category' => 'unknown',
    ]))->assertNotFound();

    $this->assertDatabaseHas('category_guest', [
        'guest_id' => $this->guest->id,
    ]);
});

test('cannot remove category to a unknown guest', function () {
    $this->delete(route('guests.categories.remove', [
        'guest' => 'unknown',
        'category' => $this->category,
    ]))->assertNotFound();

    $this->assertDatabaseHas('category_guest', [
        'category_id' => $this->category->id,
    ]);
});

test('cannot remove a category to a guest when unauthenticated', function () {
    $this->actingAsGuest();
    $this->delete(route('guests.categories.remove', [
        'guest' => $this->guest,
        'category' => $this->category,
    ]))->assertRedirect(route('login'));

    $this->assertDatabaseHas('category_guest', [
        'guest_id' => $this->guest->id,
        'category_id' => $this->category->id,
    ]);
});
