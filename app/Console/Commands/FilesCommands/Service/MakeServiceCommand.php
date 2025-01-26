<?php

namespace App\Console\Commands\FilesCommands\Service;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;

class MakeServiceCommand extends Command
{
    protected $signature = 'make:module-service {name}';
    protected $description = 'Generate a service';

    public function handle(): void
    {

        $repositoryName = $this->argument('name');

        // Generate the repository file path
        $repositoryPath = app_path("Application/Services/{$repositoryName}/{$repositoryName}Service.php");

        // Create the repository directory if it doesn't exist
        File::makeDirectory(dirname($repositoryPath), 0755, true, true);

        // Generate the repository file content
        $repositoryContent = $this->generateUseCaseContent($repositoryName);

        // Save the content to the repository file, overwriting if it already exists
        File::put($repositoryPath, $repositoryContent);

        $this->info("Service '$repositoryName' generated successfully!");
    }

    private function generateUseCaseContent(string $repositoryName): string
    {
        return "<?php

namespace App\Application\Services\\$repositoryName;
use App\Application\UseCases\\$repositoryName\Destroy{$repositoryName}UseCase;
use App\Application\UseCases\\$repositoryName\Get{$repositoryName}UseCase;
use App\Application\UseCases\\$repositoryName\Index{$repositoryName}UseCase;
use App\Application\UseCases\\$repositoryName\Store{$repositoryName}UseCase;
use App\Application\UseCases\\$repositoryName\Update{$repositoryName}UseCase;
use App\Http\Resources\\$repositoryName\\{$repositoryName}Resource;
use App\Traits\PaginationHelper;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

class {$repositoryName}Service{

    use PaginationHelper;

    protected Store{$repositoryName}UseCase \$store{$repositoryName}UseCase;
    protected Update{$repositoryName}UseCase \$update{$repositoryName}UseCase;
    protected Get{$repositoryName}UseCase \$get{$repositoryName}UseCase;
    protected Destroy{$repositoryName}UseCase \$destroy{$repositoryName}UseCase;
    protected Index{$repositoryName}UseCase \$index{$repositoryName}UseCase;

    protected array \$headers = ['id', 'name'];

    public function __construct(Store{$repositoryName}UseCase      \$store{$repositoryName}UseCase,
                                Update{$repositoryName}UseCase     \$update{$repositoryName}UseCase,
                                Get{$repositoryName}UseCase        \$get{$repositoryName}UseCase,
                                Destroy{$repositoryName}UseCase    \$destroy{$repositoryName}UseCase,
                                Index{$repositoryName}UseCase     \$index{$repositoryName}UseCase)
    {
        \$this->store{$repositoryName}UseCase = \$store{$repositoryName}UseCase;
        \$this->update{$repositoryName}UseCase = \$update{$repositoryName}UseCase;
        \$this->get{$repositoryName}UseCase = \$get{$repositoryName}UseCase;
        \$this->destroy{$repositoryName}UseCase = \$destroy{$repositoryName}UseCase;
        \$this->index{$repositoryName}UseCase = \$index{$repositoryName}UseCase;
    }

    public function index(array \$data): AnonymousResourceCollection|array
    {
        \$result = \$this->index{$repositoryName}UseCase->execute(\$data);

        return !isset(\$data['per_page']) ? {$repositoryName}Resource::collection(\$result) :
            \$this->getPaginatedData(\$this->headers,\$result, {$repositoryName}Resource::class);
    }

     /**
     * @param array \$data
     * @return {$repositoryName}Resource
     */
    public function store(array \$data): {$repositoryName}Resource
    {
        \$result = \$this->store{$repositoryName}UseCase->execute(\$data);
        return new {$repositoryName}Resource(\$result);
    }

    /**
     * @param array \$data
     * @param int \$id
     * @return bool
     */
    public function update(array \$data, int \$id): bool
    {
        \$data['id'] = \$id;
        return \$this->update{$repositoryName}UseCase->execute(\$data);
    }

    /**
     * @param int \$id
     * @return {$repositoryName}Resource
     */
    public function show(int \$id): {$repositoryName}Resource
    {
        \$source = \$this->get{$repositoryName}UseCase->execute(['id' => \$id]);
        return new {$repositoryName}Resource(\$source);
    }

    /**
     * @param int \$id
     * @return mixed
     */
    public function destroy(int \$id): mixed
    {
        return \$this->destroy{$repositoryName}UseCase->execute(['id' => \$id]);
    }
}
";

    }
}
