<?php

use App\Models\Guest;
use App\Models\User;

beforeEach(function () {
    $this->actingAs(User::factory()->create());
});

test('can leave a group', function () {
    $primary = Guest::factory()->create();
    $companion = Guest::factory()->companion($primary)->create();

    $this->post(route('guests.group.leave', $companion))
        ->assertRedirectBackWithoutErrors();

    $companion->refresh();
    $this->assertNotEquals($companion->group_id, $primary->group_id);
    $this->assertTrue($companion->is_primary);
});

test('cannot leave a group as primary', function () {
    $primary = Guest::factory()->create();

    $this->post(route('guests.group.leave', $primary))
        ->assertRedirectBackWithErrors([
            'guest' => 'Primary guest cannot leave their group.',
        ]);
});

test('cannot leave a group as anonymous', function () {
    $primary = Guest::factory()->create();
    $companion = Guest::factory()->companion($primary)->create(['first_name' => '', 'last_name' => '']);

    $this->post(route('guests.group.leave', $companion))
        ->assertRedirectBackWithErrors([
            'guest' => 'Anonymous guest cannot leave a group.',
        ]);
});
