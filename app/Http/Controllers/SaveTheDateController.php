<?php

namespace App\Http\Controllers;

use App\Enum\InvitationResponse;
use App\Enum\InvitationType;
use App\Enum\Language;
use App\Models\Invitation;
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

        return Inertia::render('SaveTheDate', [
            'currentGuest' => $invitation->guest,
            'guestGroup' => $invitation->guest->group->guests,
            'lang' => $lang,
            'languages' => Language::displayList(),
            'options' => InvitationResponse::options(),
        ]);
    }
}
