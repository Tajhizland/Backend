<?php

namespace App\Providers;

use Illuminate\Support\Facades\DB;
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
        // migrate:fresh / migrate:refresh / db:wipe drop every table. The schema
        // baseline makes them look harmless, so block them outside local dev.
        DB::prohibitDestructiveCommands($this->app->isProduction());
    }
}
