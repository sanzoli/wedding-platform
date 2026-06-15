<?php

namespace App\Enum;

enum Language: string
{
    case English = 'en';
    case Spanish = 'es';
    case Portuguese = 'pt';

    public function flag(): string
    {
        return match ($this) {
            self::English => '🇺🇸',
            self::Spanish => '🇪🇸',
            self::Portuguese => '🇧🇷',
        };
    }

    public static function values(): array
    {
        return array_column(Language::cases(), 'value');
    }

    public static function displayList(): array
    {
        return collect(self::cases())
            ->mapWithKeys(fn (self $item) => [
                $item->value => [
                    'label' => $item->name,
                    'value' => $item->value,
                    'flag' => $item->flag(),
                ],
            ])->toArray();
    }
}
