<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ShopItem extends Model
{
    protected $fillable = ['item_id', 'shop_id'];

    protected $primaryKey = null;

    public $incrementing = false;
}