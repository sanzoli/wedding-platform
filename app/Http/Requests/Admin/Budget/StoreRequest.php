<?php

namespace App\Http\Requests\Admin\Budget;

use Illuminate\Foundation\Http\FormRequest;

class StoreRequest extends FormRequest
{
    protected $stopOnFirstFailure = true;

    public function rules(): array
    {
        return [
            'name' => 'required|string|max:60',
            'draft' => 'boolean',
        ];
    }
}
