<?php

namespace Database\Factories;

use App\Enum\Language;
use App\Models\Guest;
use App\Models\GuestGroup;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Guest>
 */
class GuestFactory extends Factory
{
    public function definition(): array
    {
        return [
            'first_name' => fake()->firstName(),
            'last_name' => fake()->lastName(),
            'lang' => Language::English->value,
            'mobile' => fake()->e164PhoneNumber(),
            'group_id' => fn (array $attributes) => GuestGroup::factory(),
        ];
    }

    public function companion(?Guest $guest = null): self
    {
        $guest ??= Guest::factory()->create();

        return $this->state(fn (array $attributes) => [
            'is_primary' => false,
            'group_id' => fn (array $attributes) => $guest->group_id,
        ]);
    }

    public function anonymous(): self
    {
        return $this->state(fn (array $attributes) => [
            'first_name' => '',
            'last_name' => '',
        ]);
    }
}
