<?php

namespace App\Console\Commands\FilesCommands\DTO;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;

class MakeUpdateDTOCommand extends Command
{
    protected $signature = 'make:update-dto {name}';
    protected $description = 'Generate Update DTO file';

    public function handle(): void
    {
        $repositoryName = $this->argument('name');

        // Generate the repository file path
        $repositoryPath =  app_path("Application/DTOs/{$repositoryName}/Update{$repositoryName}DTO.php");

        // Create the repository directory if it doesn't exist
        File::makeDirectory(dirname($repositoryPath), 0755, true, true);

        // Generate the repository file content
        $repositoryContent = $this->generateUseCaseContent($repositoryName);

        // Save the content to the repository file, overwriting if it already exists
        File::put($repositoryPath, $repositoryContent);

        $this->info("DTO '$repositoryName'generated successfully!");
    }


    private function generateUseCaseContent(string $repositoryName): string
    {
        return "<?php

namespace App\Application\DTOs\\$repositoryName;

use App\Application\DTOs\DTO;

class Update{$repositoryName}DTO extends DTO
{

    public static function fromRequest(\$request): array
    {
        return [];
    }

}
";

    }
}
