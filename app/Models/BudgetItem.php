<?php

namespace App\Models;

use App\Enum\Importance;
use Database\Factories\BudgetItemFactory;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * @property string $id
 * @property string $name
 * @property ?Importance $importance
 * @property float $expected_amount
 * @property string $budget_id
 */
class BudgetItem extends Model
{
    /** @use HasFactory<BudgetItemFactory> */
    use HasFactory, HasUuids;

    protected function casts(): array
    {
        return [
            'importance' => Importance::class,
        ];
    }

    public function budget(): BelongsTo
    {
        return $this->belongsTo(Budget::class);
    }
}
