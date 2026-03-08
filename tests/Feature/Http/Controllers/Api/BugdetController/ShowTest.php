<?php

use App\Models\Budget;
use App\Models\BudgetItem;
use App\Models\User;
use Illuminate\Support\Str;
use Illuminate\Testing\Fluent\AssertableJson;
use Laravel\Sanctum\Sanctum;

test('shows a budget', function () {
    $user = User::factory()->create();
    $budget = Budget::factory()
        ->for($user->currentWedding)
        ->has(BudgetItem::factory()->count(3), 'items')
        ->create();

    Sanctum::actingAs($user, ['*']);
    $response = $this->getJson(route('api.budgets.show', $budget));

    $response->assertOk()
        ->assertJson(fn (AssertableJson $json) => $json
            ->has('data', fn ($json) => $json
                ->where('id', $budget->id)
                ->where('name', $budget->name)
                ->where('draft', false)
                ->has('items', 3)
                ->has('items', fn ($json) => $json
                    ->each(fn ($json) => $json
                        ->has('id')
                        ->has('name')
                        ->has('importance')
                        ->has('expected_amount')
                    )
                )
            )
        );
});

test('does not show budget item when unauthenticated', function () {
    $budget = Budget::factory()->create();

    $this->getJson(route('api.budgets.show', $budget))
        ->assertStatus(401);
});

test('does not found unknown budget item', function () {
    Sanctum::actingAs(User::factory()->create(), ['*']);
    $this->getJson(route('api.budgets.show', ['budget' => Str::uuid()]))
        ->assertStatus(404);
});
