<?php

namespace Database\Factories;

use App\Enum\InvitationType;
use App\Enum\Language;
use App\Models\Guest;
use App\Models\Invitation;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Invitation>
 */
class InvitationFactory extends Factory
{
    public function definition(): array
    {
        return [
            'guest_id' => Guest::factory(),
        ];
    }

    public function saveTheDate(): self
    {
        return $this->state(fn () => ['type' => InvitationType::SaveTheDate]);
    }

    public function wedding(): self
    {
        return $this->state(fn () => ['type' => InvitationType::Wedding]);
    }

    public function pt(): self
    {
        return $this->state(fn () => ['default_language' => Language::Portuguese]);
    }

    public function es(): self
    {
        return $this->state(fn () => ['default_language' => Language::Spanish]);
    }

    public function en(): self
    {
        return $this->state(fn () => ['default_language' => Language::English]);
    }
}
