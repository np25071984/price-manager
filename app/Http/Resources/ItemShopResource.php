<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class ItemShopResource extends JsonResource
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
            'title' => 'Убрать из магазина',
            'href' => null,
            'clickevent' => [
                'text' => 'Вы уверены что хотите убрать этот товар из магазина?',
                'link' => route('api.shop.remove_item', [1, $this->id]),
            ],
        ];

        return [
            'id' => $this->id,
            'article' => $this->article,
            'name' => $this->name,
            'price' => $this->price,
            'stock' => $this->stock,
            'func' => $controls,
        ];
    }
}
