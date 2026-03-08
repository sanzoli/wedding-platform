<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreInvitationRequest extends FormRequest
{
    protected $stopOnFirstFailure = true;

    public function rules(): array
    {
        return [
            'email' => 'nullable|email',
            'expires_at' => 'bail|nullable|date|after:now',
        ];
    }
}
