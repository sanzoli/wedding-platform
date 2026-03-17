<?php

use App\Models\Invitation;
use App\Models\User;
use Illuminate\Support\Str;
use Illuminate\Testing\Fluent\AssertableJson;

use function Pest\Laravel\assertDatabaseCount;
use function Pest\Laravel\assertDatabaseHas;
use function Pest\Laravel\assertDatabaseMissing;

test('creates new user when accept invitation', function () {
    $invitation = Invitation::factory()->create();

    $response = $this->postJson(route('api.invitations.accept', $invitation), [
        'name' => 'John Doe',
        'email' => 'johndoe@email.com',
        'password' => 'my-password',
        'password_confirmation' => 'my-password',
    ]);

    $response->assertOk()
        ->assertJson(fn (AssertableJson $json) => $json
            ->has('data', fn ($json) => $json
                ->where('id', $invitation->id)
                ->has('user', fn (AssertableJson $json) => $json
                    ->has('id')
                    ->where('name', 'John Doe')
                    ->where('email', 'johndoe@email.com')
                )->where('accepted_at', now()->toDateTimeString())
                ->etc()
            )
        );

    assertDatabaseHas('invitations', [
        'id' => $invitation->id,
        'user_id' => $response['data']['user']['id'],
        'accepted_at' => now()->toDateTimeString(),
    ]);

    assertDatabaseHas('users', [
        'name' => 'John Doe',
        'email' => 'johndoe@email.com',
    ]);
});

test('cannot accept already accepted invitation', function () {
    $invitation = Invitation::factory()->accepted()->create();

    $response = $this->postJson(route('api.invitations.accept', $invitation), [
        'name' => 'John Doe',
        'email' => 'johndoe@email.com',
        'password' => 'my-password',
        'password_confirmation' => 'my-password',
    ]);

    $response->assertStatus(422)
        ->assertJsonValidationErrors('invitation')
        ->assertJsonFragment(['message' => 'Invitation is already accepted.']);

    assertDatabaseMissing('users', [
        'name' => 'John Doe',
        'email' => 'johndoe@email.com',
    ]);
});

test('cannot accept an expired invitation', function () {
    $invitation = Invitation::factory()->expired()->create();

    $response = $this->postJson(route('api.invitations.accept', $invitation), [
        'name' => 'John Doe',
        'email' => 'johndoe@email.com',
        'password' => 'my-password',
        'password_confirmation' => 'my-password',
    ]);

    $response->assertStatus(422)
        ->assertJsonValidationErrors('invitation')
        ->assertJsonFragment(['message' => 'It can not accept an expired invitation.']);

    assertDatabaseMissing('users', [
        'name' => 'John Doe',
        'email' => 'johndoe@email.com',
    ]);
});

test('cannot accept an invitation with invalid user name', function ($invalidValue, $errorMessage) {
    $invitation = Invitation::factory()->expired()->create();

    $response = $this->postJson(route('api.invitations.accept', $invitation), [
        'name' => $invalidValue,
        'email' => 'johndoe@email.com',
        'password' => 'my-password',
        'password_confirmation' => 'my-password',
    ]);

    $response->assertStatus(422)
        ->assertJsonValidationErrors('name')
        ->assertJsonFragment(['message' => $errorMessage]);

    assertDatabaseMissing('users', [
        'name' => $invalidValue,
    ]);
})->with([
    'name missing' => [null, 'The name field is required.'],
    'not a string' => [['name'], 'The name field must be a string.'],
    'too long' => [Str::repeat('a', 256), 'The name field must not be greater than 255 characters.'],
]);

test('cannot accept an invitation with invalid user email', function ($invalidValue, $errorMessage) {
    $invitation = Invitation::factory()->expired()->create();

    $response = $this->postJson(route('api.invitations.accept', $invitation), [
        'name' => 'John Doe',
        'email' => $invalidValue,
        'password' => 'my-password',
        'password_confirmation' => 'my-password',
    ]);

    $response->assertStatus(422)
        ->assertJsonValidationErrors('email')
        ->assertJsonFragment(['message' => $errorMessage]);

    assertDatabaseMissing('users', [
        'email' => $invalidValue,
    ]);
})->with([
    'email missing' => [null, 'The email field is required.'],
    'not a string' => [['email'], 'The email field must be a string.'],
    'not a email' => ['not-email', 'The email field must be a valid email address.'],
    'too long' => [Str::repeat('a', 256).'@test.com', 'The email field must not be greater than 255 characters.'],
]);

test('cannot accept an invitation with invalid not unique email', function () {
    $invitation = Invitation::factory()->expired()->create();
    User::factory()->create(['email' => 'johndoe@email.com']);

    $response = $this->postJson(route('api.invitations.accept', $invitation), [
        'name' => 'John Doe',
        'email' => 'johndoe@email.com',
        'password' => 'my-password',
        'password_confirmation' => 'my-password',
    ]);

    $response->assertStatus(422)
        ->assertJsonValidationErrors('email')
        ->assertJsonFragment(['message' => 'The email has already been taken.']);

    assertDatabaseCount('users', 1);
    assertDatabaseMissing('users', [
        'name' => 'John Doe',
        'email' => 'johndoe@email.com',
    ]);
});

test('cannot accept an invitation with invalid user password', function ($invalidValue, $errorMessage) {
    $invitation = Invitation::factory()->create();

    $response = $this->postJson(route('api.invitations.accept', $invitation), [
        'name' => 'John Doe',
        'email' => 'johndoe@email.com',
        'password' => $invalidValue,
        'password_confirmation' => $invalidValue,
    ]);

    $response->assertStatus(422)
        ->assertJsonValidationErrors('password')
        ->assertJsonFragment(['message' => $errorMessage]);

    assertDatabaseCount('users', 0);
})->with([
    'password missing' => [null, 'The password field is required.'],
    'not a string' => [['password'], 'The password field must be a string.'],
    'too short' => ['a', 'The password field must be at least 8 characters.'],
]);

test('cannot accept an invitation with password confirmation mismatch', function () {
    $invitation = Invitation::factory()->expired()->create();

    $response = $this->postJson(route('api.invitations.accept', $invitation), [
        'name' => 'John Doe',
        'email' => 'johndoe@email.com',
        'password' => 'my-password',
        'password_confirmation' => null,
    ]);

    $response->assertStatus(422)
        ->assertJsonValidationErrors('password')
        ->assertJsonFragment(['message' => 'The password field confirmation does not match.']);

    assertDatabaseCount('users', 0);
});
