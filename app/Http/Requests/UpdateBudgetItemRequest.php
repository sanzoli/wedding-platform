<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateBudgetItemRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'name' => 'string|max:80',
            'expected_amount' => 'bail|nullable|numeric|decimal:0,2|between:0,9999999999999999'
        ];
    }
}
