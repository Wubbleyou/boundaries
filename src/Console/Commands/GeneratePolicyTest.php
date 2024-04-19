<?php

namespace Wubbleyou\Boundaries\Console\Commands;

class GeneratePolicyTest extends StubCommand {
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'wubbleyou:generate-policy-test';
 
    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Generates Wubbleyou\Boundaries policy test';

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
    protected $fileName = 'BoundaryPolicyTest';

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
        'CLASS_NAME' => 'BoundaryPolicyTest',
        'EXTENDS_NAME' => 'BaseBoundaryPolicyTest',
    ];

    public function handle() {
        $this->call('wubbleyou:generate-route-trait');

        if($this->generateStub())
            return $this->info("Generated policy test: " . $this->path . '/' . $this->fileName . '.php');
        
        $this->error("You have already generated the policy test: " . $this->path . '/' . $this->fileName . '.php');
    }
}