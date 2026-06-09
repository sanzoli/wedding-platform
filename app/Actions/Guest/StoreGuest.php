<?php

namespace App\Actions\Guest;

use App\Models\Guest;
use App\Models\GuestGroup;

class StoreGuest
{
    public function store(array $params): Guest
    {
        $guest = new Guest;

        $guest->first_name = $params['first_name'] ?? null;
        $guest->last_name = $params['last_name'] ?? null;
        $guest->lang = $params['lang'] ?? null;
        $guest->mobile = $params['mobile'] ?? null;
        $guest->is_primary = !isset($params['group_id']);
        $guest->group_id = $params['group_id'] ?? GuestGroup::create([])->id;

        $guest->save();

        return $guest;
    }
}
