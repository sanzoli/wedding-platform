<?php

namespace App\Models;

use App\Enum\InvitationResponse;
use App\Enum\InvitationType;
use App\Enum\Language;
use Database\Factories\InvitationFactory;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Concerns\HasUlids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * @property string $id
 * @property InvitationType $type
 * @property Guest $guest
 * @property Language $default_language
 */
class Invitation extends Model
{
    /** @use HasFactory<InvitationFactory> */
    use HasFactory;

    use HasUlids;

    protected $guarded = [];

    public function guest(): BelongsTo
    {
        return $this->belongsTo(Guest::class);
    }

    protected function casts(): array
    {
        return [
            'type' => InvitationType::class,
            'response' => InvitationResponse::class,
            'default_language' => Language::class,
        ];
    }

    protected function url(): Attribute
    {
        return Attribute::make(
            get: fn (mixed $value, array $attributes) => match ($this->type) {
                InvitationType::SaveTheDate => route('save-the-date.view', $attributes['id']),
                default => null
            }
        );
    }
}
