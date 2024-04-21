<?php

namespace Wubbleyou\Boundaries\Console\Commands;

use Wubbleyou\Boundaries\Helpers\RouteListGeneration;
use Illuminate\Console\Command;
use Illuminate\Filesystem\Filesystem;

class MissingRoutes extends Command {
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'wubbleyou:missing-routes';
 
    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Generates a list of routes not accounted for in Wubbleyou\Boundaries';

    /**
     * Create a new command instance.
     * @param Filesystem $files
     */
    public function __construct(Filesystem $files)
    {
        parent::__construct();
        $this->files = $files;
    }

    public function handle()
    {
        if(!file_exists(base_path() . '/app/Traits/BoundaryRouteTrait.php')) {
            return $this->error('You have not setup a BoundaryRouteTrait file');
        }

        $configuredRoutes = (new class { use \App\Traits\BoundaryRouteTrait;})->getRoutes();
        $whitelistedRoutes = (new class { use \App\Traits\BoundaryRouteTrait;})->getWhitelist();

        $routes = collect(RouteListGeneration::findMissingRoutes($configuredRoutes, $whitelistedRoutes));
        $routeNames = [];
        $log = '';

        if(!$routes->count()) {
            return $this->error("Your application doesn't have any unregistered routes.");
        }

        foreach($routes as $route) {
            $name = (!empty($route['name'])) ? $route['name'] : $route['action'];
            $log .= "'" . $name . ",\n";
            $routeNames[] = $name;
        }

        $path = base_path('storage/logs/boundary-route-list.log');
        $this->files->put($path, $log);

        $this->table(
            ['Name', 'Action'], $routes,
        );

        $this->info("List of routes available in log file: " . $path);
    }
}