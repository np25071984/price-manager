<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\ResourceCollection;

class ItemUnrelatedResouceCollection extends ResourceCollection
{
    /**
     * Transform the resource collection into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        $column = $request->input('column');
        if ($column && !in_array($column, ['article', 'brand_name', 'item_name', 'price'])) {
            $column = null;
        }

        $order = $request->input('order');
        if (!in_array($order, ['asc', 'desc'])) {
            $order = 'asc';
        }

        return [
            'data' => $this->collection,
            'columns' => [
                [
                    'class' => ['text-center'],
                    'sortable' => true,
                    'sort' => ($column === 'article') ? $order : false,
                    'type' => 'text',
                    'code' => 'article',
                    'title' => 'Артикул'
                ],
                [
                    'class' => ['text-center'],
                    'sortable' => true,
                    'sort' => ($column === 'brand_name') ? $order : false,
                    'type' => 'text',
                    'code' => 'brand_name',
                    'title' => 'Бренд'
                ],
                [
                    'class' => '',
                    'sortable' => true,
                    'sort' => ($column === 'item_name') ? $order : false,
                    'type' => 'text',
                    'code' => 'item_name',
                    'title' => 'Наименование товара'
                ],
                [
                    'class' => ['text-center'],
                    'sortable' => true,
                    'sort' => ($column === 'price') ? $order : false,
                    'type' => 'text',
                    'code' => 'price',
                    'title' => 'Цена'
                ],
                [
                    'class' => ['text-center'],
                    'sortable' => false,
                    'type' => 'html',
                    'code' => 'func',
                    'title' => 'Функции',
                ],
            ],
        ];

    }
}
