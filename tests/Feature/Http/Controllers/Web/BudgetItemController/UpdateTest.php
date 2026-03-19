<?php

use App\Models\Budget;
use App\Models\BudgetItem;
use App\Models\User;

use function Pest\Laravel\assertDatabaseHas;

test('guests are redirected to the login page', function () {
    $item = BudgetItem::factory()->create();

    $this->patch(route('items.update', $item), ['name' => 'New Name'])
        ->assertRedirect(route('login'));
});

test('updates a budget item and redirects back', function () {
    $user = User::factory()->create();
    $budget = Budget::factory()->create();
    $item = BudgetItem::factory()->create([
        'budget_id' => $budget->id,
        'name' => 'Old Name',
        'expected_amount' => 1000,
    ]);

    $this->actingAs($user)
        ->from(route('budgets.show', $budget))
        ->patch(route('items.update', $item), [
            'name' => 'New Name',
            'importance' => 'MustHave',
            'expected_amount' => 2000,
        ])
        ->assertRedirect(route('budgets.show', $budget));

    assertDatabaseHas('budget_items', [
        'id' => $item->id,
        'name' => 'New Name',
        'importance' => 'MustHave',
        'expected_amount' => 2000,
    ]);
});

test('can update only the name', function () {
    $user = User::factory()->create();
    $budget = Budget::factory()->create();
    $item = BudgetItem::factory()->create([
        'budget_id' => $budget->id,
        'name' => 'Old Name',
        'expected_amount' => 1000,
    ]);

    $this->actingAs($user)
        ->from(route('budgets.show', $budget))
        ->patch(route('items.update', $item), [
            'name' => 'New Name',
        ])
        ->assertRedirect(route('budgets.show', $budget));

    assertDatabaseHas('budget_items', [
        'id' => $item->id,
        'name' => 'New Name',
        'expected_amount' => 1000,
    ]);
});

test('validates invalid importance', function () {
    $user = User::factory()->create();
    $item = BudgetItem::factory()->create();

    $this->actingAs($user)
        ->patch(route('items.update', $item), [
            'importance' => 'invalid',
        ])
        ->assertSessionHasErrors('importance');
});
