<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ContractorRequest extends FormRequest
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
        $contractor = $this->route('contractor');
        $userId = \Auth::user()->id;

        return [
            'name' => 'required|min:3|unique:contractors,name,' . ($contractor ? $contractor->id : 'NULL') . ',id',
            'col_name' => 'required|numeric|min:1',
            'col_price' => 'required|numeric|min:1',
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
            'name.required' => 'Укажите название поставщика',
            'name.min' => 'Название поставщика не может быть меньше трех символов',
            'name.unique' => 'Поставщик с таким названием уже существует',
            'col_name.required' => 'Необходимо указать номер колонки из прайса поставщика, где перечислены названия товаров',
            'col_name.numeric' => 'Номер колонки - число, начиная с 1',
            'col_name.min' => 'Номер колонки - число, начиная с 1',
            'col_price.required' => 'Необходимо указать номер колонки из прайса поставщика, где перечислены цены товаров',
            'col_price.numeric' => 'Номер колонки - число, начиная с 1',
            'col_price.min' => 'Номер колонки - число, начиная с 1',
        ];
    }
}
