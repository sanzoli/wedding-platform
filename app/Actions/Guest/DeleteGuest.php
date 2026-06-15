<?php

namespace App\Actions\Guest;

use App\Models\Guest;

class DeleteGuest
{
    public function execute(Guest $guest): void
    {
        $guest->delete();

        if ($guest->is_primary) {
            $guest->group()->delete();
        }
    }
}
