<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class ContractorResource extends JsonResource
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
            'title' => 'Просмотр поставщика',
            'href' => route('contractor.show', [$this->id]),
            'clickevent' => null,
        ];
        $controls[] = [
            'name' => 'button-component',
            'class' => ['fa', 'fa-lg', 'fa-edit', 'm-1'],
            'title' => 'Редактировать поставщика',
            'href' => route('contractor.edit', [$this->id]),
            'clickevent' => null,
        ];
        $controls[] = [
            'name' => 'button-component',
            'class' => ['fa', 'fa-lg', 'fa-trash', 'm-1'],
            'title' => 'Удалить поставщика',
            'href' => null,
            'clickevent' => [
                'text' => 'Вы уверены что хотите удалить поставщика? Это повлечет удаление всех его товаров',
                'link' => route('api.contractor.destroy', [$this->id]),
            ],
        ];

        return [
            'id' => $this->id,
            'name' => $this->name,
            'func' => $controls,
        ];
    }
}
