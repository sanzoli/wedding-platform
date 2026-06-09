<?php

namespace App\Actions\Guest;

use App\Models\Guest;
use Illuminate\Support\Arr;

class UpdateGuest
{
    public function update(Guest $guest, array $params): Guest
    {
        $guest->update(Arr::only($params, [
            'first_name',
            'last_name',
            'lang',
            'mobile',
        ]));

        return $guest;
    }
}
