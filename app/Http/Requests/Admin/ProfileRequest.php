<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Contracts\Validation\Validator;

class ProfileRequest extends FormRequest
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
            'name' => 'required|max:255',
            //'email' => 'required|email|max:255|unique:users,email,'.Auth::id().',id',
            'position' => 'max:255',
            'image' => 'integer',
            'about' => 'string'
        ];
    }

    /**
     * Get the validation attributes that apply to the request.
     *
     * @return array
     */
    public function attributes()
    {
        return [
            'name' => __('users.fullname'),
            //'email' => __('users.email'),
            'position' => __('users.position'),
            'image' => __('users.photo'),
            'about' => __('users.about')
        ];
    }

    /**
     * Return validation errors as json response
     *
     * @param Validator $validator
     */
    protected function failedValidation(Validator $validator)
    {
        $errors = "";
        foreach ($validator->errors()->all() as $error) {
            $errors .= $error."\n";
        }
        $data = [
            'message' => $errors
        ];

        throw new HttpResponseException(resJson(0, $data, 422));
    }
}
