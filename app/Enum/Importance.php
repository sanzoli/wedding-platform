<?php

namespace App\Enum;

enum Importance
{
    case Innegociable;
    case High;
    case Normal;
    case Low;

    public static function names(): array
    {
        return array_column(self::cases(), 'name');
    }
}
