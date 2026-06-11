<?php

namespace App\Actions\Guest\Group;

use App\Models\Guest;
use App\Models\GuestGroup;
use Illuminate\Support\Facades\Validator;

class LeaveGuestGroup
{
    public function leave(Guest $guest): void
    {
        $this->validate($guest);

        $guest->group_id = GuestGroup::create([])->id;
        $guest->is_primary = true;

        $guest->save();
    }

    public function validate(Guest $guest): void
    {
        Validator::make([], [])
            ->after(function ($validator) use ($guest) {
                if ($guest->is_primary) {
                    $validator->errors()->add('guest', 'Primary guest cannot leave their group.');
                }

                if (empty($guest->fullName)) {
                    $validator->errors()->add('guest', 'Anonymous guest cannot leave a group.');
                }
            })->validate();
    }
}
