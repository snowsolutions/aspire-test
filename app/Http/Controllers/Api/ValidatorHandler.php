<?php

namespace App\Http\Controllers\Api;

use Illuminate\Support\Facades\Validator;

trait ValidatorHandler
{
    /**
     * Handle the validator result
     *
     * @return array
     */
    public function handleValidation($data, $rule)
    {
        $validator = Validator::make(
            $data,
            $rule
        );

        return [$validator->fails(), \App\Http\Response\ApiResponseHandler::errors(422, $validator->errors())];
    }
}
