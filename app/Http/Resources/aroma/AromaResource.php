<?php

namespace App\Http\Resources\aroma;

use Illuminate\Http\Resources\Json\JsonResource;

class AromaResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        $controls[] = [
            'name' => 'button-component',
            'class' => ['fa', 'fa-lg', 'fa-trash', 'm-1'],
            'title' => 'Удалить аромат',
            'href' => null,
            'clickevent' => [
                'text' => 'Вы уверены что хотите удалить аромат?',
                'link' => route('api.aroma.destroy', [$this->id]),
            ],
        ];

        return [
            'id' => $this->id,
            'name' => $this->name,
            'func' => $controls,
        ];
    }
}
