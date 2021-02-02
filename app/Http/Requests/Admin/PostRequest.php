<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Contracts\Validation\Validator;

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
        $reqData = $this->all();
        $rules = [
            'post.parents' => 'array',
            'post.relations' => 'array',
            'post.files' => '',
            'post.searchable' => 'integer'
        ];
        foreach (languages() as $language) {
            $data = $reqData[$language->code] ?? [];

            $rules[$language->code . '.description'] = 'max:400';
            $rules[$language->code . '.full'] = '';
            $rules[$language->code . '.icon'] = 'max:255';
            $rules[$language->code . '.active'] = 'integer';
            $rules[$language->code . '.meta_title'] = 'nullable|string';
            $rules[$language->code . '.meta_description'] = 'nullable|string';
            $rules[$language->code . '.meta_tags'] = 'nullable|string';

            if (isset($data['active']) && intval($data['active']) === 0) {
                $rules[$language->code . '.title'] = 'nullable|max:255';
            }

            if(isset($data['active']) && intval($data['active']) === 1){
                $rules[$language->code . '.title'] = 'required|max:255';
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
            'post.parents' => __('posts.parents'),
            'post.relations' => __('posts.relations'),
            'post.files' => __('posts.files'),
            'post.searchable' => __('posts.searchable')
        ];
        foreach (languages() as $language) {
            $data = $reqData[$language->code] ?? [];
            $upperLang = strtoupper($language->code);

            if (isset($data['active']) && intval($data['active']) === 0)
                continue;

            if(isset($data['active']) && intval($data['active']) === 1){
                $attributes[$language->code . '.title'] = __('posts.title') . " - $upperLang";
                $attributes[$language->code . '.description'] = __('posts.description') . " - $upperLang";
                $attributes[$language->code . '.full'] = __('posts.full') . " - $upperLang";
                $attributes[$language->code . '.icon'] = __('posts.icon') . " - $upperLang";
                $attributes[$language->code . '.active'] = __('posts.active') . " - $upperLang";
                $attributes[$language->code . '.meta_title'] = __('posts.seo.title') . " - $upperLang";
                $attributes[$language->code . '.meta_description'] = __('posts.seo.description') . " - $upperLang";
                $attributes[$language->code . '.meta_tags'] = __('posts.seo.tags') . " - $upperLang";
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
