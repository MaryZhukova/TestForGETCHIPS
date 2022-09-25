<?php

namespace App\Http\Requests\Api\V1;
use App\Helpers\ApiResponseHelpers;
use App\Models\Advert;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Contracts\Validation\Validator;


class UpdateAdvertRequest extends FormRequest
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
     * @return array
     */
    public function rules()
    {
        return [
            'title'         => 'required|max:255',
            'description'   => 'required|max:1023',
        ];
    }

    public function failedValidation(Validator $validator)
    {
        throw new HttpResponseException(
            $this->jsonErrorValidation($validator->errors())
        );
    }
}
