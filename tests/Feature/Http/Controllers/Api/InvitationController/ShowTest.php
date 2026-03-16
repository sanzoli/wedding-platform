<?php

use App\Models\BudgetItem;
use App\Models\Invitation;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use Illuminate\Testing\Fluent\AssertableJson;
use Laravel\Sanctum\Sanctum;

beforeEach(function () {
    $this->user = User::factory()->create();
    Sanctum::actingAs($this->user, ['*']);
});

test('shows a invitation', function () {
    $invitation = Invitation::factory()->for($this->user->currentWedding)->create();

    $response = $this->getJson(route('api.invitations.show', $invitation));

    $response->assertOk()
        ->assertJson(fn (AssertableJson $json) => $json
            ->has('data', fn ($json) => $json
                ->where('id', $invitation->id)
                ->where('email', $invitation->email)
                ->where('expires_at', $invitation->expires_at->toDateTimeString())
                ->whereNull('user')
                ->whereNull('accepted_at')
                ->where('created_at', $invitation->created_at->toDateTimeString())
                ->where('updated_at', $invitation->updated_at->toDateTimeString())
            )
        );
});

test('shows a accepted invitation', function () {
    $invitation = Invitation::factory()
        ->accepted()
        ->for($this->user->currentWedding)
        ->create();

    $response = $this->getJson(route('api.invitations.show', $invitation));

    $response->assertOk()
        ->assertJson(fn (AssertableJson $json) => $json
            ->has('data', fn ($json) => $json
                ->where('id', $invitation->id)
                ->where('email', $invitation->email)
                ->where('expires_at', $invitation->expires_at->toDateTimeString())
                ->where('user', $invitation->user->only('id', 'name', 'email'))
                ->where('accepted_at', $invitation->accepted_at->toDateTimeString())
                ->where('created_at', $invitation->created_at->toDateTimeString())
                ->where('updated_at', $invitation->updated_at->toDateTimeString())
            )
        );
});

test('does not show invitation when unauthenticated', function () {
    $invitation = Invitation::factory()->for($this->user->currentWedding)->create();

    Auth::forgetUser();
    $this->getJson(route('api.invitations.show', $invitation))
        ->assertStatus(401);
});

test('does not found unknown invitation', function () {
    $this->getJson(route('api.invitations.show', ['invitation' => Str::uuid()]))
        ->assertStatus(404);
});


test('does not found another wedding invitation', function () {
    $invitation = Invitation::factory()->create();
    $this->getJson(route('api.invitations.show', $invitation))
        ->assertStatus(404);
});
