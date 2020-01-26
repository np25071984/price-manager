<?php

namespace App\Http\Resources\item;

use Illuminate\Http\Resources\Json\JsonResource;

class ItemGroupResource extends JsonResource
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
            'class' => ['fa', 'fa-lg', 'fa-unlink', 'm-1'],
            'title' => 'Удалить товар из группы',
            'href' => null,
            'clickevent' => [
                'text' => 'Вы уверены что хотите удалить товар из группы?',
                'link' => route('api.item.group.remove', [$this->id]),
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
