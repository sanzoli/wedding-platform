<?php

use App\Models\User;
use Illuminate\Support\Str;
use Laravel\Sanctum\Sanctum;

use function Pest\Laravel\assertDatabaseCount;
use function Pest\Laravel\assertDatabaseHas;

test('stores budget', function () {
    $user = User::factory()->create();

    Sanctum::actingAs($user, ['*']);
    $response = $this->postJson(route('api.budgets.store'), [
        'name' => 'Budget 1',
        'draft' => true,
    ]);

    $response->assertCreated();
    assertDatabaseCount('budgets', 1);
    assertDatabaseHas('budgets', [
        'name' => 'Budget 1',
        'draft' => true,
        'wedding_id' => Auth::user()->current_wedding_id,
    ]);
});

test('stores budget with default draft', function () {
    $user = User::factory()->create();

    Sanctum::actingAs($user, ['*']);
    $response = $this->postJson(route('api.budgets.store'), [
        'name' => 'Budget 1',
    ]);

    $response->assertCreated();
    assertDatabaseCount('budgets', 1);
    assertDatabaseHas('budgets', [
        'name' => 'Budget 1',
        'draft' => false,
        'wedding_id' => Auth::user()->current_wedding_id,
    ]);
});

test('does not store budget when unauthenticated', function () {
    $this->postJson(route('api.budgets.store'))
        ->assertStatus(401);

    assertDatabaseCount('budgets', 0);
});

test('does not store budget without name', function () {
    Sanctum::actingAs(User::factory()->create(), ['*']);
    $response = $this->postJson(route('api.budgets.store', [
        'draft' => true,
    ]));

    $response->assertStatus(422)
        ->assertJsonFragment(['message' => 'The name field is required.'])
        ->assertOnlyJsonValidationErrors('name');

    assertDatabaseCount('budgets', 0);
});

test('does not store budget with invalid name', function ($invalidValue, $errorMessage) {
    Sanctum::actingAs(User::factory()->create(), ['*']);
    $response = $this->postJson(route('api.budgets.store'), [
        'name' => $invalidValue,
    ]);
    $response->assertStatus(422)
        ->assertJsonFragment(['message' => $errorMessage])
        ->assertOnlyJsonValidationErrors('name');

    assertDatabaseCount('budgets', 0);
})->with([
    'not string' => [['array'], 'The name field must be a string.'],
    'too long' => [Str::repeat('a', 61), 'The name field must not be greater than 60 characters.'],
]);

test('does not store budget with invalid draft', function () {
    Sanctum::actingAs(User::factory()->create(), ['*']);
    $response = $this->postJson(route('api.budgets.store'), [
        'name' => 'Budget 1',
        'draft' => 'string',
    ]);
    $response->assertStatus(422)
        ->assertJsonFragment(['message' => 'The draft field must be true or false.'])
        ->assertOnlyJsonValidationErrors('draft');

    assertDatabaseCount('budgets', 0);
});
