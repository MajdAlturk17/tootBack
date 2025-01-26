<?php

namespace App\Application\UseCases\User;

use App\Application\UseCases\Model\StoreModelUseCase;
use App\Infrastructure\Repositories\User\UserRepository;

class StoreUserUseCase extends StoreModelUseCase
{
    public function __construct(UserRepository $repository)
    {
        parent::__construct( $repository);
    }

}
