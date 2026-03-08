<?php

use App\Models\Budget;
use App\Models\BudgetItem;
use App\Models\User;
use Illuminate\Testing\Fluent\AssertableJson;
use Laravel\Sanctum\Sanctum;

beforeEach(function () {
    $user = User::factory()->create();
    $this->budget = Budget::factory()->for($user->currentWedding)->create();

    Sanctum::actingAs($user, ['*']);
});

test('lists budget items', function () {
    $budgetItem = BudgetItem::factory()->for($this->budget)->create(['name' => 'LaLinda']);
    BudgetItem::factory()->for($this->budget)->count(4)->create();

    $response = $this->getJson(route('api.budgets.items.index', $this->budget));

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
    BudgetItem::factory()->for($this->budget)->count(20)->create();

    $response = $this->getJson(route('api.budgets.items.index', ['page' => 2, 'budget' => $this->budget->id]));

    $response
        ->assertJson(fn (AssertableJson $json) => $json
            ->has('data', 5)
            ->has('links', fn ($json) => $json
                ->where('first', route('api.budgets.items.index', ['page' => 1, 'budget' => $this->budget->id]))
                ->where('last', route('api.budgets.items.index', ['page' => 2, 'budget' => $this->budget->id]))
                ->where('prev', route('api.budgets.items.index', ['page' => 1, 'budget' => $this->budget->id]))
                ->whereNull('next')
            )->has('meta', fn ($json) => $json
            ->where('current_page', 2)
            ->where('from', 16)
            ->where('to', 20)
            ->where('last_page', 2)
            ->where('total', 20)
            ->where('path', route('api.budgets.items.index', $this->budget))
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
    BudgetItem::factory()
        ->for($this->budget)
        ->count(5)
        ->sequence(
            ['name' => 'Local'],
            ['name' => 'Regalo'],

            ['name' => 'Decoración'],
            ['name' => 'Banda'],
            ['name' => 'Iluminación']
        )->create();

    $response = $this->getJson(route('api.budgets.items.index', [
        'search' => 'lo',
        'budget' => $this->budget->id,
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
    $response = $this->getJson(route('api.budgets.items.index', $this->budget));

    $response
        ->assertStatus(200)
        ->assertJson(fn (AssertableJson $json) => $json
            ->has('data', 0)
            ->has('links', fn ($json) => $json
                ->where('first', route('api.budgets.items.index', ['page' => 1, 'budget' => $this->budget->id]))
                ->where('last', route('api.budgets.items.index', ['page' => 1, 'budget' => $this->budget->id]))
                ->whereNull('prev')
                ->whereNull('next')
            )->has('meta', fn ($json) => $json
            ->where('current_page', 1)
            ->whereNull('from')
            ->whereNull('to')
            ->where('last_page', 1)
            ->where('total', 0)
            ->where('path', route('api.budgets.items.index', $this->budget))
            ->where('per_page', 15)
            ->has('links', 3)
            )
        );
});

test('does not list budget items when unauthenticated', function () {
    Auth::forgetUser();
    $this->getJson(route('api.budgets.items.index', Budget::factory()->create()->id))
        ->assertStatus(401);
});
