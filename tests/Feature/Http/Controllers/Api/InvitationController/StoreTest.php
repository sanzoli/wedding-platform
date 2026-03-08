<?php

use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Testing\Fluent\AssertableJson;
use Laravel\Sanctum\Sanctum;
use function Pest\Laravel\assertDatabaseCount;
use function Pest\Laravel\assertDatabaseHas;

beforeEach(function () {
    $this->user = User::factory()->create();
    Sanctum::actingAs($this->user, ['*']);
});

test('stores invitation to a wedding', function () {
    $this->postJson(route('api.invitations.store'))
        ->assertCreated()
        ->assertJson(fn (AssertableJson $json) => $json
            ->whereNotNull('data.id')
        );

    assertDatabaseCount('invitations', 1);
    assertDatabaseHas('invitations', [
        'wedding_id' => $this->user->current_wedding_id,
        'email' => null,
        'user_id' => null,
        'accepted_at' => null,
        'expires_at' => null,
    ]);
});

test('stores invitation with email', function () {
    $email = fake()->email();

    $this->postJson(route('api.invitations.store'), [
        'email' => $email,
    ])->assertCreated()
        ->assertJson(fn (AssertableJson $json) => $json
            ->whereNotNull('data.id')
            ->where('data.email', $email)
        );

    assertDatabaseCount('invitations', 1);
    assertDatabaseHas('invitations', [
        'wedding_id' => $this->user->current_wedding_id,
        'email' => $email,
        'user_id' => null,
        'accepted_at' => null,
    ]);
});

test('stores invitation with expiration', function () {
    $expiration = now()->addDays(30)->toDateTimeString();

    $this->postJson(route('api.invitations.store'), [
        'expires_at' => $expiration,
    ])->assertCreated()
        ->assertJson(fn (AssertableJson $json) => $json
            ->whereNotNull('data.id')
            ->where('data.expires_at', $expiration)
        );

    assertDatabaseCount('invitations', 1);
    assertDatabaseHas('invitations', [
        'wedding_id' => $this->user->current_wedding_id,
        'expires_at' => $expiration,
    ]);
});

test('does not store invitation when unauthenticated', function () {
    Auth::forgetUser();

    $this->postJson(route('api.invitations.store'))
        ->assertStatus(401);

    assertDatabaseCount('invitations', 0);
});

test('does not store invitation with invalid email', function () {
    $response = $this->postJson(route('api.invitations.store'), [
        'email' => 'not-an-email',
    ]);

    $response->assertStatus(422)
        ->assertJsonFragment(['message' => 'The email field must be a valid email address.'])
        ->assertOnlyJsonValidationErrors('email');

    assertDatabaseCount('invitations', 0);
});

test('does not store invitation with invalid expiration', function ($invalidValue, $errorMessage) {
    $response = $this->postJson(route('api.invitations.store'), [
        'expires_at' => $invalidValue,
    ]);

    $response->assertStatus(422)
        ->assertJsonFragment(['message' => $errorMessage])
        ->assertOnlyJsonValidationErrors('expires_at');

    assertDatabaseCount('invitations', 0);
})->with([
    'not a date' => ['not-a-date', 'The expires at field must be a valid date.'],
    'before now' => [now()->subMinute()->toDateTimeString(), 'The expires at field must be a date after now.'],
]);
