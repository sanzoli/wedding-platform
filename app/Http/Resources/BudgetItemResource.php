<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class BudgetItemResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->resource->id,
            'budget_id' => $this->resource->budget_id,
            'name' => $this->resource->name,
            'importance' => $this->resource->importance?->name,
            'expected_amount' => $this->resource->expected_amount,
        ];
    }
}
