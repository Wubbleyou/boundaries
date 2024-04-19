<?php

namespace Wubbleyou\Boundaries\Console\Commands;

class GenerateRule extends StubCommand {
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'wubbleyou:generate-rule {rule}';
 
    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Generates Wubbleyou\Boundaries boundary rule';

    /**
     * Where the generated file is saved.
     *
     * @var string
     */
    protected $fileLocation = 'app/BoundaryRules';

    /**
     * The stub filename to base the generated file from.
     *
     * @var string
     */
    protected $stubFileName = 'RuleStub';

    public function handle() {
        $this->fileName = $this->argument('rule');
        $this->stubVariables = [
            'CLASS_NAME' => $this->fileName,
        ];

        if($this->generateStub())
            return $this->info("Generated boundary rule: " . $this->path . '/' . $this->fileName . '.php');
        
        $this->error("You have already generated a boundary rule with that filename: " . $this->fileName . '.php');
    }
}