<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreInvitationRequest;
use App\Http\Requests\UpdateInvitationRequest;
use App\Models\Invitation;

class InvitationController extends Controller
{
    public function index()
    {
        return Invitation::paginate()->toResourceCollection();
    }

    public function store(StoreInvitationRequest $request)
    {
        //
    }

    public function show(Invitation $invitation)
    {
        //
    }

    public function update(UpdateInvitationRequest $request, Invitation $invitation)
    {
        //
    }

    public function destroy(Invitation $invitation)
    {
        //
    }
}
