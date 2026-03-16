<?php

use App\Models\Invitation;
use App\Models\User;
use Laravel\Sanctum\Sanctum;

use function Pest\Laravel\assertDatabaseHas;
use function Pest\Laravel\assertDatabaseMissing;

test('deletes invitation', function () {
    $user = User::factory()->create();
    $invitation = Invitation::factory()->for($user->currentWedding)->create();

    Sanctum::actingAs($user, ['*']);
    $this->deleteJson(route('api.invitations.destroy', $invitation))
        ->assertNoContent();

    assertDatabaseMissing('invitations', ['id' => $invitation->id]);
});

test('does not delete invitation when unauthenticated', function () {
    $invitation = Invitation::factory()->create();

    $this->deleteJson(route('api.invitations.destroy', $invitation))
        ->assertStatus(401);

    assertDatabaseHas('invitations', ['id' => $invitation->id]);
});

test('does not delete unknown invitation', function () {
    Sanctum::actingAs(User::factory()->create(), ['*']);
    $this->deleteJson(route('api.invitations.destroy', ['invitation' => Str::uuid()]))
        ->assertStatus(404);
});
