<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;

class CommentRequest extends FormRequest
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
            'comment' => 'required|max:400|min:15',
            'parent_id' => 'integer',
            'content_id' => 'required|integer|exists:contents,id'
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
            'comment' => __('site.comment.self_singular'),
            'content_id' => 'ID'
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
