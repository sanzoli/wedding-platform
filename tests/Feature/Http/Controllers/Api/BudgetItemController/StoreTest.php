<?php

use App\Models\User;
use Illuminate\Support\Str;
use Illuminate\Testing\Fluent\AssertableJson;
use Laravel\Sanctum\Sanctum;
use function Pest\Laravel\assertDatabaseCount;
use function Pest\Laravel\assertDatabaseHas;

test('stores budget item', function () {
    Sanctum::actingAs(User::factory()->create(), ['*']);
    $response = $this->postJson(route('api.budgetItems.store'), [
        'name' => 'Iglesia',
        'expected_amount' => 2000
    ]);

    $response->assertCreated();
    assertDatabaseCount('budget_items', 1);
    assertDatabaseHas('budget_items', [
        'name' => 'Iglesia',
        'expected_amount' => 2000,
        'status' => 'Pending'
    ]);
});

test('stores budget item without expected amount', function () {
    Sanctum::actingAs(User::factory()->create(), ['*']);
    $response = $this->postJson(route('api.budgetItems.store'), [
        'name' => 'Iglesia',
    ]);

    $response->assertCreated();
    assertDatabaseCount('budget_items', 1);
    assertDatabaseHas('budget_items', [
        'name' => 'Iglesia',
        'expected_amount' => null,
    ]);
});

test('ignores status when store budget', function () {
    Sanctum::actingAs(User::factory()->create(), ['*']);
    $response = $this->postJson(route('api.budgetItems.store'), [
        'name' => 'Iglesia',
        'expected_amount' => 2000,
        'status' => 'Cancelled'
    ]);

    $response->assertCreated()
        ->assertJson(fn (AssertableJson $json) => $json
            ->has('data', fn ($json) => $json
                ->has('id')
                ->where('name', 'Iglesia')
                ->where('status', 'Pending')
                ->where('expected_amount', 2000))
        );

    assertDatabaseCount('budget_items', 1);
    assertDatabaseHas('budget_items', [
        'name' => 'Iglesia',
        'expected_amount' => 2000,
        'status' => 'Pending'
    ]);
});

test('does not store budget item when unauthenticated', function () {
    $this->postJson(route('api.budgetItems.store'))
        ->assertStatus(401);

    assertDatabaseCount('budget_items', 0);
});

test('does not store budget item without name', function () {
    Sanctum::actingAs(User::factory()->create(), ['*']);
    $response = $this->postJson(route('api.budgetItems.store'));

    $response->assertStatus(422)
        ->assertJsonFragment(['message' => 'The name field is required.'])
        ->assertOnlyJsonValidationErrors('name');

    assertDatabaseCount('budget_items', 0);
});

test('does not store budget item with invalid name', function ($invalidValue, $errorMessage) {
    Sanctum::actingAs(User::factory()->create(), ['*']);
    $response = $this->postJson(route('api.budgetItems.store'), [
        'name' => $invalidValue,
    ]);
    $response->assertStatus(422)
        ->assertJsonFragment(['message' => $errorMessage])
        ->assertOnlyJsonValidationErrors('name');

    assertDatabaseCount('budget_items', 0);
})->with([
    'not string' => [['array'], 'The name field must be a string.'],
    'too long' => [Str::repeat('a', 81), 'The name field must not be greater than 80 characters.'],
]);

test('does not store budget item with invalid expected amount', function ($invalidValue, $errorMessage) {
    Sanctum::actingAs(User::factory()->create(), ['*']);
    $response = $this->postJson(route('api.budgetItems.store'), [
        'name' => 'Iglesia',
        'expected_amount' => $invalidValue,
    ]);
    $response->assertStatus(422)
        ->assertJsonFragment(['message' => $errorMessage])
        ->assertOnlyJsonValidationErrors('expected_amount');

    assertDatabaseCount('budget_items', 0);
})->with([
    'not numeric' => ['string', 'The expected amount field must be a number.'],
    'more than 2 decimals' => [ .1234, 'The expected amount field must have 0-2 decimal places.'],
    'negative number' => [ -1, 'The expected amount field must be between 0 and 9999999999999999.'],
    'number too big' => [10000000000000000, 'The expected amount field must be between 0 and 9999999999999999.'],
]);
