<?php

namespace Wubbleyou\Boundaries\Providers;

use Wubbleyou\Boundaries\Console\Commands\GenerateTest;
use Wubbleyou\Boundaries\Console\Commands\GenerateRule;
use Wubbleyou\Boundaries\Console\Commands\GenerateRouteTrait;
use Wubbleyou\Boundaries\Console\Commands\MissingRoutes;
use Illuminate\Support\ServiceProvider;

class BoundariesServiceProvider extends ServiceProvider {
    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        if ($this->app->runningInConsole()) {
            $this->commands([
                GenerateRouteTrait::class,
                GenerateTest::class,
                GenerateRule::class,
                MissingRoutes::class,
            ]);
        }
    }
}