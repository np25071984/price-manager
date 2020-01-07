<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Brand extends Model
{
    protected $fillable = ['name'];

    protected static function boot()
    {
        parent::boot();

        static::deleting(function (Brand $brand) {
            $brand->items()->delete();
        });
    }

    public function items()
    {
        return $this->hasMany('App\Item');
    }
}
