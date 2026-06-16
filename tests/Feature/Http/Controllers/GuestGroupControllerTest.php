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
    $companion = Guest::factory()->anonymous()->companion($primary)->create();

    $this->post(route('guests.group.leave', $companion))
        ->assertRedirectBackWithErrors([
            'guest' => 'Anonymous guest cannot leave a group.',
        ]);
});

test('can split a group', function () {
    $primary = Guest::factory()->create();
    $companion = Guest::factory()->companion($primary)->create();
    $anotherCompanion = Guest::factory()->companion($primary)->create();

    $this->post(route('guests.group.split', $primary->group))
        ->assertRedirectBackWithoutErrors();

    $this->assertDatabaseCount('guest_groups', 3);
    $this->assertNotSame($companion->group, $primary->group);
    $this->assertNotSame($anotherCompanion->group, $primary->group);
});

test('split group eliminates anonymous companion', function () {
    $primary = Guest::factory()->create();
    Guest::factory()->companion($primary)->create();
    $anonymousCompanion = Guest::factory()->anonymous()->companion($primary)->create();

    $this->post(route('guests.group.split', $primary->group))
        ->assertRedirectBackWithoutErrors();

    $this->assertDatabaseCount('guest_groups', 2);
    $this->assertDatabaseMissing('guests', [
        'id' => $anonymousCompanion->id,
    ]);
});

test('can change companion group', function () {
    $primary = Guest::factory()->create();
    $companion = Guest::factory()->companion($primary)->create();

    $newPrimary = Guest::factory()->create();

    $this->put(route('guests.group.change', [
        'guest' => $companion->id,
        'group' => $newPrimary->group_id,
    ]))->assertRedirectBackWithoutErrors();

    $companion->refresh();
    $this->assertEquals($companion->group_id, $newPrimary->group_id);
});

test('can add guest as companion in another group', function () {
    $guest = Guest::factory()->create();
    $anotherPrimary = Guest::factory()->create();

    $this->put(route('guests.group.change', [
        'guest' => $guest->id,
        'group' => $anotherPrimary->group_id,
    ]))->assertRedirectBackWithoutErrors();

    $guest->refresh();
    $this->assertEquals($guest->group_id, $anotherPrimary->group_id);
    $this->assertFalse($guest->is_primary);

    $this->assertDatabaseCount('guest_groups', 1);
});
