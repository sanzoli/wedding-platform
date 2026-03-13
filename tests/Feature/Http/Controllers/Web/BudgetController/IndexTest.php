<?php

use App\Models\Budget;
use App\Models\User;

test('guests are redirected to the login page', function () {
    $this->get(route('budgets.index'))
        ->assertRedirect(route('login'));
});

test('redirects to existing budget when one exists', function () {
    $user = User::factory()->create();
    $budget = Budget::factory()->create();

    $this->actingAs($user)
        ->get(route('budgets.index'))
        ->assertRedirect(route('budgets.show', $budget));
});

test('renders index page when no budgets exist', function () {
    $user = User::factory()->create();

    $this->actingAs($user)
        ->get(route('budgets.index'))
        ->assertOk()
        ->assertInertia(fn ($page) => $page->component('Budgets/Index'));
});
