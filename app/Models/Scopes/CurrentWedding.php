<?php

namespace App\Models\Scopes;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Scope;
use Illuminate\Support\Facades\Auth;

class CurrentWedding implements Scope
{
    public function apply(Builder $builder, Model $model): void
    {
        $builder->when(Auth::user(), function (Builder $builder) {
            $builder->where('wedding_id', Auth::user()->current_wedding_id);
        });
    }
}
