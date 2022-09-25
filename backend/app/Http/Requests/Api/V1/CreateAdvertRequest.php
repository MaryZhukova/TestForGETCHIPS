<?php

namespace App\Http\Requests\Api\V1;

use App\Helpers\ApiResponseHelpers;
use Illuminate\Foundation\Http\FormRequest;
use App\Http\Requests\Request;

use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Contracts\Validation\Validator;


class CreateAdvertRequest extends FormRequest
{

    use ApiResponseHelpers;
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        $user = $this->user();
        return $user != null && $user->tokenCan('create');
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, mixed>
     */
    public function rules()
    {
        return [
            'files' => 'required|array|min:1|max:3',
            'files.*' => 'mimes:jpeg,jpg,png',
            'title'         => 'required|max:255',
            'description'   => 'required|max:1023'
            //'public_date'   => 'required|date',
        ];
    }

    public function failedValidation(Validator $validator)
    {
        throw new HttpResponseException(
            $this->jsonErrorValidation($validator->errors())
        );
    }




}
