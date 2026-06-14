<?php

namespace App\Models;

use App\Enum\Language;
use Database\Factories\GuestFactory;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * @property int $id
 * @property string $fullName
 * @property ?string $first_name
 * @property ?string $last_name
 * @property ?Language $lang
 * @property ?string $mobile
 * @property int $group_id
 * @property bool $is_primary
 * @property GuestGroup $group
 *
 * @method static Builder when(bool $search, \Closure $param)
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
            'is_primary' => 'boolean',
        ];
    }

    protected function fullName(): Attribute
    {
        return Attribute::make(
            get: fn ($value, array $attributes) => trim($attributes['first_name'].' '.$attributes['last_name']),
        );
    }

    protected function initials(): Attribute
    {
        return Attribute::make(
            get: fn ($value, array $attributes) => strtoupper(
                substr($attributes['first_name'], 0, 1).substr($attributes['last_name'], 0, 1)
            ) ?? '?',
        );
    }
}
