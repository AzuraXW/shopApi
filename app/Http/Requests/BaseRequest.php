<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;

class BaseRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    public function failedValidation($validator)
    {
        $error = $validator->errors()->first();

        $response = response()->json([
            'success' => false,
            'message' => $error
        ])->setStatusCode(422);

        throw new HttpResponseException($response);
    }
}
