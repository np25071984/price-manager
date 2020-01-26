<?php

namespace App\Http\Resources\item;

use Illuminate\Http\Resources\Json\JsonResource;

class ItemRelatedResource extends JsonResource
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
            'title' => 'Удалить связь',
            'href' => null,
            'clickevent' => [
                'text' => 'Вы уверены что хотите удалить связь?',
                'link' => route('api.relation.destroy', [$this->relatedItem->id, $this->id]),
            ],
        ];

        return [
            'id' => $this->id,
            'contractor' => $this->contractor,
            'name' => $this->name,
            'price' => $this->price,
            'func' => $controls,
        ];
    }
}
