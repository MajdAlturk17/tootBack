<?php

namespace App\Traits;

use Illuminate\Support\Collection;

trait HasDepartments
{
    public function getDepartments(): Collection
    {
        $departments = collect();

        $this->employees->each(function ($employee) use ($departments) {
            if ($employee->workInfo) {
                $departmentInfo = [
                    'value' => $employee->workInfo->department->id,
                    'label' => $employee->workInfo->department->department_name,
                ];

                $departments->push($departmentInfo);
            }
        });
        return $departments->unique('value');
    }

}
