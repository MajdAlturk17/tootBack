<?php

/**
 * InsertModelUseCase Class
 *
 * @package App\Application\UseCases\Model
 * @author Ali Monther | ali.monther97@gmail.com
 */

namespace App\Application\UseCases\Model;

/**
 * Abstract class representing a use case to insert a model in the database.
 * This class extends CreateModelUseCase and provides a specific implementation for the handle method.
 */
abstract class InsertModelUseCase extends CreateModelUseCase
{


    /**
     * Handle the execution of the use case by inserting the model into the database.
     *
     * @param array $data Prepared data for creating the model.
     */
    public function handle(array $data = [])
    {
        // Call the insert method of the repository to insert the model into the database.
        return $this->repository->insert($data);
    }
}
