<?php

namespace App\Traits;

use Illuminate\Pagination\LengthAwarePaginator;

trait PaginationHelper
{

    public function getPaginatedData(array $headers, $data, $resource, $includeIndex = false): LengthAwarePaginator
    {

        return $this->prepareData($data, $resource, $includeIndex);
    }

    public function prepareData(LengthAwarePaginator $data, $resource, $includeIndex = false): LengthAwarePaginator
    {

        $collectionData = $data->getCollection()->transform(function ($item, $key) use ($resource, $data, $includeIndex) {

            if ($includeIndex) {
                $item['no'] = ($data->currentPage() - 1) * $data->perPage() + $key + 1;
            }

            return new $resource($item);
        });
        $data->setCollection($collectionData);
        return $data;
    }
}
