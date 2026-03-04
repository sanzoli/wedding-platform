<?php

use App\Models\BudgetItem;
use App\Models\User;
use Illuminate\Support\Str;
use Illuminate\Testing\Fluent\AssertableJson;
use Laravel\Sanctum\Sanctum;
use function Pest\Laravel\assertDatabaseCount;
use function Pest\Laravel\assertDatabaseHas;
use function Pest\Laravel\assertDatabaseMissing;

test('updates budget item', function () {
    $item = BudgetItem::factory()->create([
        'name' => 'Church',
        'status' => 'Paid',
        'expected_amount' => 4000
    ]);

    Sanctum::actingAs(User::factory()->create(), ['*']);
    $response = $this->putJson(route('api.budgetItems.update', ['budgetItem' => $item->id]), [
        'name' => 'Iglesia',
        'expected_amount' => 2000,
        'importance' => 'Normal'
    ]);

    $response->assertOk()
        ->assertJson(fn (AssertableJson $json) => $json
            ->has('data', fn ($json) => $json
                ->where('id', $item->id)
                ->where('name', 'Iglesia')
                ->where('status', 'Paid')
                ->where('expected_amount', 2000)
                ->where('importance', 'Normal')
            )
        );

    assertDatabaseCount('budget_items', 1);
    assertDatabaseHas('budget_items', [
        'name' => 'Iglesia',
        'expected_amount' => 2000,
        'status' => 'Paid'
    ]);
});

test('can update only budget item name', function () {
    $item = BudgetItem::factory()->create([
        'name' => 'Church',
        'status' => 'Paid',
        'expected_amount' => 4000
    ]);

    Sanctum::actingAs(User::factory()->create(), ['*']);
    $response = $this->putJson(route('api.budgetItems.update', ['budgetItem' => $item->id]), [
        'name' => 'Iglesia',
    ]);

    $response->assertOk()
        ->assertJson(fn (AssertableJson $json) => $json
            ->has('data', fn ($json) => $json
                ->where('id', $item->id)
                ->where('name', 'Iglesia')
                ->where('status', 'Paid')
                ->where('expected_amount', 4000)
                ->whereNull('importance')
            )
        );

    assertDatabaseCount('budget_items', 1);
    assertDatabaseHas('budget_items', [
        'name' => 'Iglesia',
        'expected_amount' => 4000,
        'status' => 'Paid',
        'importance' => null
    ]);
});

test('can update only budget item expected amount', function () {
    $item = BudgetItem::factory()->create([
        'name' => 'Church',
        'status' => 'Paid',
        'expected_amount' => 4000
    ]);

    Sanctum::actingAs(User::factory()->create(), ['*']);
    $response = $this->putJson(route('api.budgetItems.update', ['budgetItem' => $item->id]), [
        'expected_amount' => 2000
    ]);

    $response->assertOk()
        ->assertJson(fn (AssertableJson $json) => $json
            ->has('data', fn ($json) => $json
                ->where('id', $item->id)
                ->where('name', 'Church')
                ->where('status', 'Paid')
                ->where('expected_amount', 2000)
                ->whereNull('importance')
            )
        );

    assertDatabaseCount('budget_items', 1);
    assertDatabaseHas('budget_items', [
        'name' => 'Church',
        'expected_amount' => 2000,
        'status' => 'Paid',
        'importance' => null
    ]);
});

test('can update budget item expected amount to null', function () {
    $item = BudgetItem::factory()->create([
        'name' => 'Church',
        'status' => 'Paid',
        'expected_amount' => 4000
    ]);

    Sanctum::actingAs(User::factory()->create(), ['*']);
    $response = $this->putJson(route('api.budgetItems.update', ['budgetItem' => $item->id]), [
        'expected_amount' => null
    ]);

    $response->assertOk()
        ->assertJson(fn (AssertableJson $json) => $json
            ->has('data', fn ($json) => $json
                ->where('id', $item->id)
                ->whereNull('expected_amount')
                ->etc()
            )
        );

    assertDatabaseCount('budget_items', 1);
    assertDatabaseHas('budget_items', [
        'id' => $item->id,
        'expected_amount' => null,
    ]);
});

