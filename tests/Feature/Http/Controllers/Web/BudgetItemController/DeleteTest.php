<?php

use App\Models\Budget;
use App\Models\BudgetItem;
use App\Models\User;

use function Pest\Laravel\assertDatabaseHas;
use function Pest\Laravel\assertDatabaseMissing;

test('guests are redirected to the login page', function () {
    $item = BudgetItem::factory()->create();

    $this->delete(route('items.destroy', $item))
        ->assertRedirect(route('login'));

    assertDatabaseHas('budget_items', ['id' => $item->id]);
});

test('deletes a budget item and redirects back', function () {
    $user = User::factory()->create();
    $budget = Budget::factory()->create();
    $item = BudgetItem::factory()->create(['budget_id' => $budget->id]);

    $this->actingAs($user)
        ->from(route('budgets.show', $budget))
        ->delete(route('items.destroy', $item))
        ->assertRedirect(route('budgets.show', $budget));

    assertDatabaseMissing('budget_items', ['id' => $item->id]);
});

test('returns 404 for unknown budget item', function () {
    $user = User::factory()->create();

    $this->actingAs($user)
        ->delete(route('items.destroy', ['item' => fake()->uuid()]))
        ->assertNotFound();
});
