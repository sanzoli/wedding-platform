<?php

namespace App\Http\Requests;

use App\Actions\Fortify\PasswordValidationRules;
use App\Models\Invitation;
use App\Models\User;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

/**
 * @property Invitation $invitation
 */
class AcceptInvitationRequest extends FormRequest
{
    use PasswordValidationRules;

    protected $stopOnFirstFailure = true;

    public function rules(): array
    {
        return [
            'name' => ['required', 'string', 'max:255'],
            'email' => [
                'bail',
                'required',
                'string',
                'email',
                'max:255',
                Rule::unique(User::class),
            ],
            'password' => $this->passwordRules(),
        ];
    }

    public function after(): array
    {
        return [
            fn (Validator $validator) => $validator->errors()->isEmpty() && $this->invitation->accepted_at
                ? $validator->errors()->add('invitation', 'Invitation is already accepted.')
                : null,
            fn (Validator $validator) => $validator->errors()->isEmpty() && $this->invitation->expires_at->lt(now())
                ? $validator->errors()->add('invitation', 'It can not accept an expired invitation.')
                : null,
        ];
    }
}
