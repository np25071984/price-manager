<?php

namespace App\Http\Resources\item;

use Illuminate\Http\Resources\Json\JsonResource;

class ContractorItemResource extends JsonResource
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
            'class' => ['fa', 'fa-lg', 'fa-link', 'm-1'],
            'title' => 'Добавить связь',
            'href' => route('contractor.relation_form', [$this->contractor_id, $this->id]),
            'clickevent' => null,
        ];
        if ($this->relation_id) {
            $controls[] = [
                'name' => 'button-component',
                'class' => ['fa', 'fa-lg', 'fa-trash', 'm-1'],
                'title' => 'Удалить связь',
                'href' => null,
                'clickevent' => [
                    'text' => 'Вы уверены что хотите удалить связь?',
                    'link' => route('api.relation.destroy', [$this->relation_id, $this->id]),
                ],
            ];
        }

        return [
            'id' => $this->id,
            'real_article' => $this->real_article,
            'name' => $this->name,
            'relation_name' => $this->relation_name,
            'price' => $this->price,
            'func' => $controls,
        ];
    }
}
