<?php

namespace App\Actions\Guest\Group;

use App\Actions\Guest\DeleteGuest;
use App\Models\Guest;
use App\Models\GuestGroup;

class SplitGroup
{
    private DeleteGuest $deleteAction;

    private LeaveGuestGroup $leaveGroupAction;

    public function split(GuestGroup $group): void
    {
        $group->companies()
            ->each(function (Guest $guest) {
                empty($guest->fullName)
                    ? $this->deleteGuestAction()->delete($guest)
                    : $this->leaveGroupAction()->leave($guest);
            }
            );
    }

    protected function deleteGuestAction(): DeleteGuest
    {
        if (! isset($this->deleteAction)) {
            $this->deleteAction = new DeleteGuest;
        }

        return $this->deleteAction;
    }

    protected function leaveGroupAction(): LeaveGuestGroup
    {
        if (! isset($this->leaveGroupAction)) {
            $this->leaveGroupAction = new LeaveGuestGroup;
        }

        return $this->leaveGroupAction;
    }
}
