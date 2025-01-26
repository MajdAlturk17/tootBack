<?php

namespace App\Traits;

use App\Infrastructure\Repositories\Employee\EmployeeRepository;
use Illuminate\Support\Facades\Request;

trait EmployeeHelper
{

    public static function getEmployeeByUserHeader(){
        $userId = Request::header('X-User-Id');
        if (!$userId) return null;
        return app(EmployeeRepository::class)
            ->findByAttributes(['user_id' => $userId]);
    }


}
