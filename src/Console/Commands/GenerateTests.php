<?php

namespace Wubbleyou\Boundaries\Console\Commands;

use Illuminate\Console\Command;
use Wubbleyou\Boundaries\Helpers\RouteListGeneration;
use Illuminate\Filesystem\Filesystem;

class GenerateTests extends Command {
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'wubbleyou:generate-tests';
 
    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Generates all Wubbleyou\Boundaries tests at once';

    public function handle()
    {
        $this->call('wubbleyou:generate-middleware-test');
        $this->call('wubbleyou:generate-policy-test');
    }
}