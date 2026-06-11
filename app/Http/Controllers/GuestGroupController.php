<?php

namespace App\Http\Controllers;

use App\Actions\Guest\Group\LeaveGuestGroup;
use App\Http\Requests\LeaveGuestGroupRequest;
use App\Models\Guest;

class GuestGroupController extends Controller
{
    public function leave(LeaveGuestGroupRequest $request, Guest $guest, LeaveGuestGroup $action)
    {
        $request->validated();

        $action->leave($guest);

        return back();
    }
}
