<?php

namespace App\Console\Commands\FilesCommands;
use Illuminate\Console\Command;
use Illuminate\Support\Str;

use Illuminate\Support\Facades\Artisan;

class GenerateModuleFilesCommand extends Command
{
    protected $signature = 'make:module-files {name}';
    protected $description = 'Generate all use-case files';

    public function handle(): void
    {
        $useCaseName = $this->argument('name');

        Artisan::call('make:module-infra', ['name' => $useCaseName]);
        Artisan::call('make:store-request', ['name' => $useCaseName]);
        Artisan::call('make:update-request', ['name' => $useCaseName]);
        Artisan::call('make:use-cases', ['name' => $useCaseName]);
        Artisan::call('make:module-service', ['name' => $useCaseName]);
        Artisan::call('make:module-controller', ['name' => $useCaseName]);
        Artisan::call('make:store-dto', ['name' => $useCaseName]);
        Artisan::call('make:update-dto', ['name' => $useCaseName]);

        $this->info("module files for '$useCaseName' generated successfully!");
    }
}
