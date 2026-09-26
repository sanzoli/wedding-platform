<?php

namespace App\Http\Requests\Admin\Budget;

use Illuminate\Foundation\Http\FormRequest;

class UpdateRequest extends FormRequest
{
    protected $stopOnFirstFailure = true;

    public function rules(): array
    {
        return [
            'name' => 'string|max:60',
            'draft' => 'boolean',
        ];
    }
}
