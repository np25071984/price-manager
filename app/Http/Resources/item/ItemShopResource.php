<?php

namespace App\Http\Resources\item;

use App\ShopItem;
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

        $shopItem = ShopItem::firstOrCreate([
            'shop_id' => $request->shop->id,
            'item_id' => $this->id,
        ]);

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
            'href' => route('shop.item.edit', [$shopItem->id]),
            'clickevent' => null,
        ];
        $controls[] = [
            'name' => 'button-component',
            'class' => ['fa', 'fa-lg', 'fa-unlink', 'm-1'],
            'title' => 'Убрать из магазина',
            'href' => null,
            'clickevent' => [
                'text' => 'Вы уверены что хотите убрать этот товар из магазина?',
                'link' => route('api.shop.remove_item', [$request->shop, $this->id]),
            ],
        ];

        return [
            'id' => $this->id,
            'article' => $this->article,
            'name' => $this->name,
            'price' => $shopItem->discount_price > 0 ? $shopItem->discount_price . " <span class='old-price'>$shopItem->price</span>" : $shopItem->price,
            'stock' => $this->stock,
            'func' => $controls,
        ];
    }
}
