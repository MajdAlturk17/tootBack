<?php

namespace App\Console\Commands\FilesCommands\UseCases;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;

class MakeIndexUseCaseCommand extends Command
{
    protected $signature = 'make:index-use-case {name}';
    protected $description = 'Generate destroy use case file';

    public function handle(): void
    {
        $repositoryName = $this->argument('name');

        // Generate the repository file path
        $repositoryPath = app_path("Application/UseCases/{$repositoryName}/Index{$repositoryName}UseCase.php");

        // Create the repository directory if it doesn't exist
        File::makeDirectory(dirname($repositoryPath), 0755, true, true);

        // Generate the repository file content
        $repositoryContent = $this->generateUseCaseContent($repositoryName);

        // Save the content to the repository file, overwriting if it already exists
        File::put($repositoryPath, $repositoryContent);

        $this->info("Index '$repositoryName'UseCase generated successfully!");
    }


    private function generateUseCaseContent(string $repositoryName): string
    {
        return "<?php

namespace App\Application\UseCases\\{$repositoryName};

use App\Application\UseCases\Model\IndexModelUseCase;
use App\Infrastructure\Repositories\\{$repositoryName}\\{$repositoryName}Repository;

class Index{$repositoryName}UseCase extends IndexModelUseCase
{
    public function __construct({$repositoryName}Repository \$repository)
    {
        parent::__construct( \$repository);
    }
}
";

    }
}
