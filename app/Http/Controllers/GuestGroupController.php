<?php

namespace App\Http\Controllers;

use App\Actions\Guest\Group\ChangeGuestGroup;
use App\Actions\Guest\Group\LeaveGuestGroup;
use App\Actions\Guest\Group\SplitGuestGroup;
use App\Models\Guest;
use App\Models\GuestGroup;

class GuestGroupController extends Controller
{
    public function leave(Guest $guest, LeaveGuestGroup $leaveGuestGroup)
    {
        $leaveGuestGroup->execute($guest);

        return back();
    }

    public function split(GuestGroup $group, SplitGuestGroup $splitGuestGroup)
    {
        $splitGuestGroup->execute($group);

        return back();
    }

    public function change(Guest $guest, GuestGroup $group, ChangeGuestGroup $action)
    {
        $action->execute($guest, $group);

        return back();
    }
}
