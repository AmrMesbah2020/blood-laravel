<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateRequest extends FormRequest
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
            'name'=>'required|min:3|max:30',
            'email'=>['required', 'email', Rule::unique('users', 'email')->ignore($this->email,'email')],
            'phone'=>'required|max:15',
            'address'=>'required|min:20|max:150',
            'wieght'=>'required',
        ];
    }
}
