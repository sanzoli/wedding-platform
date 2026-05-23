<?php

namespace App\Models;

use App\Enum\Language;
use Database\Factories\GuestFactory;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Guest extends Model
{
    /** @use HasFactory<GuestFactory> */
    use HasFactory;

    protected function casts(): array
    {
        return [
            'lang' => Language::class,
        ];
    }

    protected function name(): Attribute
    {
        return Attribute::make(
            get: fn ($value, array $attributes) => $attributes['first_name'] . " " . $attributes['last_name'],
        );
    }
}
