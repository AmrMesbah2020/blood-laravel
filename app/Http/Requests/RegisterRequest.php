<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class RegisterRequest extends FormRequest
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
            'name'=>'required',
            'email'=>'required|email|unique:users,email',
            'password'=>'required',
            'gender'=>'required',
            'phone'=>'required',
            'address'=>'required',
            'birthdate'=>'required|date_format:Y-m-d|before:'.date('Y-m-d'),
            'wieght'=>'required',

        ];
    }

}

