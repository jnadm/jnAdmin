<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Library\ExcelSpout;
class ExcelSpoutProvider extends ServiceProvider
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
        $this->app->singleton('ExcelSpout', ExcelSpout::class);
    }
}
