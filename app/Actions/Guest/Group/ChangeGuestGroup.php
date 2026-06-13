<?php

namespace App\Actions\Guest\Group;

use App\Models\Guest;
use App\Models\GuestGroup;

class ChangeGuestGroup
{
    public function execute(Guest $guest, GuestGroup $newGroup): void
    {
        $guest->group()->associate($newGroup);

        $guest->save();
    }
}
