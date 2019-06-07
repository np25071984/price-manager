<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class ItemContractorUnrelatedResource extends JsonResource
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


        return [
            'id' => $this->id,
            'real_article' => $this->real_article,
            'contractor_name' => $this->contractor_name,
            'item_name' => $this->item_name,
            'price' => $this->price,
            'func' => $controls,
        ];

    }
}
