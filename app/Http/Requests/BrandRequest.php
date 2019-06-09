<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class BrandRequest extends FormRequest
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
        $brand = $this->route('brand');
        $userId = \Auth::user()->id;

        return [
            'name' => 'required|min:3|unique:brands,name,' . ($brand ? $brand->id : 'NULL') . ',id,user_id,' . $userId,
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
            'name.required' => 'Укажите название бренда',
            'name.min' => 'Название не может быть меньше трех символов',
            'name.unique' => 'Бренд с таким названием уже существует',
        ];
    }
}
