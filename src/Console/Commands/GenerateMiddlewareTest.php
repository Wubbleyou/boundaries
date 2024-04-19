<?php

namespace Wubbleyou\Boundaries\Console\Commands;

class GenerateMiddlewareTest extends StubCommand {
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'wubbleyou:generate-middleware-test';
 
    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Generates Wubbleyou\Boundaries middleware test';

    /**
     * Where the generated file is saved.
     *
     * @var string
     */
    protected $fileLocation = 'tests/Feature/Boundaries';

    /**
     * The filename to save the generated file as.
     *
     * @var string
     */
    protected $fileName = 'BoundaryMiddlewareTest';

    /**
     * The stub filename to base the generated file from.
     *
     * @var string
     */
    protected $stubFileName = 'TestStub';

    /**
     * The stub variables to replace within the stub file.
     *
     * @var array
     */
    protected $stubVariables = [
        'NAMESPACE' => 'App\\Tests\\Feature\\Boundaries',
        'CLASS_NAME' => 'BoundaryMiddlewareTest',
        'EXTENDS_NAME' => 'BaseBoundaryMiddlewareTest',
    ];

    public function handle() {
        $this->call('wubbleyou:generate-route-trait');
        
        if($this->generateStub())
            return $this->info("Generated middleware test: " . $this->path . '/' . $this->fileName . '.php');
        
        $this->error("You have already generated the middleware test: " . $this->path . '/' . $this->fileName . '.php');
    }
}