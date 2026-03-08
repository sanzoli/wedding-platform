<?php

namespace App\Models;

use Database\Factories\WeddingFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Wedding extends Model
{
    /** @use HasFactory<WeddingFactory> */
    use HasFactory;

    public function invitations(): HasMany
    {
        return $this->hasMany(Invitation::class);
    }
}
