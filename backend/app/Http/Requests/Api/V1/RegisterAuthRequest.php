<?php

namespace App\Http\Requests\Api\V1;

use App\Helpers\ApiResponseHelpers;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Contracts\Validation\Validator;

class RegisterAuthRequest extends FormRequest
{
    use ApiResponseHelpers;

    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, mixed>
     */
    public function rules()
    {
         return [
             'name'      => 'required|string|max:255',
             'email'     => 'required|string|email|unique:users',
             'password'  => 'required|string|min:6',
         ];

    }


    public function failedValidation(Validator $validator)
    {
        throw new HttpResponseException(
            $this->jsonErrorValidation($validator->errors())
        );
    }


}
