<?php

namespace App\Actions\Guest\Group;

use App\Models\Guest;
use App\Models\GuestGroup;
use Illuminate\Support\Facades\Validator;

class LeaveGuestGroup
{
    public function execute(Guest $guest): void
    {
        $this->validate($guest);

        $guest->group_id = GuestGroup::create([])->id;
        $guest->is_primary = true;

        $guest->save();
    }

    public function validate(Guest $guest): void
    {
        Validator::make([
            'is_primary' => $guest->is_primary,
            'full_name' => $guest->fullName,
        ], [
            'is_primary' => 'declined',
            'full_name' => 'required',
        ], [
            'is_primary.declined' => 'Primary guest cannot leave their group.',
            'full_name.required' => 'Anonymous guest cannot leave a group.',
        ])->validate();
    }
}
