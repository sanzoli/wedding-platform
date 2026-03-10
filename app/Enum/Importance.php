<?php

namespace App\Enum;

enum Importance
{
    case MustHave;
    case High;
    case Normal;
    case Low;

    public static function names(): array
    {
        return array_column(self::cases(), 'name');
    }
}
