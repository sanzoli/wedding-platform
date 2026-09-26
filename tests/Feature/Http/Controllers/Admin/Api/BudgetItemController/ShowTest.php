<?php

use App\Models\BudgetItem;
use App\Models\User;
use Illuminate\Support\Str;
use Illuminate\Testing\Fluent\AssertableJson;
use Laravel\Sanctum\Sanctum;

test('shows a budget item', function () {
    $item = BudgetItem::factory()->create(['importance' => 'Low']);

    Sanctum::actingAs(User::factory()->create(), ['*']);
    $response = $this->getJson(route('api.items.show', ['item' => $item->id]));

    $response->assertOk()
        ->assertJson(fn (AssertableJson $json) => $json
            ->has('data', fn ($json) => $json
                ->where('id', $item->id)
                ->where('name', $item->name)
                ->where('importance', $item->importance->name)
                ->where('expected_amount', $item->expected_amount)
            )
        );
});

test('shows a budget item with nullable expected amount', function () {
    $item = BudgetItem::factory()->create(['expected_amount' => null]);

    Sanctum::actingAs(User::factory()->create(), ['*']);
    $response = $this->getJson(route('api.items.show', ['item' => $item->id]));

    $response->assertOk()
        ->assertJson(fn (AssertableJson $json) => $json
            ->has('data', fn ($json) => $json
                ->where('id', $item->id)
                ->whereNull('expected_amount')
                ->etc()
            )
        );
});

test('does not show budget item when unauthenticated', function () {
    $item = BudgetItem::factory()->create();

    $this->getJson(route('api.items.show', ['item' => $item->id]))
        ->assertStatus(401);
});

test('does not found unknown budget item', function () {
    Sanctum::actingAs(User::factory()->create(), ['*']);
    $this->getJson(route('api.items.show', ['item' => Str::uuid()]))
        ->assertStatus(404);
});
