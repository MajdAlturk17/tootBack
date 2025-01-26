<?php


/**
 * CreateModelUseCase Class
 *
 * @package App\Application\UseCases\Model
 * @author Ali Monther | ali.monther97@gmail.com
 */
namespace App\Application\UseCases\Model;

/**
 * Abstract class representing a use case to create a model in the database.
 */
abstract class CreateModelUseCase extends ModelUseCase implements ProcessModelCrud
{

    /**
     * Prepare the data for the use case.
     *
     * @param array $data Data to be prepared for the use case.
     * @return array Prepared data.
     */
    public function prepareData(array $data = []): array
    {
        // By default, return the input data. Subclasses may override this method for specific preparation.
        return $data;
    }

    /**
     * Execute the use case with the provided data.
     *
     * @param array $data Data for creating the model.
     */
    public function execute(array $data = []){

        $preparedData = $this->prepareData($data);

        $result = $this->handle($preparedData);

        return $this->afterHandling(['data'=>$preparedData,'result' => $result]);

    }

    /**
     * Handle the execution of the use case.
     *
     * @param array $data Prepared data for creating the model.
     */
    public abstract function handle(array $data = []);

    public function afterHandling(mixed $data): mixed
    {
        return $data['result'];
    }
}
