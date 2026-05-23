<?php

namespace App\Http\Controllers;

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
            'guests' => GuestResource::collection(Guest::quickSearch($request->input('search'))->get()),
            'filters' => request()->only('search'),
        ]);
    }

    public function store(StoreGuestRequest $request)
    {
        //
    }

    public function update(UpdateGuestRequest $request, Guest $guest)
    {
        //
    }

    public function destroy(Guest $guest)
    {
        //
    }
}
