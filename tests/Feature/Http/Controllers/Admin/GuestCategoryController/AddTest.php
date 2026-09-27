<?php

use App\Models\Category;
use App\Models\Guest;
use App\Models\User;

beforeEach(function () {
    $this->actingAs(User::factory()->create());
});

test('can add a category to a guest', function () {
    $guest = Guest::factory()->create();
    $category = Category::create(['name' => 'family']);

    $this->post(route('guests.categories.add', compact('guest', 'category')))
        ->assertRedirectBackWithoutErrors();

    $this->assertDatabaseHas('category_guest', [
        'guest_id' => $guest->id,
        'category_id' => $category->id,
    ]);
});

test('can add a category already to a guest without duplicate', function () {
    $guest = Guest::factory()->create();
    $category = Category::create(['name' => 'family']);
    $guest->categories()->attach($category);

    $this->post(route('guests.categories.add', compact('guest', 'category')))
        ->assertRedirectBackWithoutErrors();

    $this->assertDatabaseCount('category_guest', 1);
    $this->assertDatabaseHas('category_guest', [
        'guest_id' => $guest->id,
        'category_id' => $category->id,
    ]);
});

test('cannot add unknown category to a guest', function () {
    $guest = Guest::factory()->create();

    $this->post(route('guests.categories.add', [
        'guest' => $guest,
        'category' => 'unknown',
    ]))->assertNotFound();

    $this->assertDatabaseMissing('category_guest', [
        'guest_id' => $guest->id,
    ]);
});

test('cannot add category to a unknown guest', function () {
    $category = Category::create(['name' => 'family']);

    $this->post(route('guests.categories.add', [
        'guest' => 'unknown',
        'category' => $category,
    ]))->assertNotFound();

    $this->assertDatabaseMissing('category_guest', [
        'category_id' => $category->id,
    ]);
});

test('cannot add a category to a guest when unauthenticated', function () {
    $guest = Guest::factory()->create();
    $category = Category::create(['name' => 'family']);

    $this->actingAsGuest();
    $this->post(route('guests.categories.add', compact('guest', 'category')))
        ->assertRedirect(route('login'));

    $this->assertDatabaseMissing('category_guest', [
        'guest_id' => $guest->id,
        'category_id' => $category->id,
    ]);
});
