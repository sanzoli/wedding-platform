<?php

use App\Models\Budget;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use Illuminate\Testing\Fluent\AssertableJson;
use Laravel\Sanctum\Sanctum;

use function Pest\Laravel\assertDatabaseCount;
use function Pest\Laravel\assertDatabaseHas;
use function Pest\Laravel\assertDatabaseMissing;

beforeEach(function () {
    $this->user = User::factory()->create();
    Sanctum::actingAs($this->user, ['*']);
});

test('updates budget', function () {
    $budget = Budget::factory()
        ->for($this->user->currentWedding)
        ->create([
            'name' => 'Budget 1',
            'draft' => false,
        ]);

    $response = $this->putJson(route('api.budgets.update', $budget), [
        'name' => 'Main Budget',
        'draft' => true,
    ]);

    $response->assertOk()
        ->assertJson(fn (AssertableJson $json) => $json
            ->has('data', fn ($json) => $json
                ->where('id', $budget->id)
                ->where('name', 'Main Budget')
                ->where('draft', true)
                ->has('items')
            )
        );

    assertDatabaseCount('budgets', 1);
    assertDatabaseHas('budgets', [
        'id' => $budget->id,
        'name' => 'Main Budget',
        'draft' => true,
    ]);
});

test('can update only budget name', function () {
    $budget = Budget::factory()
        ->for($this->user->currentWedding)
        ->create([
            'name' => 'Budget 1',
            'draft' => true,
        ]);

    $response = $this->putJson(route('api.budgets.update', $budget), [
        'name' => 'Main Budget',
    ]);

    $response->assertOk()
        ->assertJson(fn (AssertableJson $json) => $json
            ->has('data', fn ($json) => $json
                ->where('id', $budget->id)
                ->where('name', 'Main Budget')
                ->where('draft', true)
                ->etc()
            )
        );

    assertDatabaseCount('budgets', 1);
    assertDatabaseHas('budgets', [
        'id' => $budget->id,
        'name' => 'Main Budget',
        'draft' => true,
    ]);
});

test('can update only budget draft', function () {
    $budget = Budget::factory()
        ->for($this->user->currentWedding)
        ->create([
            'name' => 'Budget 1',
            'draft' => true,
        ]);

    $response = $this->putJson(route('api.budgets.update', $budget), [
        'draft' => false,
    ]);

    $response->assertOk()
        ->assertJson(fn (AssertableJson $json) => $json
            ->has('data', fn ($json) => $json
                ->where('id', $budget->id)
                ->where('name', 'Budget 1')
                ->where('draft', false)
                ->etc()
            )
        );

    assertDatabaseCount('budgets', 1);
    assertDatabaseHas('budgets', [
        'id' => $budget->id,
        'name' => 'Budget 1',
        'draft' => false,
    ]);
});

test('does not update budget when unauthenticated', function () {
    $budget = Budget::factory()->create();

    Auth::forgetUser();
    $this->putJson(route('api.budgets.update', $budget), [
        'name' => 'new-name',
    ])->assertStatus(401);

    assertDatabaseMissing('budgets', [
        'name' => 'new-name',
    ]);
});

test('does not update unknown budget', function () {
    $id = Str::uuid();

    $this->putJson(route('api.budgets.update', ['budget' => $id]), [
        'draft' => null,
    ])->assertStatus(404);

    assertDatabaseMissing('budgets', compact('id'));
});

test('does not update budget with invalid name', function ($invalidValue, $errorMessage) {
    $budget = Budget::factory()
        ->for($this->user->currentWedding)
        ->create();

    $response = $this->putJson(route('api.budgets.update', $budget), [
        'name' => $invalidValue,
    ]);
    $response->assertStatus(422)
        ->assertJsonFragment(['message' => $errorMessage])
        ->assertOnlyJsonValidationErrors('name');

    assertDatabaseMissing('budgets', ['name' => $invalidValue]);
    assertDatabaseHas('budgets', [
        'id' => $budget->id,
        'name' => $budget->name,
    ]);
})->with([
    'not string' => [['array'], 'The name field must be a string.'],
    'too long' => [Str::repeat('a', 61), 'The name field must not be greater than 60 characters.'],
]);

test('does not update budget with invalid draft', function () {
    $budget = Budget::factory()
        ->for($this->user->currentWedding)
        ->create();

    $response = $this->putJson(route('api.budgets.update', $budget), [
        'draft' => 'not-bolean',
    ]);
    $response->assertStatus(422)
        ->assertJsonFragment(['message' => 'The draft field must be true or false.'])
        ->assertOnlyJsonValidationErrors('draft');

    assertDatabaseMissing('budgets', ['draft' => 'not-bolean']);
    assertDatabaseHas('budgets', [
        'id' => $budget->id,
        'draft' => $budget->draft,
    ]);
});
