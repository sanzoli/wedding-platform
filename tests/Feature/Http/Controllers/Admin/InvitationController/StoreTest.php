<?php

use App\Enum\InvitationType;
use App\Models\Guest;
use App\Models\User;

beforeEach(function () {
    $this->actingAs(User::factory()->create());
});

test('can create a invitation', function () {
    $guest = Guest::factory()->create();

    $data = [
        'type' => InvitationType::SaveTheDate->name,
        'guest_id' => $guest->id,
        'default_language' => $guest->lang->value,
    ];

    $this->post(route('invitations.store'), $data)
        ->assertRedirectBackWithoutErrors();

    $this->assertDatabaseCount('invitations', 1);
    $this->assertDatabaseHas('invitations', $data);
});

test('can create a invitation with null default language', function () {
    $guest = Guest::factory()->create();

    $data = [
        'type' => InvitationType::SaveTheDate->name,
        'guest_id' => $guest->id,
        'default_language' => null,
    ];

    $this->post(route('invitations.store'), $data)
        ->assertRedirectBackWithoutErrors();

    $this->assertDatabaseCount('invitations', 1);
    $this->assertDatabaseHas('invitations', $data);
});

test('cannot create a invitation with invalid data', function ($properties, $key, $error) {
    $guest = Guest::factory()->create();

    $data = array_replace([
        'type' => InvitationType::SaveTheDate->name,
        'guest_id' => $guest->id,
        'default_language' => $guest->lang->value,
    ], $properties);

    $this->post(route('invitations.store'), $data)
        ->assertRedirectBackWithErrors([$key => $error]);

    $this->assertDatabaseCount('invitations', 0);
})->with([
    'empty type' => [
        ['type' => ''],
        'type',
        'The type field is required.',
    ],
    'type is invalid' => [
        ['type' => 'unknown'],
        'type',
        'The selected type is invalid.',
    ],
    'empty guest id' => [
        ['guest_id' => ''],
        'guest_id',
        'The guest id field is required.',
    ],
    'guest id does not exist' => [
        ['guest_id' => -1],
        'guest_id',
        'The selected guest id is invalid.',
    ],
    'invalid language' => [
        ['default_language' => 'invalid-default_language'],
        'default_language',
        'The selected default language is invalid.',
    ],
]);

test('cannot store invitations when unauthenticated', function () {
    $data = [
        'type' => InvitationType::SaveTheDate->name,
        'guest_id' => Guest::factory()->create()->id,
    ];

    $this->actingAsGuest();
    $this->post(route('invitations.store'), $data)
        ->assertRedirect(route('login'));

    $this->assertDatabaseCount('invitations', 0);
});
