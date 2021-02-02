<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Contracts\Validation\Validator;

class UserRequest extends FormRequest
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
            'name' => 'required|max:255',
            'email' => 'required|email|max:255|unique:users',
            'position' => 'max:255',
            'image' => 'integer',
            'about' => 'string',
        ];
        // If update route
        if ($this->getMethod() === 'PUT' && $id = $this->route('id')) {
            // TODO: email updating is not active yet, i'll send confirmations to new and old email too, check that!
            $rules['email'] = 'required|max:255|email|unique:users,email,'.$id.',id';
        }
        return $rules;
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
            'email' => __('users.email'),
            'position' => __('users.position'),
            'image' => __('users.photo'),
            'about' => __('users.about'),
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
