<?php

namespace App\Http\Controllers;

use App\Actions\Guest\DeleteGuest;
use App\Actions\Guest\StoreGuest;
use App\Actions\Guest\UpdateGuest;
use App\Enum\Language;
use App\Http\Requests\StoreGuestRequest;
use App\Http\Requests\UpdateGuestRequest;
use App\Http\Resources\GuestResource;
use App\Models\Guest;
use Illuminate\Http\Request;
use Inertia\Inertia;

class GuestController extends Controller
{
    public function index(Request $request)
    {
        return Inertia::render('Guests', [
            'guests' => GuestResource::collection(
                Guest::quickSearch($request->input('search'))
                    ->orderByWhen($request->input('sortBy'), $request->input('sort'))
                    ->latest()
                    ->get()
            ),
            'filters' => request()->only('search'),
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
