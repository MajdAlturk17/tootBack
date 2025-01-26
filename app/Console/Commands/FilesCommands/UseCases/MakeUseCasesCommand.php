<?php

namespace App\Console\Commands\FilesCommands\UseCases;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Artisan;

class MakeUseCasesCommand extends Command
{
    protected $signature = 'make:use-cases {name}';
    protected $description = 'Generate all use-case files';

    public function handle(): void
    {
        $useCaseName = $this->argument('name');

        Artisan::call('make:store-use-case', ['name' => $useCaseName]);
        Artisan::call('make:update-use-case', ['name' => $useCaseName]);
        Artisan::call('make:get-use-case', ['name' => $useCaseName]);
        Artisan::call('make:index-use-case', ['name' => $useCaseName]);
        Artisan::call('make:destroy-use-case', ['name' => $useCaseName]);

        $this->info("use-cases files for '$useCaseName' generated successfully!");
    }
}
