<?php

namespace App\Http\Resources;

use App\Models\GuestGroup;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * @property GuestGroup $resource
 */
class GuestGroupResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->resource->id,
            'count' => $this->resource->guests->count(),
            'primary' => GuestResource::make($this->resource->primary),
            'companions' => GuestResource::collection($this->resource->companions),
        ];
    }
}
