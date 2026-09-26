<?php

namespace App\Http\Requests\Admin;

use App\Enum\InvitationType;
use App\Enum\Language;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreInvitationRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'guest_id' => 'required|exists:guests,id',
            'type' => ['required', Rule::in(array_column(InvitationType::cases(), 'name'))],
            'default_language' => ['nullable', Rule::in(Language::values())],
        ];
    }
}
