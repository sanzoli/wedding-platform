<?php

use App\Models\Budget;
use App\Models\User;

use function Pest\Laravel\assertDatabaseCount;
use function Pest\Laravel\assertDatabaseHas;

test('guests are redirected to the login page', function () {
    $budget = Budget::factory()->create();

    $this->post(route('budgets.items.store', $budget), ['name' => 'Venue'])
        ->assertRedirect(route('login'));

    assertDatabaseCount('budget_items', 0);
});

test('stores a budget item and redirects back', function () {
    $user = User::factory()->create();
    $budget = Budget::factory()->create();

    $this->actingAs($user)
        ->from(route('budgets.show', $budget))
        ->post(route('budgets.items.store', $budget), [
            'name' => 'Venue Rental',
            'importance' => 'High',
            'expected_amount' => 5000,
        ])
        ->assertRedirect(route('budgets.show', $budget));

    assertDatabaseCount('budget_items', 1);
    assertDatabaseHas('budget_items', [
        'budget_id' => $budget->id,
        'name' => 'Venue Rental',
        'importance' => 'High',
        'expected_amount' => 5000,
    ]);
});

test('stores a budget item without optional fields', function () {
    $user = User::factory()->create();
    $budget = Budget::factory()->create();

    $this->actingAs($user)
        ->from(route('budgets.show', $budget))
        ->post(route('budgets.items.store', $budget), [
            'name' => 'Venue Rental',
        ])
        ->assertRedirect(route('budgets.show', $budget));

    assertDatabaseCount('budget_items', 1);
    assertDatabaseHas('budget_items', [
        'name' => 'Venue Rental',
        'importance' => null,
        'expected_amount' => null,
    ]);
});

test('validates name is required', function () {
    $user = User::factory()->create();
    $budget = Budget::factory()->create();

    $this->actingAs($user)
        ->post(route('budgets.items.store', $budget), [])
        ->assertSessionHasErrors('name');

    assertDatabaseCount('budget_items', 0);
});
