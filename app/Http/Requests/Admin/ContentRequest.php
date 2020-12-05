<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Contracts\Validation\Validator;

class ContentRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return false;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        $reqData = $this->all();
        $rules = [
            'content.parent_id' => 'required|integer',
        ];
        foreach (languages() as $language) {
            $data = $reqData[$language->code] ?? [];

            if (isset($data['active']) && intval($data['active']) === 0)
                continue;

            if(isset($data['active']) && intval($data['active']) === 1){
                $rules[$language->code . '.title'] = 'required|max:255';
                $rules[$language->code . '.description'] = 'max:400';
                $rules[$language->code . '.full'] = '';
                $rules[$language->code . '.icon'] = 'max:255';
                $rules[$language->code . '.active'] = 'integer';
            }
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
        $reqData = $this->all();
        $attributes = [
            'content.parent_id' => __('contents.parent_content'),
        ];
        foreach (languages() as $language) {
            $data = $reqData[$language->code] ?? [];
            $upperLang = strtoupper($language->code);

            if (isset($data['active']) && intval($data['active']) === 0)
                continue;

            if(isset($data['active']) && intval($data['active']) === 1){
                $attributes[$language->code . '.title'] = __('contents.title') . " - $upperLang";
                $attributes[$language->code . '.description'] = __('contents.description') . " - $upperLang";
                $attributes[$language->code . '.full'] = __('contents.full') . " - $upperLang";
                $attributes[$language->code . '.icon'] = __('contents.icon') . " - $upperLang";
                $attributes[$language->code . '.active'] = __('contents.active') . " - $upperLang";
            }
        }
        return $attributes;
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
