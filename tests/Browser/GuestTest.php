<?php

use App\Models\Guest;
use App\Models\GuestGroup;
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
        $page->assertSee($guest->fullName)
            ->assertSee($guest->lang->value)
            ->assertSee($guest->mobile);
    }
});

test('can search guest list', function () {
    Guest::factory()->count(5)->create();
    Guest::factory()->create(['first_name' => 'John Jake', 'last_name' => 'Doe Dae']);

    $page = visit(route('guests.index'))
        ->type('input[type="search"]', 'Jake Doe');

    $page->assertSee('John Jake Doe Dae')
        ->assertSourceHas('John <mark class="rounded-xs bg-accent/30 text-foreground">Jake Doe</mark> Dae')
        ->assertNoSmoke()
        ->assertNoAccessibilityIssues()
        ->assertNoConsoleLogs()
        ->assertNoJavaScriptErrors();
});

test('can search a companion', function () {
    Guest::factory()->count(5)->create();
    Guest::factory()->companion()->create(['first_name' => 'John Jake', 'last_name' => 'Doe Dae']);

    $page = visit(route('guests.index'))
        ->type('input[type="search"]', 'Jake Doe');

    $page->assertSee('John Jake Doe Dae')
        ->assertSourceHas('John <mark class="rounded-xs bg-accent/30 text-foreground">Jake Doe</mark> Dae')
        ->assertNoSmoke()
        ->assertNoAccessibilityIssues()
        ->assertNoConsoleLogs()
        ->assertNoJavaScriptErrors();
});

test('can create a guest', function () {
    $page = visit(route('guests.index'))
        ->click('@add-button')
        ->type('first_name', 'John')
        ->type('last_name', 'Doe')
        ->click('@guest-store-button');

    $page->assertUrlIs(route('guests.index'))
        ->assertSee('John Doe')
        ->assertNoSmoke()
        ->assertNoAccessibilityIssues()
        ->assertNoConsoleLogs()
        ->assertNoJavaScriptErrors();
});

test('can create a companion', function () {
    $primary = Guest::factory()->create();

    $page = visit(route('guests.index'))
        ->click('@guest-add-companion-button-'.$primary->id)
        ->type('first_name', 'John')
        ->type('last_name', 'Doe')
        ->click('@companion-store-button');

    $page->assertUrlIs(route('guests.index'))
        ->assertSee('John Doe')
        ->assertNoSmoke()
        ->assertNoAccessibilityIssues()
        ->assertNoConsoleLogs()
        ->assertNoJavaScriptErrors();

    $companion = Guest::where('first_name', 'John')->first();
    expect($companion->group_id)->toBe($primary->group_id);
});

test('can update a guest', function () {
    $guest = Guest::factory()->create(['first_name' => 'John', 'last_name' => 'Doe']);

    $page = visit(route('guests.index'))
        ->click('@guest-edit-button-'.$guest->id)
        ->type('first_name', 'Jake')
        ->type('mobile', '+573008764321')
        ->click('@guest-update-button-'.$guest->id);

    $page->assertUrlIs(route('guests.index'))
        ->assertSee('Jake Doe')
        ->assertSee('+573008764321')
        ->assertNoSmoke()
        ->assertNoAccessibilityIssues()
        ->assertNoConsoleLogs()
        ->assertNoJavaScriptErrors();
});

test('can update a companion', function () {
    $companion = Guest::factory()->companion()->create(['first_name' => 'John', 'last_name' => 'Doe']);

    $page = visit(route('guests.index'))
        ->click('@companion-edit-button-'.$companion->id)
        ->type('first_name', 'Jake')
        ->type('mobile', '+573008764321')
        ->click('@companion-update-button-'.$companion->id);

    $page->assertUrlIs(route('guests.index'))
        ->assertSee('Jake Doe')
        ->assertSee('+573008764321')
        ->assertNoSmoke()
        ->assertNoAccessibilityIssues()
        ->assertNoConsoleLogs()
        ->assertNoJavaScriptErrors();
});

test('can delete guest', function () {
    $list = Guest::factory()->count(5)->create();
    $guest = Guest::factory()->create(['first_name' => 'John', 'last_name' => 'Doe']);

    $page = visit(route('guests.index'))
        ->assertSee('John Doe')
        ->click('@guest-delete-button-'.$guest->id)
        ->click('.swal2-confirm');

    $page->assertDontSee('John Doe')
        ->assertSee($list->first()->fullName)
        ->assertNoSmoke()
        ->assertNoConsoleLogs()
        ->assertNoJavaScriptErrors();

});

test('can add guest to a group', function () {
    $group = GuestGroup::factory()
        ->has(Guest::factory(), 'guests')
        ->create();

    $guest = Guest::factory()->create(['first_name' => 'John', 'last_name' => 'Doe']);

    visit(route('guests.index'))
        ->click('@primary-add-to-group-button-'.$guest->id)
        ->assertSee('Add to group')
        ->assertSee('Pick a destination group from the list and confirm the transfer.')
        ->type('group_id', $group->primary->first_name)
        ->click('[data-reka-popper-content-wrapper]')
        ->click('Change group')
        ->assertSourceHas('<span data-slot="avatar-fallback" class="flex size-full items-center justify-center rounded-full admin-type-action bg-muted text-muted-foreground">JD</span>');

    $guest->refresh();
    $this->assertEquals($group->id, $guest->group_id);
});

test('can change guest to a group', function () {
    $group = GuestGroup::factory()
        ->has(Guest::factory(), 'guests')
        ->create();

    $guest = Guest::factory()->companion()->create(['first_name' => 'John', 'last_name' => 'Doe']);

    visit(route('guests.index'))
        ->click('@companion-change-group-button-'.$guest->id)
        ->assertSee('Change group')
        ->assertSee('Pick a destination group from the list and confirm the transfer.')
        ->type('group_id', $group->primary->first_name)
        ->click('[data-reka-popper-content-wrapper]')
        ->click('Change group')
        ->assertSourceHas('<span data-slot="avatar-fallback" class="flex size-full items-center justify-center rounded-full admin-type-action bg-muted text-muted-foreground">JD</span>');

    $guest->refresh();
    $this->assertEquals($group->id, $guest->group_id);
});

test('can split a group', function () {
    $primary = Guest::factory()->create(['first_name' => 'John', 'last_name' => 'Doe']);
    $companion1 = Guest::factory()->companion($primary)->create(['first_name' => 'Alan', 'last_name' => 'Doe']);
    $companion2 = Guest::factory()->companion($primary)->create(['first_name' => 'David', 'last_name' => 'Doe']);

    visit(route('guests.index'))
        ->click('@split-guest-group-button-'.$primary->id)
        ->assertSourceHas('<span data-slot="avatar-fallback" class="flex size-full items-center justify-center rounded-full admin-type-action bg-secondary text-secondary-foreground">JD</span>')
        ->assertSourceHas('<span data-slot="avatar-fallback" class="flex size-full items-center justify-center rounded-full admin-type-action bg-secondary text-secondary-foreground">AD</span>')
        ->assertSourceHas('<span data-slot="avatar-fallback" class="flex size-full items-center justify-center rounded-full admin-type-action bg-secondary text-secondary-foreground">DD</span>');

    $this->assertNotEquals($primary->group_id, $companion1->refresh()->group_id);
    $this->assertNotEquals($primary->group_id, $companion2->refresh()->group_id);
});
