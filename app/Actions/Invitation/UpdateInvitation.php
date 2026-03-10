<?php

namespace App\Actions\Invitation;

use App\Models\Invitation;

class UpdateInvitation
{
    public function update(Invitation $invitation, array $input): Invitation
    {
        $invitation->email = array_key_exists('email', $input) ? $input['email'] : $invitation->email;
        $invitation->expires_at = array_key_exists('expires_at', $input) ? $input['expires_at'] : $invitation->expires_at;

        $invitation->save();

        return $invitation;
    }
}
