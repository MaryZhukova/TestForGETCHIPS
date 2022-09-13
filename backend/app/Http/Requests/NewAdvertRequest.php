<?php

namespace App\Http\Requests\API;

use Illuminate\Foundation\Http\FormRequest;

class NewAdvertRequest extends FormRequest
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

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, mixed>
     */
    public function rules()
    {
        return [
            'title'         => 'required|max:255',
            'description'   => 'required|max:1023',
            'create_date'   => 'required|date',
            'user_id'       => 'required'

        ];
    }
}
