<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Http\JsonResponse;
use Illuminate\Validation\ValidationException;
use Illuminate\Validation\Validator;

abstract class BaseRequest extends FormRequest
{

    abstract public function authorize();

    abstract public function rules();

    protected function failedValidation(Validator $validator)
    {
        $errors = (new ValidationException($validator))->errors();
        throw new HttpResponseException(response()->json ( [
            'status'  => 'error',
            'code' 	  => JsonResponse::HTTP_OK,
            'message' => "Une erreur est survenue lors de la validation.",
            'data' 	  => null,
            'error' 	  => $errors
        ], JsonResponse::HTTP_UNPROCESSABLE_ENTITY ));
    }
}
