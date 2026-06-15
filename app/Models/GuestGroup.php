<?php

namespace App\Models;

use Database\Factories\GuestGroupFactory;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Collection;

/**
 * @method static self create(array $array)
 * @method static self make(array $array)
 *
 * @property int $id
 * @property Collection<Guest> $guests
 * @property Collection<Guest> $primary
 * @property Collection<Guest> $companions
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

    public function companions(): Builder|HasMany
    {
        return $this->guests()->withAttributes(['is_primary' => false]);
    }

    public function primaryGuest(): Guest
    {
        return $this->primary()->first();
    }

    public static function selectableOptions(): array
    {
        return GuestGroup::with('primary:first_name,last_name')
            ->get()
            ->sortBy(fn (self $group) => $group->primaryGuest()->full_name)
            ->values()
            ->map(fn (self $group) => [
                'id' => $group->id,
                'full_name' => $group->primaryGuest()->full_name,
                'initials' => $group->primaryGuest()->initials,
            ])->toArray();
    }
}
