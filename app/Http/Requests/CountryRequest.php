<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CountryRequest extends FormRequest
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
        $country = $this->route('country');

        return [
            'name' => 'required|min:3|unique:countries,name,' . ($country ? $country->id : 'NULL') . ',id',
        ];
    }

    /**
     * Get the error messages for the defined validation rules.
     *
     * @return array
     */
    public function messages()
    {
        return [
            'name.required' => 'Укажите название страны',
            'name.min' => 'Название не может быть меньше трех символов',
            'name.unique' => 'Страна с таким названием уже существует',
        ];
    }
}
