<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Item extends Model
{
    protected $fillable = ['brand_id', 'article', 'name', 'price', 'stock'];

    /**
     * @return App\Brand
     */
    public function brand() {
        return $this->hasOne('App\Brand', 'id', 'brand_id');
    }

    public function contractorItems()
    {
        return $this->belongsToMany('App\ContractorItem', 'relations');
    }


}
