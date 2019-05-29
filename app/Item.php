<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Item extends Model
{
    protected $fillable = ['brand_id', 'article', 'name', 'price', 'stock'];
}
