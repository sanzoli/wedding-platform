<?php

namespace App\Models;

use Database\Factories\GuestGroupFactory;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * @method static self create(array $array)
 * @method static self make(array $array)
 */
class GuestGroup extends Model
{
    /** @use HasFactory<GuestGroupFactory> */
    use HasFactory;

    protected $guarded = [];

    protected $with = ['guests'];

    public function guests(): HasMany
    {
        return $this->hasMany(Guest::class, 'group_id');
    }

    public function primary(): Builder|HasMany
    {
        return $this->guests()->withAttributes(['is_primary' => true]);
    }

    public function companies(): Builder|HasMany
    {
        return $this->guests()->withAttributes(['is_primary' => false]);
    }

    public static function selectableOptions(): array
    {
        return GuestGroup::with('primary')
            ->get()
            ->sortBy(fn ($group) => $group->primary->first()->full_name)
            ->values()
            ->map(fn ($group) => [
                'id' => $group->id,
                'full_name' => $group->primary->first()->full_name,
                'initials' => $group->primary->first()->initials,
            ])->toArray();
    }
}
