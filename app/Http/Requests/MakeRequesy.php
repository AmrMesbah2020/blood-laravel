<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class MakeRequesy extends FormRequest
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
     * @return array
     */
    public function rules()
    {
        return [
            'phone'=>'required|max:15',
            'description'=>'required',
            'quantity'=>'required',
            'date'=>'date_format:Y-m-d|after:'.date('Y-m-d'),
            'address'=>'required',

        ];
    }
    public function messages()
    {
        return [
            'title' => 'Invalid Date after today',
        ];
    }
}
