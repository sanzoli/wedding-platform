<?php

use App\Models\Budget;
use App\Models\BudgetItem;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;

pest()->use(RefreshDatabase::class);

test('can list budget items', function () {
    $this->seed();

    $budget = Budget::first();
    $items = BudgetItem::factory()
        ->for($budget)
        ->count(3)
        ->create();

    $this->actingAs(User::first());
    $page = visit(route('budget'));

    $page->assertNoSmoke()
        ->assertNoConsoleLogs()
        ->assertNoJavaScriptErrors()
        ->assertTitle('Budget - '.config('app.name'))
        ->assertSourceHas('<h1 class="type-display text-foreground">'.$budget->name.'</h1>')
        ->assertSee($items->first()->name);
});
