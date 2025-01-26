<?php

namespace App\Application\UseCases\User;

use App\Application\UseCases\Model\GetModelDetailsUseCase;
use App\Infrastructure\Repositories\User\UserRepository;

class GetUserUseCase extends GetModelDetailsUseCase
{
    public function __construct(UserRepository $repository)
    {
        parent::__construct( $repository);
    }

    public function setConditions(array $data): void
    {
        $this->conditions = ['id' => $data['id']];
    }
}
