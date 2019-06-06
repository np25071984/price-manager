<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\ResourceCollection;

class BrandResourceCollection extends ResourceCollection
{
    /**
     * Transform the resource collection into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {

        return [
            'data' => $this->collection,
            'columns' => [
                [
                    'class' => '',
                    'sort' => null,
                    'type' => 'text',
                    'code' => 'name',
                    'title' => 'Наименование поставщика'
                ],
                [
                    'class' => 'text-center',
                    'sort' => null,
                    'type' => 'component',
                    'code' => 'func',
                    'title' => 'Функции',
                ],
            ],
        ];
    }
}
