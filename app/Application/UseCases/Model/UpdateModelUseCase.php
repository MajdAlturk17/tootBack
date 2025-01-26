<?php

/**
 * UpdateModelUseCase Class
 *
 * @package App\Application\UseCases\Model
 * @author Ali Monther | ali.monther97@gmail.com
 */
namespace App\Application\UseCases\Model;


/**
 * Abstract class representing a use case to update a model in the database.
 * This class extends EditModelUseCase and provides a specific implementation for the handle method.
 */
abstract class UpdateModelUseCase extends EditModelUseCase
{

    /**
     * Handle the execution of the use case by updating the model in the database.
     *
     * @param array $data Prepared data for updating the model.
     * @return bool Returns true if the updating is successful, false otherwise.
     */
    public function handle(array $data = []): bool
    {

        // Call the update method of the repository to update the model in the database.
        return $this->repository->update($data , $this->conditions);
    }
}
