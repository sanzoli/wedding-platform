<?php

namespace App\Actions\Guest;

use App\Models\Guest;

class DeleteGuest
{
    public function delete(Guest $guest)
    {
        $guest->delete();

        if ($guest->is_primary) {
            $guest->group()->delete();
        }
    }
}
