<?php

use App\Models\Guest;
use App\Models\User;

beforeEach(function () {
    $this->actingAs(User::factory()->create());
});

test('shows guest list', function () {
    $guests = Guest::factory()->count(5)->create();
    $page = visit(route('guests.index'));

    $page->assertNoSmoke()
        ->assertNoAccessibilityIssues()
        ->assertNoConsoleLogs()
        ->assertNoJavaScriptErrors()
        ->assertTitle('Guests - Wedding Platform')
        ->assertSee('Guest List');

    foreach ($guests as $guest) {
        $page->assertSee($guest->name)
            ->assertSee($guest->lang->value)
            ->assertSee($guest->mobile);
    }
});
