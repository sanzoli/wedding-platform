<?php

namespace App\Http\Controllers;

use App\Actions\Guest\DeleteGuest;
use App\Actions\Guest\StoreGuest;
use App\Actions\Guest\UpdateGuest;
use App\Enum\Language;
use App\Http\Requests\StoreGuestRequest;
use App\Http\Requests\UpdateGuestRequest;
use App\Http\Resources\GuestGroupResource;
use App\Models\Guest;
use App\Models\GuestGroup;
use Illuminate\Http\Request;
use Inertia\Inertia;

class GuestController extends Controller
{
    public function index(Request $request)
    {
        $search = $request->input('search');
        $sort = $request->input('sort');
        $sortBy = $request->input('sortBy');

        return Inertia::render('Guests', [
            'guestGroups' => GuestGroupResource::collection(
                GuestGroup::queryFilters($search, $sort, $sortBy)
                    ->latest('guest_groups.created_at')
                    ->get()
            ),
            'filters' => request()->only('search', 'sort', 'sortBy'),
            'languages' => Language::displayList(),
        ]);
    }

    public function store(StoreGuestRequest $request, StoreGuest $action)
    {
        $action->store($request->validated());

        return back();
    }

    public function update(UpdateGuestRequest $request, Guest $guest, UpdateGuest $action)
    {
        $action->update($guest, $request->validated());

        return back();
    }

    public function destroy(Guest $guest, DeleteGuest $action)
    {
        $action->delete($guest);

        return back();
    }
}
