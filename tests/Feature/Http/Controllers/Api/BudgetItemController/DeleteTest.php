<?php

use App\Models\BudgetItem;
use App\Models\User;
use Laravel\Sanctum\Sanctum;

use function Pest\Laravel\assertDatabaseHas;
use function Pest\Laravel\assertDatabaseMissing;

test('deletes budget item', function () {
    $item = BudgetItem::factory()->create();

    Sanctum::actingAs(User::factory()->create(), ['*']);
    $this->deleteJson(route('api.budgetItems.destroy', ['budgetItem' => $item->id]))
        ->assertNoContent();

    assertDatabaseMissing('budget_items', ['id' => $item->id]);
});

test('does not delete budget item when unauthenticated', function () {
    $item = BudgetItem::factory()->create();

    $this->deleteJson(route('api.budgetItems.destroy', ['budgetItem' => $item->id]))
        ->assertStatus(401);

    assertDatabaseHas('budget_items', ['id' => $item->id]);
});

test('does not delete unknown budget item', function () {
    Sanctum::actingAs(User::factory()->create(), ['*']);
    $this->deleteJson(route('api.budgetItems.destroy', ['budgetItem' => Str::uuid()]))
        ->assertStatus(404);
});
