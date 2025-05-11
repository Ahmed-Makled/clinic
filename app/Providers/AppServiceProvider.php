<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Console\Commands\SeedCommand;

class AppServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->app->extend('command.seed', function () {
            return new SeedCommand($this->app['db']);
        });
    }

    public function boot(): void
    {
    }
}
