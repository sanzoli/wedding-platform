<?php

namespace App\Actions\Guest;

use App\Models\Guest;

class UpdateGuest
{
    public function update(Guest $guest, array $params): Guest
    {
        $guest->update($params);

        return $guest;
    }
}
