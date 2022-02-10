<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class PostRequest extends FormRequest
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
            'title'=>'required|max:40|min:10',
            'content'=>'required|min:100|max:200',
            'image'=>'image|mimes:jpeg,png,jpg,gif,svg|max:2048'
        ];
    }

    public function messages()
    {
        return [
            'title.required' => 'title is required.',
            'title.min' => 'for title min input must be at least 10 chars.',
            'title.max' => 'for title max input must be at least 40 chars.',
            'content.required'=>' content field is required.',
            'content.min'=>'for content min input must be at least 100 chars.',
            'content.max'=>'for content max input must be at least 200 chars.',
            'image.mime'=>'invalid iamge',
            'image.max'=>'max size of image is 2 mb',

        ];
    }

}
