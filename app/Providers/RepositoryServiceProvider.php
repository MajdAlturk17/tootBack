<?php


namespace App\Providers;

use App\Infrastructure\Models\User\User;
use App\Infrastructure\Repositories\User\EloquentUserRepository;
use App\Infrastructure\Repositories\User\UserRepository;
use Illuminate\Support\ServiceProvider;

class RepositoryServiceProvider extends ServiceProvider
{

    public function boot(): void
    {
        $this->app->bind(UserRepository::class, function () {
            return new EloquentUserRepository(new User());
        });
    }
}
