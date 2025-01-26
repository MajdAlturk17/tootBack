<?php

/**
 * ProcessModelCrud Interface
 *
 * @package App\Application\UseCases\Model
 * @author Ali Monther | ali.monther97@gmail.com
 */

namespace App\Application\UseCases\Model;

/**
 * Interface defining methods for processing CRUD operations on a model.
 */
interface ProcessModelCrud
{

    /**
     * Execute the CRUD operation with the provided data.
     *
     * @param array $data Additional data for the CRUD operation.
     */
    function execute(array $data = []);

    /**
     * Handle the execution of the CRUD operation.
     *
     * @param array $data Data for the CRUD operation.
     */
     public function handle(array $data = []);


    /**
     * Prepare the data for the CRUD operation.
     *
     * @param array $data Data to be prepared for the CRUD operation.
     */
     function prepareData(array $data = []);

}
