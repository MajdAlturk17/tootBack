<?php

/**
 * UpsertModelUseCase Class
 *
 * @package App\Application\UseCases\Model
 * @author Ali Monther | ali.monther97@gmail.com
 */

namespace App\Application\UseCases\Model;

/**
 * Class representing a use case to upsert a model in the database.
 * This class extends EditModelUseCase and provides a specific implementation for the handle method.
 */
abstract class UpsertModelUseCase extends EditModelUseCase
{

    /**
     * Handle the execution of the use case by update or inserting the models in the database.
     *
     * @param array $data Prepared data for update or inserting the models.
     * @return bool Returns true if the update or inserting is successful, false otherwise.
     */
    public function handle(array $data = []): bool
    {
        // Call the upsert method of the repository to upsert the model in the database.

        return $this->repository->upsert($data,$this->conditions);
    }
}
