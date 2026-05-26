<?php

namespace App\Models;

use App\Enum\Language;
use Database\Factories\GuestFactory;
use Illuminate\Database\Eloquent\Attributes\Scope;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * @property int $id
 * @property ?string $first_name
 * @property ?string $last_name
 * @property ?Language $lang
 * @property ?string $mobile
 */
class Guest extends Model
{
    /** @use HasFactory<GuestFactory> */
    use HasFactory;

    protected $guarded = [];

    protected function casts(): array
    {
        return [
            'lang' => Language::class,
        ];
    }

    protected function name(): Attribute
    {
        return Attribute::make(
            get: fn ($value, array $attributes) => $attributes['first_name'].' '.$attributes['last_name'],
        );
    }

    #[Scope]
    protected function quickSearch(Builder $query, ?string $value): Builder
    {
        return $query->when($value, fn ($query) => $query
            ->whereLike('first_name', '%'.$value.'%')
            ->orWhereLike('last_name', '%'.$value.'%')
            ->orWhereLike('mobile', '%'.$value.'%')
            ->orWhereRaw("CONCAT(first_name, ' ', last_name) LIKE ?", ["%{$value}%"])
        );
    }
}
