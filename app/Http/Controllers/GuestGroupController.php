<?php

namespace App\Http\Controllers;

use App\Actions\Guest\Group\ChangeGuestGroup;
use App\Actions\Guest\Group\LeaveGuestGroup;
use App\Actions\Guest\Group\SplitGroup;
use App\Models\Guest;
use App\Models\GuestGroup;

class GuestGroupController extends Controller
{
    public function leave(Guest $guest, LeaveGuestGroup $action)
    {
        $action->leave($guest);

        return back();
    }

    public function split(GuestGroup $group, SplitGroup $action)
    {
        $action->split($group);

        return back();
    }

    public function change(Guest $guest, GuestGroup $group, ChangeGuestGroup $action)
    {
        $action->execute($guest, $group);

        return back();
    }
}
