<?php

namespace App\Console\Commands\FilesCommands\Controller;

use Illuminate\Console\Command;

use Illuminate\Support\Facades\File;

class MakeControllerCommand extends Command
{
    protected $signature = 'make:module-controller {name}';
    protected $description = 'Generate a service';

    public function handle(): void
    {

        $repositoryName = $this->argument('name');

        // Generate the repository file path
        $repositoryPath = app_path("Http/Controllers/{$repositoryName}/{$repositoryName}Controller.php");

        // Create the repository directory if it doesn't exist
        File::makeDirectory(dirname($repositoryPath), 0755, true, true);

        // Generate the repository file content
        $repositoryContent = $this->generateUseCaseContent($repositoryName);

        // Save the content to the repository file, overwriting if it already exists
        File::put($repositoryPath, $repositoryContent);

        $this->info("Controller '$repositoryName' generated successfully!");
    }

    private function generateUseCaseContent(string $repositoryName): string
    {
        return "<?php

namespace App\Http\Controllers\\$repositoryName;

use App\Http\Controllers\Controller;
use App\Helpers\ApiResponse;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use App\Http\Requests\\$repositoryName\Store{$repositoryName}Request;
use App\Http\Requests\\$repositoryName\Update{$repositoryName}Request;
use App\Application\Services\\$repositoryName\\{$repositoryName}Service;
use App\Application\DTOs\\$repositoryName\Store{$repositoryName}DTO;
use App\Application\DTOs\\$repositoryName\Update{$repositoryName}DTO;

class {$repositoryName}Controller extends Controller
{

    protected {$repositoryName}Service \$service;

    public function __construct({$repositoryName}Service \$service)
    {
        \$this->service = \$service;
    }

    /**
     * @param Request \$request
     * @return JsonResponse
     */
    public function index(Request \$request): JsonResponse
    {
        \$result = \$this->service->index(\$request->all());
        return ApiResponse::success(\$result);
    }

    /**
     * @param Store{$repositoryName}Request \$request
     * @return JsonResponse
     */
    public function store(Store{$repositoryName}Request \$request): JsonResponse
    {
        \$result = \$this->service->store(Store{$repositoryName}DTO::fromRequest(\$request->validated()));
        return ApiResponse::success(\$result);
    }

    /**
     * @param Update{$repositoryName}Request \$request
     * @param int \$id
     * @return JsonResponse
     */
    public function update(Update{$repositoryName}Request \$request, int \$id): JsonResponse
    {
        \$data = \$this->service->update(Update{$repositoryName}DTO::fromRequest(\$request->validated()), \$id);
        return ApiResponse::success(\$data);
    }

     /**
     * @param int \$id
     * @return JsonResponse
     */
    public function show(int \$id): JsonResponse
    {
        \$data = \$this->service->show(\$id);
        return ApiResponse::success(\$data);
    }

    /**
     * @param int \$id
     * @return JsonResponse
     */
    public function destroy(int \$id): JsonResponse
    {
        \$data = \$this->service->destroy(\$id);
        return ApiResponse::success(\$data);
    }

}
";

    }
}
