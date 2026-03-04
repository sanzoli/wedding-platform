<?php

use App\Models\BudgetItem;
use App\Models\User;
use Illuminate\Testing\Fluent\AssertableJson;
use Laravel\Sanctum\Sanctum;

test('lists budget items', function () {
    $provider = BudgetItem::factory()->create(['name' => 'LaLinda']);
    BudgetItem::factory()->count(4)->create();

    Sanctum::actingAs(User::factory()->create(), ['*']);
    $response = $this->getJson(route('api.budgetItems.index'));

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
                ->where('id', $provider->id)
                ->where('name', 'LaLinda')
                ->whereNull('importance')
                ->where('expected_amount', $provider->expected_amount)
                )
            )->etc()
        );
});

test('paginates budget items', function () {
    BudgetItem::factory()->count(20)->create();

    Sanctum::actingAs(User::factory()->create(), ['*']);
    $response = $this->getJson(route('api.budgetItems.index', ['page' => 2]));

    $response
        ->assertJson(fn (AssertableJson $json) => $json
            ->has('data', 5)
            ->has('links', fn ($json) => $json
                ->where('first', route('api.budgetItems.index', ['page' => 1]))
                ->where('last', route('api.budgetItems.index', ['page' => 2]))
                ->where('prev', route('api.budgetItems.index', ['page' => 1]))
                ->whereNull('next')
            )->has('meta', fn ($json) => $json
            ->where('current_page', 2)
            ->where('from', 16)
            ->where('to', 20)
            ->where('last_page', 2)
            ->where('total', 20)
            ->where('path', route('api.budgetItems.index'))
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
    BudgetItem::factory()->create(['name' => 'Local']);
    BudgetItem::factory()->create(['name' => 'Regalo']);

    BudgetItem::factory()->create(['name' => 'Decoración']);
    BudgetItem::factory()->create(['name' => 'Banda']);
    BudgetItem::factory()->create(['name' => 'Iluminación']);

    Sanctum::actingAs(User::factory()->create(), ['*']);
    $response = $this->getJson(route('api.budgetItems.index', ['search' => 'lo']));

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
    Sanctum::actingAs(User::factory()->create(), ['*']);
    $response = $this->getJson(route('api.budgetItems.index'));

    $response
        ->assertStatus(200)
        ->assertJson(fn (AssertableJson $json) => $json
            ->has('data', 0)
            ->has('links', fn ($json) => $json
                ->where('first', route('api.budgetItems.index', ['page' => 1]))
                ->where('last', route('api.budgetItems.index', ['page' => 1]))
                ->whereNull('prev')
                ->whereNull('next')
            )->has('meta', fn ($json) => $json
            ->where('current_page', 1)
            ->whereNull('from')
            ->whereNull('to')
            ->where('last_page', 1)
            ->where('total', 0)
            ->where('path', route('api.budgetItems.index'))
            ->where('per_page', 15)
            ->has('links', 3)
            )
        );
});

test('does not list budget items when unauthenticated', function () {
    $this->getJson(route('api.budgetItems.index'))
        ->assertStatus(401);
});
