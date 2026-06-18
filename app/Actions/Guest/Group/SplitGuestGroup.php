<?php

namespace App\Actions\Guest\Group;

use App\Actions\Guest\DeleteGuest;
use App\Models\Guest;
use App\Models\GuestGroup;

class SplitGuestGroup
{
    public function execute(GuestGroup $group): void
    {
        $group->companions()
            ->each(function (Guest $guest) {
                $action = empty($guest->fullName) ? new DeleteGuest : new LeaveGuestGroup;
                $action->execute($guest);
            }
            );
    }
}
