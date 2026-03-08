<?php

namespace App\Models;

use App\Models\Scopes\CurrentWedding;
use Database\Factories\BudgetFactory;
use Illuminate\Database\Eloquent\Attributes\ScopedBy;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * @property string $id
 * @property string $name
 * @property bool $draft
 * @property int $wedding_id
 * @property Wedding $wedding
 */
#[ScopedBy([CurrentWedding::class])]
class Budget extends Model
{
    /** @use HasFactory<BudgetFactory> */
    use HasFactory, HasUuids;

    protected function casts(): array
    {
        return [
            'draft' => 'boolean',
        ];
    }

    public function items(): HasMany
    {
        return $this->hasMany(BudgetItem::class);
    }

    public function wedding(): BelongsTo
    {
        return $this->belongsTo(Wedding::class);
    }
}
