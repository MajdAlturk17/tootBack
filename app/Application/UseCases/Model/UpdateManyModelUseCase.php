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
abstract class UpdateManyModelUseCase extends UpdateModelUseCase
{

    public function setConditions(array $data): void
    {
        $this->conditions = [];
    }

    public function execute(array $data=[]): bool
    {
        $data = $this->prepareData($data);

        foreach ($data as $item){
            $this->conditions = ['id'=>$item['id']];
            unset($item['id']);
            $this->afterHandling($this->handle($item));
        }
        return true;
    }

}
