<?php

namespace App\Http\Controllers\Api;

use App\Actions\Invitation\CreateNewInvitation;
use App\Actions\Invitation\DeleteInvitation;
use App\Actions\Invitation\UpdateInvitation;
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

    public function store(StoreInvitationRequest $request, CreateNewInvitation $action)
    {
        return $action->create($request->validated())->toResource();
    }

    public function show(Invitation $invitation)
    {
        return $invitation->toResource();
    }

    public function update(UpdateInvitationRequest $request, Invitation $invitation, UpdateInvitation $action)
    {
        return $action->update($invitation, $request->validated())->toResource();
    }

    public function destroy(Invitation $invitation, DeleteInvitation $action)
    {
        $action->delete($invitation);

        return response()->noContent();
    }
}
