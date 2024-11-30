<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Filesystem\Filesystem;

class MakeService extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'make:service {name : The name of the service}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create a new service class';

    protected $files;

    /**
     * Create a new command instance.
     *
     * @param Filesystem $files
     * @return void
     */
    public function __construct(Filesystem $files)
    {
        parent::__construct();

        $this->files = $files;
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $serviceName = $this->argument('name');
        $servicePath = app_path('Services');

        // Parse the service name to handle nested directories
        $serviceNameParts = explode('/', $serviceName);
        $className = array_pop($serviceNameParts); // Extract the class name
        $nestedPath = $servicePath . '/' . implode('/', $serviceNameParts); // Resolve nested path

        // Ensure the directory exists
        if (!$this->files->exists($servicePath)) {
            $this->files->makeDirectory($servicePath, 0755, true);
        }

        // Ensure the nested directory exists
        if (count($serviceNameParts) > 0 && !$this->files->exists($nestedPath)) {
            $this->files->makeDirectory($nestedPath, 0755, true); // Create nested directories recursively
        }

        $filePath = $nestedPath . '/' . $className . '.php'; // Final file path

        // Check if the service file already exists
        if ($this->files->exists($filePath)) {
            $this->error("Service {$serviceName} already exists!");
            return Command::FAILURE;
        }

        // Create the service class file
        $this->files->put($filePath, $this->getStubContent($serviceName));

        $this->info("Service {$serviceName} created successfully at {$filePath}");
        return Command::SUCCESS;
    }

    /**
     * Get the content for the service class.
     *
     * @param string $serviceName
     * @return string
     */
    protected function getStubContent($serviceName)
    {
        return <<<EOT
<?php

namespace App\Services;

class {$serviceName}
{
    // Add your service methods here
}
EOT;
    }
}
