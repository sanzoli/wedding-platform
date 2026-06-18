<?php

namespace App\Actions\Guest;

use App\Models\Guest;
use Illuminate\Support\Facades\Validator;

class DeleteGuest
{
    public function execute(Guest $guest): void
    {
        $this->validate($guest);

        $guest->delete();

        if ($guest->is_primary) {
            $guest->group()->delete();
        }
    }

    public function validate(Guest $guest): void
    {
        Validator::make([
            'is_primary' => $guest->is_primary,
            'has_companions' => $guest->group->companions()->exists(),
        ], [
            'is_primary' => 'declined_if:has_companions,true',
        ], [
            'is_primary.declined_if' => 'It cannot delete guest with companions.',
        ])->validate();
    }
}
