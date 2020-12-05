<?php

namespace App\Http\Requests\Admin\Menu;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Contracts\Validation\Validator;

class ItemRequest extends FormRequest
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
            'item.parent_id' => 'required|integer',
        ];
        foreach (languages() as $language) {
            $data = $reqData[$language->code] ?? [];

            if (isset($data['active']) && intval($data['active']) === 0)
                continue;

            if(isset($data['active']) && intval($data['active']) === 1){
                $rules[$language->code . '.title'] = 'required|max:255';
                $rules[$language->code . '.url'] = 'max:255';
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
            'item.parent_id' => __('menus.item_parent'),
        ];
        foreach (languages() as $language) {
            $data = $reqData[$language->code] ?? [];
            $upperLang = strtoupper($language->code);

            if (isset($data['active']) && intval($data['active']) === 0)
                continue;

            if(isset($data['active']) && intval($data['active']) === 1){
                $attributes[$language->code . '.title'] = __('menus.item_title') . " - $upperLang";
                $attributes[$language->code . '.url'] = __('menus.item_url') . " - $upperLang";
                $attributes[$language->code . '.icon'] = __('menus.item_icon') . " - $upperLang";
                $attributes[$language->code . '.active'] = __('menus.item_active') . " - $upperLang";
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
