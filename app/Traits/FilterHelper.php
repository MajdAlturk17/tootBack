<?php

namespace App\Traits;

trait FilterHelper
{

    private function resolveFiltersArray($filters): array
    {
        $filters = json_decode($filters, true);

        $transformedArray = [];

        foreach ($filters as $filter) {
            // Use the 'id' as the key and 'value' as the value
            $transformedArray[$filter['id']] = $filter['value'];
        }

        return $transformedArray;
    }

}
