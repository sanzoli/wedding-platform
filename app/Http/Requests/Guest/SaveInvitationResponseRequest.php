<?php

namespace App\Http\Requests\Guest;

use App\Enum\InvitationResponse;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class SaveInvitationResponseRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'response' => ['required', Rule::in(array_column(InvitationResponse::cases(), 'value'))],
        ];
    }
}
