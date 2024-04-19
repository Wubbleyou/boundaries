<?php

namespace Wubbleyou\Boundaries\Console\Commands;

class GenerateRouteTrait extends StubCommand {
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'wubbleyou:generate-route-trait';
 
    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Generates Wubbleyou\Boundaries route trait';

    /**
     * Where the generated file is saved.
     *
     * @var string
     */
    protected $fileLocation = 'app/Traits';

    /**
     * The filename to save the generated file as.
     *
     * @var string
     */
    protected $fileName = 'BoundaryRouteTrait';

    /**
     * The stub filename to base the generated file from.
     *
     * @var string
     */
    protected $stubFileName = 'BoundaryRouteTrait';

    public function handle() {
        if($this->generateStub())
            return $this->info("Generated route trait: " . $this->path . '/' . $this->fileName . '.php');
    }
}