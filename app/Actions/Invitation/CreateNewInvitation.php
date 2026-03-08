<?php

namespace App\Actions\Invitation;

use App\Models\Invitation;
use Illuminate\Support\Facades\Auth;

class CreateNewInvitation
{
    public function create(array $input): Invitation
    {
        $invitation = new Invitation;

        $invitation->email = $input['email'] ?? null;
        $invitation->expires_at = $input['expires_at'] ?? null;

        $invitation->wedding()
            ->associate(Auth::user()->current_wedding_id)
            ->save();

        return $invitation;
    }
}
