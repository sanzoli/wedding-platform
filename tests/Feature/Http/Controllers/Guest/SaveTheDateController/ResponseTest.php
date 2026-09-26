<?php

use App\Enum\InvitationResponse;
use App\Models\Invitation;

test('can responde a invitation', function (string $response) {
    $invitation = Invitation::factory()->saveTheDate()->create();

    $this->post(
        route('save-the-date.response', $invitation),
        compact('response')
    )->assertRedirectBackWithoutErrors();

    $this->assertDatabaseHas('invitations', [
        'id' => $invitation->id,
        'response' => $response,
    ]);
})->with([
    'yes' => ['response' => 'yes'],
    'probably_yes' => ['response' => 'probably_yes'],
    'probably_no' => ['response' => 'probably_no'],
    'no' => ['response' => 'no'],
]);

test('can update a invitation response', function (string $response) {
    $invitation = Invitation::factory()
        ->saveTheDate()
        ->create(['response' => Arr::random(InvitationResponse::cases())]);

    $this->post(
        route('save-the-date.response', $invitation),
        compact('response')
    )->assertRedirectBackWithoutErrors();

    $this->assertDatabaseHas('invitations', [
        'id' => $invitation->id,
        'response' => $response,
    ]);
})->with([
    'yes' => ['response' => 'yes'],
    'probably_yes' => ['response' => 'probably_yes'],
    'probably_no' => ['response' => 'probably_no'],
    'no' => ['response' => 'no'],
]);

test('cannot responde a invitation with invalid response', function (?string $response, string $error) {
    $invitation = Invitation::factory()->saveTheDate()->create();

    $this->post(
        route('save-the-date.response', $invitation),
        compact('response')
    )->assertRedirectBackWithErrors(['response' => $error]);
})->with([
    'is null' => ['response' => null, 'error' => 'The response field is required.'],
    'is invalid' => ['response' => 'never', 'error' => 'The selected response is invalid.'],
]);

test('cannot responde a unknown invitation', function () {
    $this->post(
        route('save-the-date.response', 'random-code'),
        ['response' => 'yes']
    )->assertNotFound();
});
