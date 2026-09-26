<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\StoreInvitationRequest;
use App\Models\Invitation;

class InvitationController extends Controller
{
    public function store(StoreInvitationRequest $request)
    {
        Invitation::create([
            'type' => $request->type,
            'guest_id' => $request->guest_id,
            'default_language' => $request->default_language,
        ]);

        return back()->with('success', 'Invitation created.');
    }
}
