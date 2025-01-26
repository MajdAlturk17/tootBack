<?php

namespace App\Traits;


/**
 * Trait AddDataToType
 *
 * This trait provides methods to add columns to arrays and models, allowing for dynamic data addition.
 *
 * @package App\Traits
 * @author Ali Monther | ali.monther97@gmail.com
 */

trait AddDataToType
{

    /**
     * Add multiple columns to each array in the given data.
     *
     * @param array $data
     * @param array $columns
     * @return array
     */
    public function addColumnsToArrays(array $data, array $columns): array
    {
        return array_map(function ($item) use ($columns) {
            foreach ($columns as $columnName => $columnValue) {
                $item[$columnName] = $columnValue;
            }
            return $item;
        }, $data);
    }

    /**
     * Add a single column to each array in the given data.
     *
     * @param array $data
     * @param string $columnName
     * @param mixed $columnValue
     * @return array
     */
    public function addColumnToArray(array $data, string $columnName, mixed $columnValue): array
    {
        return array_map(function ($item) use ($columnName, $columnValue) {
            $item[$columnName] = $columnValue;
            return $item;
        }, $data);
    }

    /**
     * Add a single column to the model data.
     *
     * @param array $data
     * @param string $columnName
     * @param mixed $columnValue
     * @return array
     */
    public function addColumnToModel(array $data, string $columnName, mixed $columnValue): array
    {
        $data[$columnName] = $columnValue;
        return $data;
    }

    /**
     * Add columns with specific values to each array in the given data.
     *
     * @param array  $data
     * @param array  $columns
     * @param string $key
     * @return array
     */
    public function addColumnsToArraysWithKey(array $data, array $columns, string $key): array
    {
        return array_map(function ($item) use ($columns, $key) {
            $newItem = [
                $key => $item,
            ];

            foreach ($columns as $columnName => $columnValue) {
                $newItem[$columnName] = $columnValue;
            }

            return $newItem;
        }, $data);
    }

    /**
     * @param $array
     * @return array[]
     */
    public function segregateItems($array): array
    {
        $itemsToInsert = [];
        $itemsToUpdate = [];

        foreach ($array as $item) {
            if (isset($item['id'])) {
                $itemsToUpdate[] = $item;
            } else {
                $itemsToInsert[] = $item;
            }
        }

        return [
            'items_to_insert' => $itemsToInsert,
            'items_to_update' => $itemsToUpdate
        ];
    }

}
