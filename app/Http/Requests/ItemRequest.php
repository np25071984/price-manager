<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

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
        $item = $this->route('item');
        $userId = \Auth::user()->id;

        return [
            'brand_id' => 'required',
            'article' => 'required|min:3|unique:items,article,' . ($item ? $item->id : 'NULL') . ',id',
            'name' => 'required|min:3|unique:items,name,' . ($item ? $item->id : 'NULL') . ',id',
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
            'brand_id.required' => 'Выберите бренд',
            'article.required' => 'Укажите артикул товара',
            'article.min' => 'Артикул не может быть меньше трех символов',
            'article.unique' => 'Товар с таким артикулом уже существует',
            'name.required' => 'Укажите название товара',
            'name.min' => 'Название не может быть меньше трех символов',
            'name.unique' => 'Товар с таким названием уже существует',
        ];
    }
}
