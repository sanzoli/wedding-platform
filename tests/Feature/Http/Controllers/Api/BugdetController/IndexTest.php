<?php

use App\Models\Budget;
use App\Models\BudgetItem;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Testing\Fluent\AssertableJson;
use Laravel\Sanctum\Sanctum;

beforeEach(function () {
    $this->user = User::factory()->create();
    Sanctum::actingAs($this->user, ['*']);
});

test('lists budgets', function () {
    Budget::factory()
        ->for($this->user->currentWedding)
        ->has(BudgetItem::factory()->count(3), 'items')
        ->create();

    Budget::factory()
        ->for($this->user->currentWedding)
        ->count(4)
        ->draft()
        ->sequence(
            ['name' => 'Budget 2'],
            ['name' => 'Budget 3'],
            ['name' => 'Budget 4'],
            ['name' => 'Budget 5'],
        )->create();

    $response = $this->getJson(route('api.budgets.index'));

    $response->assertOk()
        ->assertJson(fn (AssertableJson $json) => $json
            ->has('data', 5)
            ->has('data', fn ($json) => $json
                ->each(fn ($json) => $json
                    ->has('id')
                    ->has('name')
                    ->has('draft')
                    ->has('items')
                )->first(fn ($json) => $json
                ->where('name', 'Budget 1')
                ->where('draft', false)
                ->has('items', 3)
                ->has('items', fn ($json) => $json
                    ->each(fn ($json) => $json
                        ->has('id')
                        ->has('name')
                        ->has('importance')
                        ->has('expected_amount')
                    )
                )->etc()
                )
            )->etc()
        );
});

test('lists only current wedding budgets', function () {
    Budget::factory()
        ->for($this->user->currentWedding)
        ->create();

    Budget::factory()
        ->count(4)
        ->draft()
        ->sequence(
            ['name' => 'Budget 2'],
            ['name' => 'Budget 3'],
        )->create();

    $response = $this->getJson(route('api.budgets.index'));

    $response->assertOk()
        ->assertJson(fn (AssertableJson $json) => $json
            ->has('data', 1)
            ->etc()
        );
});

test('paginates budgets', function () {
    Budget::factory()->for($this->user->currentWedding)->count(20)->create();

    $response = $this->getJson(route('api.budgets.index', ['page' => 2]));

    $response
        ->assertJson(fn (AssertableJson $json) => $json
            ->has('data', 5)
            ->has('links', fn ($json) => $json
                ->where('first', route('api.budgets.index', ['page' => 1]))
                ->where('last', route('api.budgets.index', ['page' => 2]))
                ->where('prev', route('api.budgets.index', ['page' => 1]))
                ->whereNull('next')
            )->has('meta', fn ($json) => $json
            ->where('current_page', 2)
            ->where('from', 16)
            ->where('to', 20)
            ->where('last_page', 2)
            ->where('total', 20)
            ->where('path', route('api.budgets.index'))
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

test('returns empty list when empty', function () {
    $response = $this->getJson(route('api.budgets.index'));

    $response
        ->assertStatus(200)
        ->assertJson(fn (AssertableJson $json) => $json
            ->has('data', 0)
            ->has('links', fn ($json) => $json
                ->where('first', route('api.budgets.index', ['page' => 1]))
                ->where('last', route('api.budgets.index', ['page' => 1]))
                ->whereNull('prev')
                ->whereNull('next')
            )->has('meta', fn ($json) => $json
            ->where('current_page', 1)
            ->whereNull('from')
            ->whereNull('to')
            ->where('last_page', 1)
            ->where('total', 0)
            ->where('path', route('api.budgets.index'))
            ->where('per_page', 15)
            ->has('links', 3)
            )
        );
});

test('does not list budgets when unauthenticated', function () {
    Auth::forgetUser();
    $this->getJson(route('api.budgets.index'))
        ->assertStatus(401);
});
