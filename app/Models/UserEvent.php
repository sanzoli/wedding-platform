<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphTo;

/**
 * @method static create(array $array)
 */
class UserEvent extends Model
{
    protected $guarded = [];

    public function creator(): MorphTo
    {
        return $this->morphTo();
    }

    protected function casts(): array
    {
        return [
            'settings' => 'array',
        ];
    }
}
