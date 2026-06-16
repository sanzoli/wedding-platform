<?php

namespace App\Http\Controllers;

use App\Actions\Guest\DeleteGuest;
use App\Actions\Guest\SearchGuests;
use App\Actions\Guest\StoreGuest;
use App\Actions\Guest\UpdateGuest;
use App\Enum\Language;
use App\Http\Requests\Guest\DeleteGuestRequest;
use App\Http\Requests\Guest\StoreGuestRequest;
use App\Http\Requests\Guest\UpdateGuestRequest;
use App\Http\Resources\GuestGroupResource;
use App\Models\Guest;
use App\Models\GuestGroup;
use Inertia\Inertia;

class GuestController extends Controller
{
    public function index(SearchGuests $search)
    {
        $filters = request()->only('search', 'sort', 'sortBy');

        return Inertia::render('Guests', [
            'totalGuests' => Guest::count(),
            'totalAnonymous' => Guest::whereNull('first_name')->whereNull('last_name')->count(),
            'guestGroups' => GuestGroupResource::collection($search->execute($filters)),
            'filters' => $filters,
            'languages' => Language::displayList(),
            'selectableGroups' => GuestGroup::selectableOptions(),
        ]);
    }

    public function store(StoreGuestRequest $request, StoreGuest $storeGuest)
    {
        $storeGuest->execute($request->validated());

        return back();
    }

    public function update(UpdateGuestRequest $request, Guest $guest, UpdateGuest $updateGuest)
    {
        $updateGuest->execute($guest, $request->validated());

        return back();
    }

    public function destroy(DeleteGuestRequest $request, Guest $guest, DeleteGuest $deleteGuest)
    {
        $request->validated();
        $deleteGuest->execute($guest);

        return back();
    }
}
