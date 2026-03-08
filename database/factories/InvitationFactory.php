<?php

namespace Database\Factories;

use App\Models\Invitation;
use App\Models\Wedding;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Invitation>
 */
class InvitationFactory extends Factory
{
    public function definition(): array
    {
        return [
            'wedding_id' => Wedding::factory(),
            'email' => fake()->unique()->safeEmail(),
            'expires_at' => Carbon::now()->addDays(rand(3, 60)),
        ];
    }
}
