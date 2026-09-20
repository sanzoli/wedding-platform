<?php

namespace App\Http\Controllers;

use App\Enum\InvitationResponse;
use App\Enum\InvitationType;
use App\Enum\Language;
use App\Http\Requests\SaveInvitationResponseRequest;
use App\Http\Resources\InvitationCollection;
use App\Http\Resources\InvitationResource;
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

        $lang = request()->query('lang', $invitation->default_language?->value ?? 'es');
        App::setLocale($lang);
        syncLangFiles(['app', 'save_the_date']);

        return Inertia::render('SaveTheDate', [
            'currentGuest' => $invitation->guest,
            'invitations' => new InvitationCollection(
                $invitation->guest->group->saveTheDates()->get()
            ),
            'guestGroup' => $invitation->guest->group->guests,
            'language' => $lang,
            'languages' => Language::displayList(),
            'options' => InvitationResponse::options(),
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
