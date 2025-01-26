<?php

namespace App\Console\Commands\FilesCommands\Model;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;

class MakeModelCommand extends Command
{
    protected $signature = 'make:module-model {name}';
    protected $description = 'Generate model file';

    public function handle(): void
    {
        $repositoryName = $this->argument('name');

        // Generate the repository file path
        $repositoryPath =  app_path("Infrastructure/Models/{$repositoryName}/{$repositoryName}.php");

        // Create the repository directory if it doesn't exist
        File::makeDirectory(dirname($repositoryPath), 0755, true, true);

        // Generate the repository file content
        $repositoryContent = $this->generateUseCaseContent($repositoryName);

        // Save the content to the repository file, overwriting if it already exists
        File::put($repositoryPath, $repositoryContent);

        $this->info("Request '$repositoryName'generated successfully!");
    }


    private function generateUseCaseContent(string $repositoryName): string
    {
        return "<?php

namespace App\Infrastructure\Models\\$repositoryName;

use App\Infrastructure\Models\BaseModel;

class {$repositoryName} extends BaseModel
{

   protected \$guarded = ['id'];

}
";

    }
}
