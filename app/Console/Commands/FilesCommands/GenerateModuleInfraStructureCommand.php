<?php

namespace App\Console\Commands\FilesCommands;
use Illuminate\Console\Command;
use Illuminate\Support\Str;

use Illuminate\Support\Facades\Artisan;

class GenerateModuleInfraStructureCommand extends Command
{
    protected $signature = 'make:module-infra {name}';
    protected $description = 'Generate all use-case files';

    public function handle(): void
    {
        $useCaseName = $this->argument('name');

        Artisan::call('make:module-model', ['name' => $useCaseName]);
        Artisan::call('make:repository', ['name' => $useCaseName]);
        Artisan::call('make:eloquent-repository', ['name' => $useCaseName]);
        Artisan::call('make:migration', ['name' => 'create_'.Str::snake($useCaseName).'s_migration']);
        Artisan::call('make:resource', ['name' =>  "{$useCaseName}/{$useCaseName}Resource"]);

        $this->info("module InfraStructure for '$useCaseName' generated successfully!");
    }
}
