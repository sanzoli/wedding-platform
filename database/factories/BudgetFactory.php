<?php

namespace Database\Factories;

use App\Models\Budget;
use App\Models\Wedding;
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
            'wedding_id' => Wedding::factory(),
        ];
    }

    public function draft(): Factory
    {
        return $this->state(fn () => ['draft' => true]);
    }
}
