<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class GroupRequest extends FormRequest
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
        $group = $this->route('group');

        return [
            'name' => 'required|min:3|unique:groups,name,' . ($group ? $group->id : 'NULL'),
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
            'name.required' => 'Укажите название группы',
            'name.min' => 'Название не может быть меньше трех символов',
            'name.unique' => 'Группа с таким именем уже существует',
        ];
    }
}
