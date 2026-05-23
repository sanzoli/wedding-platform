<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreGuestRequest;
use App\Http\Requests\UpdateGuestRequest;
use App\Models\Guest;
use Inertia\Inertia;

class GuestController extends Controller
{
    public function index()
    {
        return Inertia::render('Guests', ['guests' => Guest::all()->toResourceCollection()]);
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
