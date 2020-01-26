<?php

namespace App\Http\Resources\item;

use Illuminate\Http\Resources\Json\JsonResource;

class ItemCountryResource extends JsonResource
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
            'title' => 'Просмотр товара',
            'href' => route('item.show', [$this->id]),
            'clickevent' => null,
        ];
        $controls[] = [
            'name' => 'button-component',
            'class' => ['fa', 'fa-lg', 'fa-edit', 'm-1'],
            'title' => 'Редактировать товар',
            'href' => route('item.edit', [$this->id]),
            'clickevent' => null,
        ];
        $controls[] = [
            'name' => 'button-component',
            'class' => ['fa', 'fa-lg', 'fa-trash', 'm-1'],
            'title' => 'Удалить товар',
            'href' => null,
            'clickevent' => [
                'text' => 'Вы уверены что хотите удалить товар?',
                'link' => route('api.item.destroy', [$this->id]),
            ],
        ];

        return [
            'id' => $this->id,
            'article' => $this->article,
            'name' => $this->name,
            'stock' => $this->stock,
            'func' => $controls,
        ];
    }
}
