<?php

namespace App\Console\Commands\FilesCommands\Repository;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;

class MakeEloquentRepositoryCommand extends Command
{
    protected $signature = 'make:eloquent-repository {name}';
    protected $description = 'Generate eloquent repository';

    public function handle(): void
    {
        $repositoryName = $this->argument('name');

        // Generate the repository file path
        $repositoryPath = app_path("Infrastructure/Repositories/{$repositoryName}/Eloquent{$repositoryName}Repository.php");

        // Create the repository directory if it doesn't exist
        File::makeDirectory(dirname($repositoryPath), 0755, true, true);

        // Generate the repository file content
        $repositoryContent = "<?php

namespace App\Infrastructure\Repositories\\{$repositoryName};

use App\Infrastructure\Repositories\Base\BaseRepository;
use App\Infrastructure\Repositories\Base\EloquentBaseRepository;

class Eloquent{$repositoryName}Repository extends EloquentBaseRepository implements {$repositoryName}Repository
{

}

";

        // Save the content to the repository file, overwriting if it already exists
        File::put($repositoryPath, $repositoryContent);

        $this->info("Eloquent Repository '$repositoryName' generated successfully!");
    }
}
