<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\StoreInvitationRequest;
use App\Http\Requests\Admin\UpdateInvitationRequest;
use App\Models\Invitation;

class InvitationController extends Controller
{
    public function store(StoreInvitationRequest $request)
    {
        return Invitation::create([
            'type' => $request->type,
            'guest_id' => $request->guest_id,
            'default_language' => $request->default_language,
        ]);
    }

    /**
     * Display the specified resource.
     */
    public function show(Invitation $invitation)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateInvitationRequest $request, Invitation $invitation)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Invitation $invitation)
    {
        //
    }
}
