<?php

use App\Models\Budget;
use App\Models\User;
use Laravel\Sanctum\Sanctum;

use function Pest\Laravel\assertDatabaseHas;
use function Pest\Laravel\assertDatabaseMissing;

test('deletes budget', function () {
    $budget = Budget::factory()->create();

    Sanctum::actingAs(User::factory()->create(), ['*']);
    $this->deleteJson(route('api.budgets.destroy', $budget))
        ->assertNoContent();

    assertDatabaseMissing('budgets', ['id' => $budget->id]);
});

test('does not delete budget when unauthenticated', function () {
    $budget = Budget::factory()->create();

    $this->deleteJson(route('api.budgets.destroy', $budget))
        ->assertStatus(401);

    assertDatabaseHas('budgets', ['id' => $budget->id]);
});

test('does not delete unknown budget', function () {
    Sanctum::actingAs(User::factory()->create(), ['*']);
    $this->deleteJson(route('api.budgets.destroy', ['budget' => Str::uuid()]))
        ->assertStatus(404);
});
