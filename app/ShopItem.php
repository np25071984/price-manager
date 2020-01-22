<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\Item;
use App\Shop;

class ShopItem extends Model
{
    protected $fillable = ['item_id', 'shop_id', 'price'];

    /**
     * @return App\Shop
     */
    public function shop() {
        return $this->hasOne('App\Shop', 'id', 'shop_id');
    }

    /**
     * @return App\Item
     */
    public function item() {
        return $this->hasOne('App\Item', 'id', 'item_id');
    }

}
