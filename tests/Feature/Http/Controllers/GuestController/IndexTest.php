<?php

use App\Enum\Language;
use App\Models\Guest;
use App\Models\User;
use Inertia\Testing\AssertableInertia as Assert;

beforeEach(function () {
    $this->actingAs(User::factory()->create());
});

test('can list guests', function () {
    Guest::factory()->count(3)->create();
    $guest = Guest::first();

    $this->get(route('guests.index'))
        ->assertOk()
        ->assertInertia(fn (Assert $page) => $page
            ->component('Guests')
            ->has('guestGroups.data', 3, fn (Assert $page) => $page
                ->where('id', $guest->group_id)
                ->where('count', 1)
                ->has('companions', 0)
                ->has('primary', fn (Assert $page) => $page
                    ->where('id', $guest->id)
                    ->where('group_id', $guest->group_id)
                    ->where('full_name', $guest->fullName)
                    ->where('initials', $guest->initials)
                    ->where('first_name', $guest->first_name)
                    ->where('last_name', $guest->last_name)
                    ->where('mobile', $guest->mobile)
                    ->where('lang', $guest->lang->value)
                    ->where('flag', $guest->lang->flag())
                )
            )
        );
});

test('can search list guests by name', function (string $search) {
    Guest::factory()->count(3)->create();
    $guest = Guest::factory()->create(['first_name' => 'John', 'last_name' => 'Doe']);

    $this->get(route('guests.index', compact('search')))
        ->assertOk()
        ->assertInertia(fn (Assert $page) => $page
            ->component('Guests')
            ->has('guestGroups.data', 1, fn (Assert $page) => $page
                ->where('id', $guest->group_id)
                ->where('count', 1)
                ->has('companions', 0)
                ->has('primary', fn (Assert $page) => $page
                    ->where('id', $guest->id)
                    ->where('full_name', 'John Doe')
                    ->where('initials', 'JD')
                    ->where('first_name', 'John')
                    ->where('last_name', 'Doe')
                    ->etc()
                )
            )
        );
})->with([
    'lowercase' => ['search' => 'john doe'],
    'first name' => ['search' => 'John'],
    'last name' => ['search' => 'Doe'],
    'partials' => ['search' => 'hn D'],
]);

test('can search list guests by companion name', function (string $search) {
    Guest::factory()->count(3)->create();
    $primary = Guest::factory()->create();
    $companion = Guest::factory()->companion($primary)->create(['first_name' => 'John', 'last_name' => 'Doe']);

    $this->get(route('guests.index', compact('search')))
        ->assertOk()
        ->assertInertia(fn (Assert $page) => $page
            ->component('Guests')
            ->has('guestGroups.data', 1, fn (Assert $page) => $page
                ->where('id', $companion->group_id)
                ->has('primary', fn (Assert $page) => $page
                    ->where('id', $primary->id)
                    ->where('group_id', $primary->group_id)
                    ->etc()
                )->has('companions', 1, fn (Assert $page) => $page
                ->where('id', $companion->id)
                ->where('group_id', $companion->group_id)
                ->where('full_name', 'John Doe')
                ->where('initials', 'JD')
                ->where('first_name', 'John')
                ->where('last_name', 'Doe')
                ->etc()
                )->where('count', 1)
            )
        );
})->with([
    'lowercase' => ['search' => 'john doe'],
    'first name' => ['search' => 'John'],
    'last name' => ['search' => 'Doe'],
    'partials' => ['search' => 'hn D'],
]);

test('can search list guests by mobile', function () {
    Guest::factory()->count(3)->create();
    Guest::factory()->create(['mobile' => '+573005999999']);

    $this->get(route('guests.index', ['search' => '059']))
        ->assertOk()
        ->assertInertia(fn (Assert $page) => $page
            ->component('Guests')
            ->has('guestGroups.data', 1, fn (Assert $page) => $page
                ->where('primary.mobile', '+573005999999')
                ->etc()
            )
        );
});

