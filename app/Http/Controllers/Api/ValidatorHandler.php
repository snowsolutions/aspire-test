<?php

namespace App\Http\Controllers\Api;

use Illuminate\Support\Facades\Validator;

trait ValidatorHandler
{
    public function handleValidation($data, $rule)
    {
        $validator = Validator::make(
            $data,
            $rule
        );

        if ($validator->fails()) {
            return \App\Http\Response\ApiResponseHandler::errors(401, $validator->errors());
        }
    }
}
