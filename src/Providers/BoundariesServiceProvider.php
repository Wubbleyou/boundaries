<?php

namespace Wubbleyou\Boundaries\Providers;

use Wubbleyou\Boundaries\Console\Commands\GenerateTests;
use Wubbleyou\Boundaries\Console\Commands\GenerateRule;
use Wubbleyou\Boundaries\Console\Commands\GenerateMiddlewareTest;
use Wubbleyou\Boundaries\Console\Commands\GeneratePolicyTest;
use Wubbleyou\Boundaries\Console\Commands\GenerateRouteTrait;
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
                GenerateMiddlewareTest::class,
                GeneratePolicyTest::class,
                GenerateRouteTrait::class,
                GenerateTests::class,
                GenerateRule::class,
            ]);
        }
    }
}