<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Library\ComJwt;
class ComJwtProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        $this->app->singleton('ComJwt', ComJwt::class);
    }
}
