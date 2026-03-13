<?php

use App\Models\Budget;
use App\Models\BudgetItem;
use App\Models\User;

test('guests are redirected to the login page', function () {
    $budget = Budget::factory()->create();

    $this->get(route('budgets.show', $budget))
        ->assertRedirect(route('login'));
});

test('renders budget show page with budget data', function () {
    $user = User::factory()->create();
    $budget = Budget::factory()->create(['name' => 'Wedding Budget']);

    $this->actingAs($user)
        ->get(route('budgets.show', $budget))
        ->assertOk()
        ->assertInertia(fn ($page) => $page
            ->component('Budgets/Show')
            ->has('budget', fn ($prop) => $prop
                ->where('id', $budget->id)
                ->where('name', 'Wedding Budget')
                ->etc()
            )
        );
});

test('includes budget items in show page', function () {
    $user = User::factory()->create();
    $budget = Budget::factory()->create();
    BudgetItem::factory()->count(3)->create(['budget_id' => $budget->id]);

    $this->actingAs($user)
        ->get(route('budgets.show', $budget))
        ->assertOk()
        ->assertInertia(fn ($page) => $page
            ->component('Budgets/Show')
            ->has('budget.items', 3)
        );
});
