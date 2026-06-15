<?php

namespace App\Http\Requests\Guest;

use App\Models\Guest;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Validator;

/**
 * @property Guest $guest
 */
class DeleteGuestRequest extends FormRequest
{
    public function after(): array
    {
        return [
            function (Validator $validator) {
                if ($this->guest->is_primary && $this->guest->group->companions()->exists()) {
                    $validator->errors()->add('guest', 'It cannot delete guest with companions.');
                }
            },
        ];
    }
}
