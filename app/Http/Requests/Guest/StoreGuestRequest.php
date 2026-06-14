<?php

namespace App\Http\Requests\Guest;

use App\Enum\Language;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreGuestRequest extends FormRequest
{
    protected $stopOnFirstFailure = true;

    public function rules(): array
    {
        return [
            'first_name' => [
                'nullable',
                'string',
                Rule::requiredIf(fn () => ! request('group_id') && ! request('last_name')),
            ],
            'last_name' => [
                'nullable',
                'string',
                Rule::requiredIf(fn () => ! request('group_id') && ! request('first_name')),
            ],
            'lang' => ['nullable', Rule::in(array_column(Language::cases(), 'value'))],
            'mobile' => 'nullable|regex:/^\+\d{9,}$/',
            'group_id' => 'nullable|exists:App\Models\GuestGroup,id',
        ];
    }
}
