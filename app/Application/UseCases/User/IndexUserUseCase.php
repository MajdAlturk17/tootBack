<?php

namespace App\Application\UseCases\User;

use App\Application\UseCases\Model\IndexModelUseCase;
use App\Infrastructure\Repositories\User\UserRepository;

class IndexUserUseCase extends IndexModelUseCase
{
    public function __construct(UserRepository $repository)
    {
        parent::__construct( $repository);
    }
}
