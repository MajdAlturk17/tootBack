<?php

namespace App\Traits;

use App\Helpers\FileHelper;

trait PrepareEmployeeData
{
    public function addEmployeeIdToArray(array $data, $employeeId): array
    {
        return array_map(function ($addresses) use ($employeeId) {
            $addresses['employee_id'] = $employeeId;
            return $addresses;
        }, $data);
    }

    public function addEmployeeIdToModel(array $data, $employeeId): array
    {
        $data['employee_id'] = $employeeId;
        return $data;
    }

    public function addEmployeeIdAndFileUrlToArray(array $data, int $employeeId,string $path): array
    {
        return array_map(function ($item) use ($employeeId, $path) {
            $item['employee_id'] = $employeeId;
//            $item['file_url'] = FileHelper::uploadFile($item['file_url'], $path);
            return $item;
        }, $data);
    }
}

