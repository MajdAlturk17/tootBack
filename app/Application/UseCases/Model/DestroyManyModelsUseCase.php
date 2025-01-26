<?php

namespace App\Application\UseCases\Model;

/**
 * DestroyManyModelsUseCase Class
 *
 * This abstract class extends the DeleteModelUseCase class, specializing in handling the destruction
 * (permanent deletion) of multiple model entities in the application.
 *
 * @package App\Application\UseCases\Model
 * @author Ali Monther | ali.monther97@gmail.com
 */

abstract class DestroyManyModelsUseCase extends DeleteModelUseCase
{


    /**
     * An array containing the IDs of the model entities to be destroyed.
     *
     * @var array
     */
    protected array $ids = [];


    /**
     * The columns to be considered during the destruction process. Default is 'id'.
     *
     * @var string
     */
    protected string $columns = 'id';



    /**
     * Sets the column to be considered during the destruction process.
     *
     * @param string $column The column to be used for destruction.
     */
    public function setColumn(string $column='id'): void
    {
        $this->columns = $column;
    }


    /**
     * Handles the destruction (permanent deletion) of multiple model entities.
     *
     * @param array $data An array of data relevant to the destruction process (optional).
     * @return bool|null The result of the destruction process (true on success, false on failure, or null if not applicable).
     */
    function handle(array $data = []): ?bool
    {

        return $this->repository->destroyMany($this->ids, $this->columns,$this->conditions);
    }


    /**
     * Sets the array of IDs for the model entities to be destroyed.
     *
     * @param array $ids An array of model entity IDs.
     */
    public  function setIds(array $ids): void
    {
        $this->ids = $ids;
    }

    public function prepareData(array $data = []): array
    {
        return $data;
    }

    public function setRepository($repository): void
    {
        $this->repository = $repository;
    }
    
}
