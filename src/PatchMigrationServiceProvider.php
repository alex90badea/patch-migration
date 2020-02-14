<?php

namespace AlexBadea\PatchMigration;

use AlexBadea\PatchMigration\Commands\PatchInstall;
use AlexBadea\PatchMigration\Commands\PatchStatus;
use AlexBadea\PatchMigration\Commands\PatchMake;
use AlexBadea\PatchMigration\Commands\PatchRun;
use Illuminate\Support\ServiceProvider;

class PatchMigrationServiceProvider extends ServiceProvider
{

    /**
     * Bootstrap the application services.
     *
     * @return void
     */
    public function boot()
    {
        if ($this->app->runningInConsole()) {
            $this->commands([
                PatchStatus::class,
                PatchInstall::class,
                PatchMake::class,
                PatchRun::class,
            ]);
        }
    }

    /**
     * Register the application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

}
