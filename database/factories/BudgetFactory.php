<?php

namespace Database\Factories;

use App\Models\Budget;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Budget>
 */
class BudgetFactory extends Factory
{
    public function definition(): array
    {
        return [
            'name' => 'Budget 1',
            'draft' => false,
        ];
    }

    public function draft(): Factory
    {
        return $this->state(fn () => ['draft' => true]);
    }
}
