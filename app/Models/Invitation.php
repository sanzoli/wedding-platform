<?php

namespace App\Models;

use App\Models\Scopes\CurrentWedding;
use Carbon\Carbon;
use Database\Factories\InvitationFactory;
use Illuminate\Database\Eloquent\Attributes\ScopedBy;
use Illuminate\Database\Eloquent\Concerns\HasUlids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * @property string $id
 * @property string $email
 * @property Carbon $expires_at
 * @property Carbon $accepted_at
 */
#[ScopedBy([CurrentWedding::class])]
class Invitation extends Model
{
    /** @use HasFactory<InvitationFactory> */
    use HasFactory, HasUlids;

    protected $dateFormat = 'Y-m-d H:i:s';

    public function wedding(): BelongsTo
    {
        return $this->belongsTo(Wedding::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    protected function casts(): array
    {
        return [
            'expires_at' => 'datetime:Y-m-d H:i:s',
            'accepted_at' => 'datetime:Y-m-d H:i:s',
        ];
    }
}
