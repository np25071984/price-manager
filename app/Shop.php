<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Shop extends Model
{
    protected $fillable = ['name', 'url'];

    /**
     * Get all items.
     * @return App\Item[]
     */
    public function items()
    {
        return $this->hasManyThrough(
            'App\Item',
            'App\ShopItem',
            'shop_id',
            'id',
            'id',
            'item_id'
        )->orderBy('name', 'asc');
    }

}
