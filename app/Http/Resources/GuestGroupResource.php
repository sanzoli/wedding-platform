<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class GuestGroupResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'count' => $this->guests()->count(),
            'primary' => GuestResource::make($this->primaryGuest()),
            'companies' => GuestResource::collection($this->companies),
        ];
    }
}
