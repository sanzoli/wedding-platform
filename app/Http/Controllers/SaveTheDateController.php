<?php

namespace App\Http\Controllers;

use App\Enum\InvitationType;
use App\Http\Requests\SaveInvitationResponseRequest;
use App\Http\Resources\InvitationCollection;
use App\Models\Invitation;
use Carbon\Carbon;
use Illuminate\Support\Facades\App;
use Inertia\Inertia;

class SaveTheDateController extends Controller
{
    public function view(Invitation $invitation)
    {
        if ($invitation->type !== InvitationType::SaveTheDate) {
            abort(403);
        }

        $language = request()->query('language', $invitation->default_language?->value ?? 'es');

        App::setLocale($language);
        syncLangFiles(['app', 'save_the_date']);

        return Inertia::render('SaveTheDate', [
            'id' => $invitation->id,
            'currentGuest' => $invitation->guest,
            'invitations' => new InvitationCollection(
                $invitation->guest->group->saveTheDates()->get()
            ),
            'language' => $language,
            'date' => Carbon::make('April 8, 2027')->toFormattedDateString(),
            'location' => 'Maringá, Brasil',
            'coupleNames' => 'Lauana & David',
        ]);
    }

    public function response(Invitation $invitation, SaveInvitationResponseRequest $request)
    {
        $invitation->update(['response' => $request->input('response')]);

        return back();
    }
}
