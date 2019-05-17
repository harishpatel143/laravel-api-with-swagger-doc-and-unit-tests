<?php

namespace App\Traits;

use App\ResponseTransformar\ValidationResponseTransformer;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;
use App\Response\ApiResponse;
use Request;


trait UsesCustomErrorMessage
{

    /**
     * Handle a failed validation attempt.
     *
     * @param  \Illuminate\Contracts\Validation\Validator $validator
     * @return void
     *
     * @throws \Illuminate\Http\Exceptions\HttpResponseException
     */
    protected function failedValidation(Validator $validator)
    {
        $apiResponse = new ApiResponse();
        $message = (method_exists($this, 'message')) ? $this->container->call([
            $this,
            'message'
        ]) : 'The given data was invalid.';

        throw new HttpResponseException($apiResponse->respondValidationError(
            $this->response($validator->errors()->getMessages()),
            ""
        ));
    }

    /**
     * iterate and give valid validation response
     *
     * @param $errors
     * @return array
     *
     */
    public function response($errors)
    {
        $validationResponseTransformer = new ValidationResponseTransformer();
        return $validationResponseTransformer->response($errors);
    }
}
