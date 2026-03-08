<?php

use App\Models\Budget;
use App\Models\BudgetItem;
use App\Models\User;
use Illuminate\Testing\Fluent\AssertableJson;
use Laravel\Sanctum\Sanctum;

test('lists budget items', function () {
    $budget = Budget::factory()->create();
    $budgetItem = BudgetItem::factory()->for($budget)->create(['name' => 'LaLinda']);
    BudgetItem::factory()->for($budget)->count(4)->create();

    Sanctum::actingAs(User::factory()->create(), ['*']);
    $response = $this->getJson(route('api.budgets.items.index', $budget));

    $response
        ->assertStatus(200)
        ->assertJson(fn (AssertableJson $json) => $json
            ->has('data', fn ($json) => $json
                ->has(5)
                ->each(fn ($json) => $json
                    ->has('id')
                    ->has('name')
                    ->has('importance')
                    ->has('expected_amount')
                )->first(fn ($json) => $json
                ->where('id', $budgetItem->id)
                ->where('name', 'LaLinda')
                ->whereNull('importance')
                ->where('expected_amount', $budgetItem->expected_amount)
                )
            )->etc()
        );
});

test('paginates budget items', function () {
    BudgetItem::factory()->for($budget = Budget::factory()->create())->count(20)->create();

    Sanctum::actingAs(User::factory()->create(), ['*']);
    $response = $this->getJson(route('api.budgets.items.index', ['page' => 2, 'budget' => $budget->id]));

    $response
        ->assertJson(fn (AssertableJson $json) => $json
            ->has('data', 5)
            ->has('links', fn ($json) => $json
                ->where('first', route('api.budgets.items.index', ['page' => 1, 'budget' => $budget->id]))
                ->where('last', route('api.budgets.items.index', ['page' => 2, 'budget' => $budget->id]))
                ->where('prev', route('api.budgets.items.index', ['page' => 1, 'budget' => $budget->id]))
                ->whereNull('next')
            )->has('meta', fn ($json) => $json
            ->where('current_page', 2)
            ->where('from', 16)
            ->where('to', 20)
            ->where('last_page', 2)
            ->where('total', 20)
            ->where('path', route('api.budgets.items.index', $budget))
            ->where('per_page', 15)
            ->has('links', fn ($json) => $json
                ->each(fn ($json) => $json
                    ->has('url')
                    ->has('label')
                    ->has('page')
                    ->has('active')
                )
            )
            )
        );
});

test('searches budget items', function () {
    $budget = Budget::factory()
        ->has(BudgetItem::factory()
            ->count(5)
            ->sequence(
                ['name' => 'Local'],
                ['name' => 'Regalo'],

                ['name' => 'Decoración'],
                ['name' => 'Banda'],
                ['name' => 'Iluminación']
            ), 'items'
        )->create();

    Sanctum::actingAs(User::factory()->create(), ['*']);
    $response = $this->getJson(route('api.budgets.items.index', [
        'search' => 'lo',
        'budget' => $budget->id,
    ]));

    $response
        ->assertStatus(200)
        ->assertJson(fn (AssertableJson $json) => $json
            ->has('data', fn ($json) => $json
                ->has(2)
                ->where('0.name', 'Local')
                ->where('1.name', 'Regalo')
            )->etc()
        );
});

test('returns empty list when empty', function () {
    $budget = Budget::factory()->create();

    Sanctum::actingAs(User::factory()->create(), ['*']);
    $response = $this->getJson(route('api.budgets.items.index', $budget));

    $response
        ->assertStatus(200)
        ->assertJson(fn (AssertableJson $json) => $json
            ->has('data', 0)
            ->has('links', fn ($json) => $json
                ->where('first', route('api.budgets.items.index', ['page' => 1, 'budget' => $budget->id]))
                ->where('last', route('api.budgets.items.index', ['page' => 1, 'budget' => $budget->id]))
                ->whereNull('prev')
                ->whereNull('next')
            )->has('meta', fn ($json) => $json
            ->where('current_page', 1)
            ->whereNull('from')
            ->whereNull('to')
            ->where('last_page', 1)
            ->where('total', 0)
            ->where('path', route('api.budgets.items.index', $budget))
            ->where('per_page', 15)
            ->has('links', 3)
            )
        );
});

test('does not list budget items when unauthenticated', function () {
    $this->getJson(route('api.budgets.items.index', Budget::factory()->create()->id))
        ->assertStatus(401);
});
