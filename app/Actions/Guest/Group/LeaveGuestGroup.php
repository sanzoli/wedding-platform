<?php

namespace App\Actions\Guest\Group;

use App\Models\Guest;
use App\Models\GuestGroup;

class LeaveGuestGroup
{
    public function leave(Guest $guest): void
    {
        $guest->group_id = GuestGroup::create([])->id;
        $guest->is_primary = true;

        $guest->save();
    }
}
