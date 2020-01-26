<?php

namespace App\Http\Resources\country;

use Illuminate\Http\Resources\Json\JsonResource;

class CountryResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        $controls = [];
        $controls[] = [
            'name' => 'button-component',
            'class' => ['fa', 'fa-lg', 'fa-eye', 'm-1'],
            'title' => 'Просмотр страны',
            'href' => route('country.show', [$this->id]),
            'clickevent' => null,
        ];
        $controls[] = [
            'name' => 'button-component',
            'class' => ['fa', 'fa-lg', 'fa-edit', 'm-1'],
            'title' => 'Редактировать страну',
            'href' => route('country.edit', [$this->id]),
            'clickevent' => null,
        ];
        $controls[] = [
            'name' => 'button-component',
            'class' => ['fa', 'fa-lg', 'fa-trash', 'm-1'],
            'title' => 'Удалить страну',
            'href' => null,
            'clickevent' => [
                'text' => 'Вы уверены что хотите удалить бренд? Это повлечет удаление всех товаров',
                'link' => route('api.country.destroy', [$this->id]),
            ],
        ];

        return [
            'id' => $this->id,
            'name' => $this->name,
            'func' => $controls,
        ];
    }
}
