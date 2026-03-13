<?php

use App\Models\Budget;
use App\Models\User;

use function Pest\Laravel\assertDatabaseCount;
use function Pest\Laravel\assertDatabaseHas;

test('guests are redirected to the login page', function () {
    $this->post(route('budgets.store'), ['name' => 'Budget'])
        ->assertRedirect(route('login'));

    assertDatabaseCount('budgets', 0);
});

test('creates a budget and redirects to show page', function () {
    $user = User::factory()->create();

    $this->actingAs($user)
        ->post(route('budgets.store'), [
            'name' => 'Wedding Budget',
            'draft' => false,
        ])
        ->assertRedirect();

    assertDatabaseCount('budgets', 1);
    assertDatabaseHas('budgets', [
        'name' => 'Wedding Budget',
        'draft' => false,
    ]);

    $budget = Budget::first();
    $this->assertNotNull($budget);
});

test('validates name is required', function () {
    $user = User::factory()->create();

    $this->actingAs($user)
        ->post(route('budgets.store'), [])
        ->assertSessionHasErrors('name');

    assertDatabaseCount('budgets', 0);
});
