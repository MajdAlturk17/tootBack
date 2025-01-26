<?php

namespace App\Traits;

trait ExceptionMaker
{
    public function makeException(\Exception $exception): array
    {
         return [
             'message'=>$exception->getMessage(),
             'trace'=>$exception->getTrace(),
             'code'=>$exception->getCode(),
         ];
    }
}
