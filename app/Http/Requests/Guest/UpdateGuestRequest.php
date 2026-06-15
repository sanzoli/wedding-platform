<?php

namespace App\Http\Requests\Guest;

use App\Enum\Language;
use App\Models\Guest;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

/**
 * @property Guest $guest
 */
class UpdateGuestRequest extends FormRequest
{
    protected $stopOnFirstFailure = true;

    public function rules(): array
    {
        return [
            'first_name' => [
                'nullable',
                'string',
                Rule::requiredIf(fn () => $this->guest->is_primary && ! request('last_name')),
            ],
            'last_name' => [
                'nullable',
                'string',
                Rule::requiredIf(fn () => $this->guest->is_primary && ! request('first_name')),
            ],
            'lang' => ['nullable', Rule::in(Language::values())],
            'mobile' => 'nullable|regex:/^\+\d{9,}$/',
        ];
    }
}
