<?php

namespace App\Models;

use App\Enum\Importance;
use App\Enum\Status;
use Database\Factories\BudgetItemFactory;
use Illuminate\Database\Eloquent\Casts\AsBinary;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * @method static paginate()
 *
 * @property string $id
 * @property string $name
 * @property ?Importance $importance
 * @property float $expected_amount
 */
class BudgetItem extends Model
{
    /** @use HasFactory<BudgetItemFactory> */
    use HasFactory, HasUuids;

    protected function casts(): array
    {
        return [
            'uuid' => AsBinary::uuid(),
            'importance' => Importance::class,
        ];
    }
}
