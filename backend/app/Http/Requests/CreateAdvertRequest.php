<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use App\Http\Requests\Request;


class CreateAdvertRequest extends FormRequest
{
    protected $stopOnFirstFailure = false;

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

    
}
