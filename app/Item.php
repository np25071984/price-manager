<?php

namespace App;

use App\Relation;
use Illuminate\Database\Eloquent\Model;

class Item extends Model
{
    protected $fillable = ['brand_id', 'article', 'name', 'price', 'stock'];

    protected static function boot()
    {
        parent::boot();

        static::deleting(function (Item $item) {
            Relation::where(['item_id' => $item->id])->delete();
        });
    }

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
