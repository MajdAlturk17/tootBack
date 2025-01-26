<?php

/**
 * EditModelUseCase Class
 *
 * @package App\Application\UseCases\Model
 * @author Ali Monther | ali.monther97@gmail.com
 */
namespace App\Application\UseCases\Model;


/**
 * Abstract class representing a use case to edit a model in the database.
 */
abstract class EditModelUseCase extends ModelUseCase implements ProcessModelCrud
{

    /**
     * Conditions to filter the model for editing.
     *
     * @var array
     */
    protected array $conditions = [];

    /**
     * Execute the use case with the provided data.
     *
     * @param array $data Data for editing the model.
     * @return mixed Returns true if the editing is successful, false otherwise.
     */
    public function execute(array $data = []): mixed
    {
        $data = $this->prepareData($data);

        $this->setConditions($data);

        $result = $this->handle($data);

        return $this->afterHandling($result);
    }

    /**
     * Prepare the data for the use case.
     *
     * @param array $data Data to be prepared for editing the model.
     * @return array Prepared data.
     */
    public function prepareData(array $data = []): array
    {
        // By default, return the input data. Subclasses may override this method for specific preparation.
        return $data;
    }

    /**
     * Handle the execution of the use case.
     *
     * @param array $data Prepared data for editing the model.
     * @return bool Returns true if the editing is successful, false otherwise.
     */
    public abstract function handle(array $data = []): bool;

    public abstract function setConditions(array $data): void;
}
