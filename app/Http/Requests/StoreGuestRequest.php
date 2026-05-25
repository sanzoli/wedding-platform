<?php

namespace App\Http\Requests;

use App\Enum\Language;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreGuestRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'first_name' => 'nullable|string',
            'last_name' => 'nullable|string',
            'lang' => ['nullable', Rule::in(array_column(Language::cases(), 'value'))],
            'mobile' => 'nullable|string',
        ];
    }
}
