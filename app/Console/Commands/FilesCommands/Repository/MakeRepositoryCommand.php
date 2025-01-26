<?php

namespace App\Console\Commands\FilesCommands\Repository;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;

class MakeRepositoryCommand extends Command
{
    protected $signature = 'make:repository {name}';
    protected $description = 'Generate a repository';

    public function handle(): void
    {
        $repositoryName = $this->argument('name');

        // Generate the repository file path
        $repositoryPath = app_path("Infrastructure/Repositories/{$repositoryName}/{$repositoryName}Repository.php");

        // Create the repository directory if it doesn't exist
        File::makeDirectory(dirname($repositoryPath), 0755, true, true);

        // Generate the repository file content
        $repositoryContent = "<?php

namespace App\Infrastructure\Repositories\\{$repositoryName};

use App\Infrastructure\Repositories\Base\BaseRepository;

interface {$repositoryName}Repository extends BaseRepository
{

}

";

        // Save the content to the repository file, overwriting if it already exists
        File::put($repositoryPath, $repositoryContent);

        $this->info("Repository '$repositoryName' generated successfully!");
    }
}
