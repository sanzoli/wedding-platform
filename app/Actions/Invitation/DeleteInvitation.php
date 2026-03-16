<?php

namespace App\Actions\Invitation;

use App\Models\Invitation;

class DeleteInvitation
{
    public function delete(Invitation $invitation): ?bool
    {
        return $invitation->delete();
    }
}
