<?php

/**
 * GetModelDetailsUseCase Class
 *
 * @package App\Application\UseCases\Model
 * @author Ali Monther | ali.monther97@gmail.com
 */

namespace App\Application\UseCases\Model;

use Illuminate\Database\Eloquent\Model;

/**
 * Abstract class representing a use case to get details of a model from the database.
 */
abstract class GetModelDetailsUseCase extends ModelUseCase implements ProcessModelCrud
{

    /**
     * Conditions to filter the model retrieval.
     *
     * @var array
     */
    protected array $conditions = [];

    /**
     * Relationships to eager load with the model.
     *
     * @var array
     */
    protected array $with = [];

    /**
     * Order criteria for sorting the result set.
     *
     * @var array
     */
    protected array $order = ['id' => 'desc'];

    /**
     * Columns to retrieve from the model.
     *
     * @var array
     */
    protected array $columns = ['*'];

    protected bool $withAbort = true;


    /**
     * @param array $data
     * @return void
     */
    public abstract function setConditions(array $data): void;


    /**
     * Execute the use case with the provided data.
     *
     * @param array $data Additional data for the use case.
     * @return Model|array | null Returns the retrieved model or an array with an error message.
     */
    public function execute(array $data = []): Model|array|null
    {
        $data = $this->prepareData($data);
        $this->setConditions($data);
        $result = $this->handle();
        if (!$result && $this->withAbort)
            abort(404, 'Model Not Found !!');
        return $this->afterHandling($result);

    }

    /**
     * Handle the execution of the use case.
     *
     * @param array $data
     * @return Model|array|null Returns the retrieved model or null if not found.
     */
    public function handle(array $data = []): Model|array|null
    {
           return $this->repository->findByAttributes($this->conditions , $this->with , $this->order , $this->columns,);
    }

    /**
     * Prepare the data for the use case.
     *
     * @param array $data Additional data for the use case.
     */
    public function prepareData(array $data = []): array {
        return $data;
    }
}
