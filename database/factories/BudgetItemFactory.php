<?php

namespace Database\Factories;

use App\Models\Budget;
use App\Models\BudgetItem;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<BudgetItem>
 */
class BudgetItemFactory extends Factory
{
    public function definition(): array
    {
        return [
            'budget_id' => Budget::factory(),
            'name' => fake()->name(),
            'expected_amount' => fake()->randomFloat(2, 100, 999999),
        ];
    }
}
