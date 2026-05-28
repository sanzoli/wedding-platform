<?php

namespace App\Models;

use Database\Factories\GuestGroupFactory;
use Illuminate\Database\Eloquent\Attributes\Scope;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Query\JoinClause;

/**
 * @method static self create(array $array)
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

    public function primaryGuest(): Guest
    {
        return $this->guests()->where('is_primary', true)->first();
    }

    #[Scope]
    protected function queryFilters(Builder $query, ?string $search, ?string $sort, ?string $sortBy): Builder
    {
        return $query->when($search || $sortBy, fn (Builder $query) => $query
            ->join('guests', fn (JoinClause $join) => $join
                ->on('guests.group_id', '=', 'guest_groups.id')
                ->where('guests.is_primary', '=', true)
            )
            ->when($search, fn (Builder $query) => $query
                ->whereLike('first_name', '%'.$search.'%')
                ->orWhereLike('last_name', '%'.$search.'%')
                ->orWhereLike('mobile', '%'.$search.'%')
                ->orWhereRaw("CONCAT(first_name, ' ', last_name) LIKE ?", ["%{$search}%"])
            )
            ->when($sortBy, fn (Builder $query) => $query->orderBy($sortBy, $sort))
        );
    }
}
