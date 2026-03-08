<?php

use App\Models\Invitation;
use App\Models\User;
use Illuminate\Testing\Fluent\AssertableJson;
use Laravel\Sanctum\Sanctum;

beforeEach(function () {
    $this->user = User::factory()->create();
    Sanctum::actingAs($this->user, ['*']);
});

test('lists invitations', function () {
    Invitation::factory()
        ->for($this->user->currentWedding)
        ->count(5)
        ->sequence(
            ['accepted_at' => now(), 'user_id' => User::factory()->create()],
            ['email' => null],
            ['email' => null, 'accepted_at' => now()->subDay(), 'user_id' => User::factory()->create()]
        )->create();

    $response = $this->getJson(route('api.invitations.index'));

    $response
        ->assertOk()
        ->assertJson(fn (AssertableJson $json) => $json
            ->has('data', 5, fn (AssertableJson $json) => $json
                ->has('id')
                ->has('email')
                ->has('expires_at')
                ->has('user')
                ->has('accepted_at')
                ->has('created_at')
                ->has('updated_at')
            )->has('data', fn (AssertableJson $json) => $json
            ->first(fn (AssertableJson $json) => $json
                ->where('accepted_at', now()->format('Y-m-d H:i:s'))
                ->has('user', fn (AssertableJson $json) => $json
                    ->has('id')
                    ->has('name')
                    ->has('email')
                )->etc()
            )->etc()
            )->etc()
        );
});

test('lists only current wedding invitations', function () {
    Invitation::factory()->for($this->user->currentWedding)->create();
    Invitation::factory()->count(4)->create();

    $response = $this->getJson(route('api.invitations.index'));

    $response->assertOk()
        ->assertJson(fn (AssertableJson $json) => $json
            ->has('data', 1)
            ->etc()
        );
});

test('paginates invitations', function () {
    Invitation::factory()->for($this->user->currentWedding)->count(20)->create();

    $response = $this->getJson(route('api.invitations.index', ['page' => 2]));

    $response
        ->assertJson(fn (AssertableJson $json) => $json
            ->has('data', 5)
            ->has('links', fn ($json) => $json
                ->where('first', route('api.invitations.index', ['page' => 1]))
                ->where('last', route('api.invitations.index', ['page' => 2]))
                ->where('prev', route('api.invitations.index', ['page' => 1]))
                ->whereNull('next')
            )->has('meta', fn ($json) => $json
            ->where('current_page', 2)
            ->where('from', 16)
            ->where('to', 20)
            ->where('last_page', 2)
            ->where('total', 20)
            ->where('path', route('api.invitations.index'))
            ->where('per_page', 15)
            ->has('links', fn ($json) => $json
                ->each(fn ($json) => $json
                    ->has('url')
                    ->has('label')
                    ->has('page')
                    ->has('active')
                )
            )
            )
        );
});

test('returns empty list when empty', function () {
    $response = $this->getJson(route('api.invitations.index'));

    $response
        ->assertStatus(200)
        ->assertJson(fn (AssertableJson $json) => $json
            ->has('data', 0)
            ->has('links', fn ($json) => $json
                ->where('first', route('api.invitations.index', ['page' => 1]))
                ->where('last', route('api.invitations.index', ['page' => 1]))
                ->whereNull('prev')
                ->whereNull('next')
            )->has('meta', fn ($json) => $json
            ->where('current_page', 1)
            ->whereNull('from')
            ->whereNull('to')
            ->where('last_page', 1)
            ->where('total', 0)
            ->where('path', route('api.invitations.index'))
            ->where('per_page', 15)
            ->has('links', 3)
            )
        );
});

test('does not list invitations when unauthenticated', function () {
    Auth::forgetUser();
    $this->getJson(route('api.invitations.index'))
        ->assertStatus(401);
});
