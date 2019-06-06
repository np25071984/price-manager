<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class BrandResource extends JsonResource
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
            'title' => 'Просмотр бренда',
            'href' => route('brand.show', [$this->id])
        ];
        $controls[] = [
            'name' => 'button-component',
            'class' => ['fa', 'fa-lg', 'fa-edit', 'm-1'],
            'title' => 'Редактировать бренд',
            'href' => route('brand.edit', [$this->id])
        ];
        $controls[] = [
            'name' => 'button-component',
            'class' => ['fa', 'fa-lg', 'fa-trash', 'm-1'],
            'title' => 'Удалить бренд',
            'href' => null,
            'clickevent' => [
                'text' => 'Вы уверены что хотите удалить бренд? Это повлечет удаление всех товаров',
                'link' => route('api.brand.destroy', [$this->id]),
            ],
        ];

        return [
            'id' => $this->id,
            'name' => $this->name,
            'func' => $controls,
        ];
    }
}
