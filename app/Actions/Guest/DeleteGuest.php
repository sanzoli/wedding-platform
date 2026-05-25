<?php

namespace App\Actions\Guest;

use App\Models\Guest;

class DeleteGuest
{
    public function delete(Guest $guest): ?bool
    {
        return $guest->delete();
    }
}
