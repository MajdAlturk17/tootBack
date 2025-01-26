<?php

/**
 * DeleteModelUseCase Class
 *
 * This abstract class extends the ModelUseCase class and implements the ProcessModelCrud interface,
 * providing a common structure for use cases responsible for deleting model entities in the application.
 *
 * @package App\Application\UseCases\Model
 * @author Ali Monther | ali.monther97@gmail.com
 */

namespace App\Application\UseCases\Model;

abstract class DeleteModelUseCase extends ModelUseCase implements ProcessModelCrud
{
    /**
     * An array containing conditions to be used for the deletion process.
     *
     * @var array
     */
    protected array $conditions = [];


    /**
     * Executes the delete model use case by preparing the data and handling the deletion process.
     *
     * @param array $data An array of data relevant to the deletion process.
     * @return mixed The result of the deletion process.
     */
    function execute(array $data = []): mixed
    {
        $data = $this->prepareData($data);
        $result = $this->handle($data);
        return $this->afterHandling($result);
    }


    /**
     * Prepares the data for the delete model use case. This method can be overridden in derived classes
     * to manipulate or validate the input data before the deletion process.
     *
     * @param array $data An array of data relevant to the deletion process.
     * @return array The prepared data for the deletion process.
     */
    function prepareData(array $data = []): array
    {
        return $data;
    }


    /**
     * Handles the deletion process for the model entity. This method must be implemented in derived classes
     * to define the specific logic for deleting model entities.
     *
     * @param array $data An array of data relevant to the deletion process.
     * @return mixed The result of the deletion process.
     */
    abstract function handle(array $data = []): mixed;


}
