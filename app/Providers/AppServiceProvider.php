<?php

namespace App\Providers;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Request;
use Illuminate\Support\ServiceProvider;

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

        Builder::macro('macroPaginate', function () {
            /** @var Builder $this */
            $perPage = Request::query('per_page', 10);

            return $this->paginate($perPage);
        });
    }
}
