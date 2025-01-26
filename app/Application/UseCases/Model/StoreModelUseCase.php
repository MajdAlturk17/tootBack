<?php


/**
 * StoreModelUseCase Class
 *
 * @package App\Application\UseCases\Model
 * @author Ali Monther | ali.monther97@gmail.com
 */
namespace App\Application\UseCases\Model;

use Illuminate\Database\Eloquent\Model;

/**
 * Abstract class representing a use case to store a model in the database.
 * This class extends CreateModelUseCase and provides a specific implementation for the handle method.
 */
abstract class StoreModelUseCase extends CreateModelUseCase
{

    /**
     * Handle the execution of the use case by creating and storing the model in the database.
     *
     * @param array $data Prepared data for creating the model.
     * @return Model The created model.
     */
    public function handle(array $data = []): mixed
    {
        // Call the create method of the repository to create and store the model in the database.
        return $this->repository->create($data);
    }

}
