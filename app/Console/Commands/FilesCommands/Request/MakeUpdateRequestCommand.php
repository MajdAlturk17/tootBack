<?php

namespace App\Console\Commands\FilesCommands\Request;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;

class MakeUpdateRequestCommand extends Command
{
    protected $signature = 'make:update-request {name}';
    protected $description = 'Generate update request';

    public function handle(): void
    {
        $repositoryName = $this->argument('name');

        // Generate the repository file path
        $repositoryPath = app_path("Http/Requests/{$repositoryName}/Update{$repositoryName}Request.php");

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

namespace App\Http\Requests\\$repositoryName;

use App\Http\Requests\BaseRequest;
use Illuminate\Contracts\Validation\ValidationRule;

class Update{$repositoryName}Request extends BaseRequest
{

    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, ValidationRule|array|string>
     */
    public function rules(): array
    {
        return [];

    }

}
";

    }
}
