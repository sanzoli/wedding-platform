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
 * @property string $email
 * @property Carbon $expires_at
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
}
