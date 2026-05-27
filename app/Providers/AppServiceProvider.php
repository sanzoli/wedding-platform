<?php

namespace App\Providers;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\ServiceProvider;
use Inertia\Inertia;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        Inertia::macro(
            'mergeShared',
            fn (string $key, array $value) => array_merge(Inertia::getShared($key), $value)
        );

        Builder::macro('orderByWhen', function (?string $column, ?string $direction) {
            return $this->when($column, fn (Builder $query) => $query
                ->orderBy($column, $direction)
            );
        });
    }
}
