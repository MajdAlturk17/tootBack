<?php

namespace App\Traits;

use Illuminate\Support\Facades\Request;

/**
 * Trait SortHelper
 *
 * Provides methods to handle sorting of data arrays based on request parameters.
 *
 * @package App\Traits
 * @author Ali Monther | ali.monther97@gmail.com
 */
trait SortHelper
{
    /**
     * Prepares the sort configuration from the request or defaults.
     *
     * @param array $defaultSort Default sort configuration.
     * @param string $defaultRequestSortType Default sort type if not specified.
     * @return array|string The sort configuration.
     * @throws \Exception If invalid sort attributes are provided.
     */
    protected function prepareSort(array $defaultSort = ['id'=>'desc'], string $defaultRequestSortType = 'desc'): array|string
    {
        if (!Request::get('sorts')) return $defaultSort;
        $sorts = $this->getSort();
        try {
            return [$sorts->sort => $sorts->sort_type ?? $defaultRequestSortType];
        }
        catch (\Exception $e){
            abort(400,'Invalid sort attributes');
        }

    }


    /**
     * Prepares the sort configuration with key-value pairs from the request or defaults.
     *
     * @param array $defaultSort Default sort configuration.
     * @param string $defaultRequestSortType Default sort type if not specified.
     * @return array|string The sort configuration as key-value pairs.
     * @throws \Exception If invalid sort attributes are provided.
     */
    protected function prepareSortWithKeyValue(array $defaultSort = ['sort'=>'id','sort_type'=>'desc'], string $defaultRequestSortType = 'desc'): array|string
    {
        if (!Request::get('sorts')) return $defaultSort;
        $sorts = $this->getSort();
        try {
            return [
                'sort'=>$sorts->sort ,
                'sort_type'=> $sorts->sort_type ?? $defaultRequestSortType
            ];
        }
        catch (\Exception $e){
            abort(400,'Invalid sort attributes');
        }

    }


    /**
     * Prepares the sorted data array based on request parameters.
     *
     * @param array $data The data to be sorted.
     * @return array|string The sorted data.
     */
    protected function sortNumericallyIndexedData(array $data): array|string
    {
        $sorts = $this->getSort();

        if (is_null($sorts)) {
            return $data;
        }

        $sortKey = $sorts->sort;
        $sortType = $sorts->sort_type ?? 'desc';

        foreach ($data as &$items) {
            usort($items, function($a, $b) use ($sortKey, $sortType) {
                if (!isset($a[$sortKey]) || !isset($b[$sortKey])) {
                    return 0;
                }

                if ($sortType === 'asc') {
                    return $a[$sortKey] <=> $b[$sortKey];
                } else {
                    return $b[$sortKey] <=> $a[$sortKey];
                }
            });
        }

        return $data;
    }

    /**
     * Retrieves the sort configuration from the request.
     *
     * @return mixed The decoded sort configuration.
     */
    private function getSort(): mixed
    {
        return json_decode(Request::get('sorts'));
    }
}
