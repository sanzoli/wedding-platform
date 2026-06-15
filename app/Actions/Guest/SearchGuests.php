<?php

namespace App\Actions\Guest;

use App\Models\Guest;
use App\Models\GuestGroup;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Collection;

class SearchGuests
{
    public function execute(array $filters): Collection
    {
        $search = $filters['search'] ?? null;
        $sortBy = $filters['sortBy'] ?? null;
        $sort = $filters['sort'] ?? null;

        return Guest::when($search, fn (Builder $query) => $query
            ->whereLike('first_name', '%'.$search.'%')
            ->orWhereLike('last_name', '%'.$search.'%')
            ->orWhereLike('mobile', '%'.$search.'%')
            ->orWhereRaw("CONCAT(first_name, ' ', last_name) LIKE ?", ["%{$search}%"])
        )->when($sortBy, fn (Builder $query) => $query->orderBy($sortBy, $sort))
            ->latest()
            ->orderBy('group_id')
            ->orderBy('is_primary')
            ->get()
            ->mapToGroups(fn (Guest $guest) => [$guest->group_id => $guest])
            ->map(function (Collection $guests, int $groupId) {
                return GuestGroup::make([
                    'id' => $groupId,
                    'guests' => $guests,
                    'primary' => $guests->where(fn (Guest $guest) => $guest->is_primary),
                    'companions' => $guests->where(fn (Guest $guest) => ! $guest->is_primary),
                ]);
            });
    }
}
