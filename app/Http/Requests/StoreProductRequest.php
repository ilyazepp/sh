<?php

namespace SHammer\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreProductRequest extends FormRequest
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
        $rules = [
            'name' => 'required|max:255|min:3',
            'price' => 'required|regex:/^\d*(\.\d{1,2})?$/',
            'description' => 'required'
        ];

        if (request()->has('image')) {
             $rules['imageDescription'] = 'required';
        }

        $validator = \Validator::make(request()->all(), $rules);

        return $rules;
    }
}
