<?php

namespace App\Actions\Invitation;

use App\Models\Invitation;
use App\Models\User;

class AcceptInvitation
{
    public function accept(Invitation $invitation, array $input): Invitation
    {
        $invitation
            ->user()
            ->associate(User::create([
                'name' => $input['name'],
                'email' => $input['email'],
                'password' => $input['password'],
            ]));

        $invitation->accepted_at = now();
        $invitation->save();

        return $invitation;
    }
}
