<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;

class MakeDTO extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'make:dto {name}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create a new Data Transfer Object (DTO)';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $name = $this->argument('name');
        $directory = app_path('DTOs');

        // Parse the DTO name to handle nested directories
        $nameParts = explode('/', $name);
        $className = array_pop($nameParts); // Get the class name (last part)

        // If no namespace parts are left, set the namespace to just 'App\DTOs'
        $namespace = 'App\\DTOs';
        if (count($nameParts) > 0) {
            // Handle subdirectories for nested namespaces
            $namespace .= '\\' . implode('\\', $nameParts);
        }

        $nestedPath = $directory . '/' . implode('/', $nameParts); // Resolve nested path

        // Ensure the DTOs directory exists
        if (!File::exists($directory)) {
            File::makeDirectory($directory, 0755, true);
        }

        // Ensure the nested directory exists
        if (count($nameParts) > 0 && !File::exists($nestedPath)) {
            File::makeDirectory($nestedPath, 0755, true); // Create directories recursively
        }

        $filePath = $nestedPath . '/' . $className . '.php'; // Final file path

        // Check if the DTO file already exists
        if (File::exists($filePath)) {
            $this->error("DTO named {$name} already exists!");
            return Command::FAILURE;
        }

        // Create the content for the DTO class
        $dtoTemplate = $this->getDTOTemplate($namespace, $className);

        // Write the file to the DTOs directory
        File::put($filePath, $dtoTemplate);

        $this->info("DTO {$name} created successfully at {$filePath}");
        return Command::SUCCESS;
    }

    /**
     * Get the DTO class template.
     *
     * @param string $namespace
     * @param string $className
     * @return string
     */
    protected function getDTOTemplate($namespace, $className)
    {
        return <<<PHP
<?php

namespace {$namespace};
use App\DTOs\BaseDTOs;

class {$className} extends BaseDTOs
{
    // Define the properties for this DTO
    // public string \$property;

    public function __construct()
    {
        // Assign values to properties
    }

    // Add any necessary methods here
}
PHP;
    }
}
