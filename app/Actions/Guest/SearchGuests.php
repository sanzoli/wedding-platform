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
        $categories = $filters['categories'] ?? null;

        return Guest::when($search, fn (Builder $query) => $query
            ->whereLike('first_name', '%'.$search.'%')
            ->orWhereLike('last_name', '%'.$search.'%')
            ->orWhereLike('mobile', '%'.$search.'%')
            ->orWhereRaw("CONCAT(first_name, ' ', last_name) LIKE ?", ["%{$search}%"])
        )->when($categories, fn (Builder $query) => $query
            ->whereHas('categories', fn (Builder $q) => $q->whereIn('categories.id', $categories), '=', count($categories))
        )->when($sortBy, fn (Builder $query) => $query->orderBy($sortBy, $sort))
            ->latest()
            ->orderBy('group_id')
            ->orderBy('is_primary')
            ->get()
            ->mapToGroups(fn (Guest $guest) => [$guest->group_id => $guest])
            ->map(function (Collection $guests, int $groupId) {
                return GuestGroup::make(array_filter([
                    'id' => $groupId,
                    'guests' => $guests,
                    'primary' => $guests->firstWhere(fn (Guest $guest) => $guest->is_primary),
                    'companions' => $guests->where(fn (Guest $guest) => ! $guest->is_primary),
                ]));
            });
    }
}
