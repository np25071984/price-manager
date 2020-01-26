<?php

namespace App\Http\Resources\shop;

use Illuminate\Http\Resources\Json\JsonResource;

class ShopResource extends JsonResource
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
            'title' => 'Просмотр магазин',
            'href' => route('shop.show', [$this->id]),
            'clickevent' => null,
        ];
        $controls[] = [
            'name' => 'button-component',
            'class' => ['fa', 'fa-lg', 'fa-edit', 'm-1'],
            'title' => 'Редактировать магазин',
            'href' => route('shop.edit', [$this->id]),
            'clickevent' => null,
        ];
        $controls[] = [
            'name' => 'button-component',
            'class' => ['fa', 'fa-lg', 'fa-trash', 'm-1'],
            'title' => 'Удалить магазин',
            'href' => null,
            'clickevent' => [
                'text' => 'Вы уверены что хотите удалить магазин?',
                'link' => route('api.shop.destroy', [$this->id]),
            ],
        ];

        return [
            'id' => $this->id,
            'name' => $this->name,
            'func' => $controls,
        ];
    }
}
