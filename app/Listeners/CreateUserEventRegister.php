<?php

namespace App\Listeners;

use App\Contracts\UserEventContract;
use App\Models\UserEvent;

class CreateUserEventRegister
{
    public function handle(UserEventContract $event): void
    {
        UserEvent::create([
            'event' => $event->type(),
            'creator_type' => $event->creatorType(),
            'creator_id' => $event->creatorId(),
            'settings' => $event->settings(),
        ]);
    }
}
