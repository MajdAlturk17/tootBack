<?php

/**
 * ModelUseCase Class
 *
 * This abstract class serves as the base for all use cases related to models in the application. Use cases in
 * this context typically involve orchestrating interactions with the database through a repository.
 *
 * @package App\Application\UseCases\Model
 * @author Ali Monther | ali.monther97@gmail.com
 */

namespace App\Application\UseCases\Model;

use App\Infrastructure\Repositories\Base\BaseRepository;


/**
 * Class ModelUseCase
 *
 * This abstract class defines a common structure and behavior for use cases related to models in the application.
 * It enforces the use of a repository to interact with the database, ensuring a consistent approach to data access
 * across different model-related use cases.
 */
abstract class ModelUseCase
{
    /**
     * The repository instance to interact with the database.
     *
     * @var BaseRepository
     */
    protected BaseRepository $repository;

    /**
     * GetModelDetailsUseCase constructor.
     *
     * @param BaseRepository $repository The repository instance to interact with the database.
     */
    public function __construct(BaseRepository $repository)
    {
        $this->repository = $repository;
    }


    /**
     * Execute custom logic for the CRUD operation
     *
     * @param mixed $data
     * @return mixed
     */
    public function afterHandling(mixed $data): mixed
    {
        return $data;
    }
}
