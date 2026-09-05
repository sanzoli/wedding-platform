<?php

namespace App\Models;

use Database\Factories\GuestGroupFactory;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Support\Collection;

/**
 * @method static self create(array $array)
 * @method static self make(array $array)
 *
 * @property int $id
 * @property Collection<Guest> $guests
 * @property Guest $primary
 * @property Collection<Guest> $companions
 */
class GuestGroup extends Model
{
    /** @use HasFactory<GuestGroupFactory> */
    use HasFactory;

    protected $guarded = [];

    protected $with = ['primary'];

    public function guests(): HasMany
    {
        return $this->hasMany(Guest::class, 'group_id');
    }

    public function primary(): HasOne
    {
        return $this->hasOne(Guest::class, 'group_id_for_unique');
    }

    public function companions(): Builder|HasMany
    {
        return $this->guests()->withAttributes(['is_primary' => false]);
    }

    public static function selectableOptions(): array
    {
        return GuestGroup::all()
            ->sortBy(fn (self $group) => $group->primary->full_name)
            ->values()
            ->map(fn (self $group) => [
                'id' => $group->id,
                'full_name' => $group->primary->full_name,
                'initials' => $group->primary->initials,
            ])->toArray();
    }
}
