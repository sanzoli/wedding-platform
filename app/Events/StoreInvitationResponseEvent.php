<?php

namespace App\Events;

use App\Contracts\UserEventContract;
use App\Models\Guest;
use App\Models\Invitation;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Http\Request;
use Illuminate\Queue\SerializesModels;

class StoreInvitationResponseEvent implements UserEventContract
{
    use Dispatchable, SerializesModels;

    private Invitation $invitation;

    private Request $request;

    public function __construct(Invitation $invitation, Request $request)
    {
        $this->invitation = $invitation;
        $this->request = $request;
    }

    public function id(): string
    {
        return $this->invitation->id;
    }

    public function type(): string
    {
        return 'invitation.response.'.$this->invitation->type->name;

    }

    public function creatorType(): string
    {
        return Guest::class;
    }

    public function creatorId(): string
    {
        return $this->invitation->guest->id;
    }

    public function settings(): array
    {
        return [
            'invitation' => $this->invitation->toArray(),
            'request' => [
                'url' => $this->request->url(),
                'toArray' => $this->request->toArray(),
            ],
        ];
    }
}
