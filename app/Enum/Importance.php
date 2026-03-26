<?php

namespace App\Enum;

use Illuminate\Support\Str;

enum Importance
{
    case Low;
    case Normal;
    case High;
    case MustHave;

    public static function names(): array
    {
        return array_column(self::cases(), 'name');
    }

    public static function options(): array
    {
        return collect(self::cases())
            ->mapWithKeys(fn (self $item) => [$item->name => $item->trans()])
            ->toArray();
    }

    public function trans(): string
    {
        return trans('budget.importance.'. Str::kebab($this->name));
    }
}