test('can update only budget item importance', function () {
    $item = BudgetItem::factory()->create([
        'name' => 'Church',
        'status' => 'Paid',
        'expected_amount' => 2000
    ]);

    Sanctum::actingAs(User::factory()->create(), ['*']);
    $response = $this->putJson(route('api.budgetItems.update', ['budgetItem' => $item->id]), [
        'importance' => 'High'
    ]);

    $response->assertOk()
        ->assertJson(fn (AssertableJson $json) => $json
            ->has('data', fn ($json) => $json
                ->where('id', $item->id)
                ->where('name', 'Church')
                ->where('status', 'Paid')
                ->where('expected_amount', 2000)
                ->where('importance', 'High')
            )
        );

    assertDatabaseCount('budget_items', 1);
    assertDatabaseHas('budget_items', [
        'name' => 'Church',
        'expected_amount' => 2000,
        'status' => 'Paid',
        'importance' => 'High'
    ]);
});

test('can update budget item importance to null', function () {
    $item = BudgetItem::factory()->create([
        'name' => 'Church',
        'status' => 'Paid',
        'expected_amount' => 4000,
        'importance' => 'High'
    ]);

    Sanctum::actingAs(User::factory()->create(), ['*']);
    $response = $this->putJson(route('api.budgetItems.update', ['budgetItem' => $item->id]), [
        'importance' => null
    ]);

    $response->assertOk()
        ->assertJson(fn (AssertableJson $json) => $json
            ->has('data', fn ($json) => $json
                ->where('id', $item->id)
                ->whereNull('importance')
                ->etc()
            )
        );

    assertDatabaseCount('budget_items', 1);
    assertDatabaseHas('budget_items', [
        'id' => $item->id,
        'importance' => null,
    ]);
});

test('does not update budget item when unauthenticated', function () {
    $item = BudgetItem::factory()->create();

    $this->putJson(route('api.budgetItems.update', ['budgetItem' => $item->id]), [
        'name' => 'new-name',
    ])->assertStatus(401);

    assertDatabaseMissing('budget_items', [
        'name' => 'new-name',
    ]);
});

test('does not update unknown budget item', function () {
    $id = Str::uuid();

    Sanctum::actingAs(User::factory()->create(), ['*']);
    $this->putJson(route('api.budgetItems.update', ['budgetItem' => $id]), [
        'expected_amount' => null
    ])->assertStatus(404);

    assertDatabaseMissing('budget_items', compact('id'));
});

test('does not update budget item with invalid name', function ($invalidValue, $errorMessage) {
    $item = BudgetItem::factory()->create();

    Sanctum::actingAs(User::factory()->create(), ['*']);
    $response = $this->putJson(route('api.budgetItems.update', ['budgetItem' => $item->id]), [
        'name' => $invalidValue,
    ]);
    $response->assertStatus(422)
        ->assertJsonFragment(['message' => $errorMessage])
        ->assertOnlyJsonValidationErrors('name');

    assertDatabaseHas('budget_items', ['name' => $item->name]);
    assertDatabaseMissing('budget_items', ['name' => $invalidValue]);
})->with([
    'not string' => [['array'], 'The name field must be a string.'],
    'too long' => [Str::repeat('a', 81), 'The name field must not be greater than 80 characters.'],
]);

test('does not update budget item with invalid importance', function () {
    $item = BudgetItem::factory()->create();

    Sanctum::actingAs(User::factory()->create(), ['*']);
    $response = $this->putJson(route('api.budgetItems.update', ['budgetItem' => $item->id]), [
        'importance' => 'invalid-value',
    ]);
    $response->assertStatus(422)
        ->assertJsonFragment(['message' => 'The selected importance is invalid.'])
        ->assertOnlyJsonValidationErrors('importance');

    assertDatabaseHas('budget_items', ['expected_amount' => $item->expected_amount]);
    assertDatabaseMissing('budget_items', ['expected_amount' => 'invalid-value']);
});

test('does not update budget item with invalid expected amount', function ($invalidValue, $errorMessage) {
    $item = BudgetItem::factory()->create();

    Sanctum::actingAs(User::factory()->create(), ['*']);
    $response = $this->putJson(route('api.budgetItems.update', ['budgetItem' => $item->id]), [
        'expected_amount' => $invalidValue,
    ]);
    $response->assertStatus(422)
        ->assertJsonFragment(['message' => $errorMessage])
        ->assertOnlyJsonValidationErrors('expected_amount');

    assertDatabaseHas('budget_items', ['expected_amount' => $item->expected_amount]);
    assertDatabaseMissing('budget_items', ['expected_amount' => $invalidValue]);
})->with([
    'not numeric' => ['string', 'The expected amount field must be a number.'],
    'more than 2 decimals' => [ .1234, 'The expected amount field must have 0-2 decimal places.'],
    'negative number' => [ -1, 'The expected amount field must be between 0 and 9999999999999999.'],
    'number too big' => [10000000000000000, 'The expected amount field must be between 0 and 9999999999999999.'],
]);
