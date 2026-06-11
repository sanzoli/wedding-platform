<?php

namespace App\Http\Requests;

use App\Models\Guest;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Validator;

/**
 * @property Guest $guest
 */
class LeaveGuestGroupRequest extends FormRequest
{
    public function after(): array
    {
        return [
            function (Validator $validator) {
                if ($this->guest->is_primary) {
                    $validator->errors()->add('guest', 'Primary guest cannot leave their group.');
                }

                if (empty($this->guest->fullName)) {
                    $validator->errors()->add('guest', 'Anonymous guest cannot leave a group.');
                }
            },
        ];
    }
}
