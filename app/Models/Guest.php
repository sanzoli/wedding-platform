<?php

namespace App\Models;

use App\Enum\Language;
use Database\Factories\GuestFactory;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * @property int $id
 * @property string $name
 * @property ?string $first_name
 * @property ?string $last_name
 * @property ?Language $lang
 * @property ?string $mobile
 */
class Guest extends Model
{
    /** @use HasFactory<GuestFactory> */
    use HasFactory;

    protected $guarded = [];

    protected $touches = ['group'];

    public function group(): BelongsTo
    {
        return $this->belongsTo(GuestGroup::class, 'group_id');
    }

    protected function casts(): array
    {
        return [
            'lang' => Language::class,
        ];
    }

    protected function name(): Attribute
    {
        return Attribute::make(
            get: fn ($value, array $attributes) => $attributes['first_name'].' '.$attributes['last_name'],
        );
    }
}
