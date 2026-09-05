<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class GuestResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'group_id' => $this->group_id,
            'full_name' => $this->fullName,
            'initials' => $this->initials,
            'first_name' => $this->first_name ?? '',
            'last_name' => $this->last_name ?? '',
            'mobile' => $this->mobile ?? '',
            'lang' => $this->lang?->value ?? '',
            'flag' => $this->lang?->flag() ?? '-',
        ];
    }
}
