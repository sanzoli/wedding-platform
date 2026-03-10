<?php

use App\Models\Invitation;
use App\Models\User;
use Illuminate\Testing\Fluent\AssertableJson;
use Laravel\Sanctum\Sanctum;

use function Pest\Laravel\assertDatabaseCount;
use function Pest\Laravel\assertDatabaseHas;
use function Pest\Laravel\assertDatabaseMissing;

beforeEach(function () {
    $user = User::factory()->create();
    $this->invitation = Invitation::factory()
        ->for($user->currentWedding)
        ->create([
            'email' => 'old@mail.com',
            'expires_at' => now()->addDay(),
        ]);

    Sanctum::actingAs($user);
});

test('updates invitation', function () {
    $response = $this->putJson(route('api.invitations.update', $this->invitation), [
        'email' => 'test@mail.com',
        'expires_at' => now()->addMonth()->toDateTimeString(),
    ]);

    $response->assertOk()
        ->assertJson(fn (AssertableJson $json) => $json
            ->has('data', fn ($json) => $json
                ->where('id', $this->invitation->id)
                ->where('email', 'test@mail.com')
                ->where('expires_at', now()->addMonth()->toDateTimeString())
                ->etc()
            )
        );

    assertDatabaseCount('invitations', 1);
    assertDatabaseHas('invitations', [
        'id' => $this->invitation->id,
        'email' => 'test@mail.com',
        'expires_at' => now()->addMonth()->toDateTimeString(),
    ]);
});

test('updates invitation with null email', function () {
    $response = $this->putJson(route('api.invitations.update', $this->invitation), [
        'email' => null,
    ]);

    $response->assertOk()
        ->assertJson(fn (AssertableJson $json) => $json
            ->has('data', fn ($json) => $json
                ->where('id', $this->invitation->id)
                ->whereNull('email')
                ->etc()
            )
        );

    assertDatabaseCount('invitations', 1);
    assertDatabaseHas('invitations', [
        'id' => $this->invitation->id,
        'email' => null,
    ]);
});

test('updates invitation with null expiration', function () {
    $response = $this->putJson(route('api.invitations.update', $this->invitation), [
        'expires_at' => null,
    ]);

    $response->assertOk()
        ->assertJson(fn (AssertableJson $json) => $json
            ->has('data', fn ($json) => $json
                ->where('id', $this->invitation->id)
                ->whereNull('expires_at')
                ->etc()
            )
        );

    assertDatabaseCount('invitations', 1);
    assertDatabaseHas('invitations', [
        'id' => $this->invitation->id,
        'expires_at' => null,
    ]);
});

test('does not update invitation when unauthenticated', function () {
    Auth::forgetUser();
    $this->putJson(route('api.invitations.update', $this->invitation), [
        'name' => 'new-name',
    ])->assertStatus(401);

    assertDatabaseMissing('invitations', [
        'name' => 'new-name',
    ]);
});

test('does not update unknown invitation', function () {
    $id = Str::uuid();

    $this->putJson(route('api.invitations.update', ['invitation' => $id]), [
        'email' => null,
    ])->assertStatus(404);

    assertDatabaseMissing('invitations', compact('id'));
});

test('does not update an accepted invitation', function () {
    $this->invitation->accepted_at = now();
    $this->invitation->save();

    $response = $this->putJson(route('api.invitations.update', $this->invitation), [
        'email' => 'test@mail.com',
    ]);

    $response->assertStatus(422)
        ->assertJsonFragment(['message' => 'It can not update an accepted invitation'])
        ->assertOnlyJsonValidationErrors('invitation');

    assertDatabaseMissing('invitations', ['email' => 'test@mail.com']);
    assertDatabaseHas('invitations', [
        'id' => $this->invitation->id,
        'email' => $this->invitation->email,
    ]);
});

test('does not update a expired invitation', function () {
    $this->invitation->expires_at = now()->subMinute();
    $this->invitation->save();

    $response = $this->putJson(route('api.invitations.update', $this->invitation), [
        'email' => 'test@mail.com',
    ]);

    $response->assertStatus(422)
        ->assertJsonFragment(['message' => 'It can not update a expired invitation'])
        ->assertOnlyJsonValidationErrors('invitation');

    assertDatabaseMissing('invitations', ['email' => 'test@mail.com']);
    assertDatabaseHas('invitations', [
        'id' => $this->invitation->id,
        'email' => $this->invitation->email,
    ]);
});

test('does not update an invitation with invalid email', function () {
    $response = $this->putJson(route('api.invitations.update', $this->invitation), [
        'email' => 'not-email',
    ]);

    $response->assertStatus(422)
        ->assertJsonFragment(['message' => 'The email field must be a valid email address.'])
        ->assertOnlyJsonValidationErrors('email');

    assertDatabaseMissing('invitations', ['email' => 'not-email']);
    assertDatabaseHas('invitations', [
        'id' => $this->invitation->id,
        'email' => $this->invitation->email,
    ]);
});

test('does not update an invitation with invalid expiration', function ($invalidValue, $errorMessage) {
    $response = $this->putJson(route('api.invitations.update', $this->invitation), [
        'expires_at' => $invalidValue,
    ]);

    $response->assertStatus(422)
        ->assertJsonFragment(['message' => $errorMessage])
        ->assertOnlyJsonValidationErrors('expires_at');

    assertDatabaseMissing('invitations', ['expires_at' => $invalidValue]);
    assertDatabaseHas('invitations', [
        'id' => $this->invitation->id,
        'expires_at' => $this->invitation->expires_at,
    ]);
})->with([
    'not a date' => ['not-a-date', 'The expires at field must be a valid date.'],
    'before now' => [now()->subMinute()->toDateTimeString(), 'The expires at field must be a date after now.'],
]);
