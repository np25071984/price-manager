<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class AromaRequest extends FormRequest
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
        $aroma = $this->route('aroma');

        return [
            'name' => 'required|min:3|unique:aromas,name,' . ($aroma ? $aroma->id : 'NULL') . ',id',
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
            'name.required' => 'Укажите название аромата',
            'name.min' => 'Название не может быть меньше трех символов',
            'name.unique' => 'Аромат с таким названием уже существует',
        ];
    }
}
