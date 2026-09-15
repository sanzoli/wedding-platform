<?php

namespace App\Enum;

enum InvitationResponse: string
{
    case Yes = 'yes';
    case ProbablyYes = 'probably_yes';
    case ProbablyNo = 'probably_no';
    case No = 'no';

    public static function options(): array
    {
        return collect(self::cases())
            ->mapWithKeys(fn (self $value) => [$value->value => trans('save_the_date.options.'.$value->value)])
            ->toArray();
    }
}
