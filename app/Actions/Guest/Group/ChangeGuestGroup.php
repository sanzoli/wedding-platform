<?php

namespace App\Actions\Guest\Group;

use App\Models\Guest;
use App\Models\GuestGroup;

class ChangeGuestGroup
{
    public function execute(Guest $guest, GuestGroup $newGroup): void
    {
        $oldGroup = $guest->group;
        if ($oldGroup->is($newGroup)) {
            return;
        }

        $wasPrimary = $guest->is_primary;

        $guest->is_primary = false;
        $guest->group()->associate($newGroup);
        $guest->save();

        if ($wasPrimary) {
            $oldGroup->delete();
        }
    }
}
