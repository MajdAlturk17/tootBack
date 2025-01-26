<?php

/**
 * DestroyModelUseCase Class
 *
 * This abstract class extends the DeleteModelUseCase class, specializing in handling the destruction
 * (permanent deletion) of model entities in the application.
 *
 * @package App\Application\UseCases\Model
 * @author Ali Monther | ali.monther97@gmail.com
 */


namespace App\Application\UseCases\Model;

abstract class DestroyModelUseCase extends DeleteModelUseCase
{

    /**
     * Handles the destruction (permanent deletion) of the model entity.
     *
     * @param array $data An array of data relevant to the destruction process (optional).
     * @return bool|null The result of the destruction process (true on success, false on failure, or null if not applicable).
     */

    function handle(array $data = []): ?bool
    {
        $this->setConditions($data);


        return $this->repository->destroy($this->conditions);
    }

    /**
     * @param array $data
     * @return void
     */
    public abstract function setConditions(array $data): void;
}