test('can search companion  by mobile', function () {
    Guest::factory()->count(3)->create();
    $companion = Guest::factory()->companion()->create(['mobile' => '+573005999999']);

    $this->get(route('guests.index', ['search' => '059']))
        ->assertOk()
        ->assertInertia(fn (Assert $page) => $page
            ->component('Guests')
            ->has('guestGroups.data', 1, fn (Assert $page) => $page
                ->where('id', $companion->group_id)
                ->has('primary', fn (Assert $page) => $page
                    ->where('id', $companion->group->primary->id)
                    ->where('group_id', $companion->group_id)
                    ->etc()
                )->has('companions', 1, fn (Assert $page) => $page
                ->where('id', $companion->id)
                ->where('group_id', $companion->group_id)
                ->where('mobile', '+573005999999')
                ->etc()
                )->where('count', 1)
            )
        );
});

test('can sort guest list by name', function (string $sortDirection, array $results) {
    Guest::factory()
        ->count(3)
        ->sequence(
            ['first_name' => 'John'],
            ['first_name' => 'Amanda'],
            ['first_name' => 'Thomas'],
        )->create();

    $this->get(route('guests.index', ['sort' => $sortDirection, 'sortBy' => 'first_name']))
        ->assertOk()
        ->assertInertia(fn (Assert $page) => $page
            ->component('Guests')
            ->where('guestGroups.data.0.primary.first_name', $results[0])
            ->where('guestGroups.data.1.primary.first_name', $results[1])
            ->where('guestGroups.data.2.primary.first_name', $results[2])
        );
})->with([
    'asc' => ['sortDirection' => 'asc', 'results' => ['Amanda', 'John', 'Thomas']],
    'desc' => ['sortDirection' => 'desc', 'results' => ['Thomas', 'John', 'Amanda']],
]);

test('can sort guest list by lang', function (string $sortDirection, array $results) {
    Guest::factory()
        ->count(3)
        ->sequence(
            ['lang' => Language::Spanish],
            ['lang' => Language::Portuguese],
            ['lang' => Language::English],
        )->create();

    $this->get(route('guests.index', ['sort' => $sortDirection, 'sortBy' => 'lang']))
        ->assertOk()
        ->assertInertia(fn (Assert $page) => $page
            ->component('Guests')
            ->where('guestGroups.data.0.primary.lang', $results[0])
            ->where('guestGroups.data.1.primary.lang', $results[1])
            ->where('guestGroups.data.2.primary.lang', $results[2])
        );
})->with([
    'asc' => ['sortDirection' => 'asc', 'results' => ['en', 'es', 'pt']],
    'desc' => ['sortDirection' => 'desc', 'results' => ['pt', 'es', 'en']],
]);

test('can sort guest list by mobile', function (string $sortDirection, array $results) {
    Guest::factory()
        ->count(3)
        ->sequence(
            ['mobile' => '+573005999999'],
            ['mobile' => '+13023469809'],
            ['mobile' => '+5544998176523'],
        )->create();

    $this->get(route('guests.index', ['sort' => $sortDirection, 'sortBy' => 'mobile']))
        ->assertOk()
        ->assertInertia(fn (Assert $page) => $page
            ->component('Guests')
            ->where('guestGroups.data.0.primary.mobile', $results[0])
            ->where('guestGroups.data.1.primary.mobile', $results[1])
            ->where('guestGroups.data.2.primary.mobile', $results[2])
        );
})->with([
    'asc' => ['sortDirection' => 'asc', 'results' => ['+13023469809', '+5544998176523', '+573005999999']],
    'desc' => ['sortDirection' => 'desc', 'results' => ['+573005999999', '+5544998176523', '+13023469809']],
]);

test('can sort and search guest list', function () {
    Guest::factory()
        ->count(3)
        ->sequence(
            ['first_name' => 'John'],
            ['first_name' => 'Amanda'],
            ['first_name' => 'Amadeu'],
        )->create();

    $this->get(route('guests.index', ['search' => 'ama', 'sort' => 'asc', 'sortBy' => 'first_name']))
        ->assertOk()
        ->assertInertia(fn (Assert $page) => $page
            ->component('Guests', 2)
            ->where('guestGroups.data.0.primary.first_name', 'Amadeu')
            ->where('guestGroups.data.1.primary.first_name', 'Amanda')
        );
});

test('does not list guests when unauthenticated', function () {
    $this->actingAsGuest();
    $this->getJson(route('guests.index'))
        ->assertStatus(401);
});
