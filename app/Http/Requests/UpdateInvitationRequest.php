<?php

namespace App\Http\Requests;

use App\Models\Invitation;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;

/**
 * @property Invitation $invitation
 */
class UpdateInvitationRequest extends FormRequest
{
    protected $stopOnFirstFailure = true;

    public function rules(): array
    {
        return [
            'email' => 'nullable|email',
            'expires_at' => 'bail|nullable|date|after:now',
        ];
    }

    public function after(): array
    {
        return [
            fn (Validator $validator) => $validator->errors()->isEmpty() && $this->invitation->accepted_at
                ? $validator->errors()->add('invitation', 'It can not update an accepted invitation')
                : null,
            fn (Validator $validator) => $validator->errors()->isEmpty() && $this->invitation->expires_at->lt(now())
                ? $validator->errors()->add('invitation', 'It can not update a expired invitation')
                : null,
        ];
    }
}
