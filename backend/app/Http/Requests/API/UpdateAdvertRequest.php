<?php

namespace App\Http\Requests\API;
use App\Models\Advert;

use Illuminate\Foundation\Http\FormRequest;

class UpdateAdvertRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return $this->route('advert')
            ->canBeEditedBy($this->user());
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
}
