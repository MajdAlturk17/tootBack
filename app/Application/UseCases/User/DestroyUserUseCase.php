<?php

namespace App\Application\UseCases\User;

use App\Application\UseCases\Model\DestroyModelUseCase;
use App\Infrastructure\Repositories\User\UserRepository;

class DestroyUserUseCase extends DestroyModelUseCase
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
