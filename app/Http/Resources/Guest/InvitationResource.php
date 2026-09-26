<?php

namespace App\Http\Resources\Guest;

use App\Enum\InvitationResponse;
use App\Models\Guest;
use App\Models\Invitation;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * @property string $id
 * @property Guest $guest
 * @property InvitationResponse $response
 *
 * @see Invitation
 */
class InvitationResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'response' => $this->response?->value,
            'guest' => [
                'id' => $this->guest->id,
                'name' => $this->guest->fullName,
            ],
        ];
    }
}
