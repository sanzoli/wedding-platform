<?php

namespace Database\Factories;

use App\Models\Wedding;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Wedding>
 */
class WeddingFactory extends Factory
{
    public function definition(): array
    {
        return [
            'name' => fake()->name().' & '.fake()->name(),
            'date' => Carbon::now()->addDays(rand(30, 365))->format('Y-m-d'),
        ];
    }

    public function noDate()
    {
        return $this->state(fn () => ['date' => null]);
    }
}
