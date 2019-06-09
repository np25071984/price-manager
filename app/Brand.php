<?php

namespace App;

use App\Scopes\UserScope;
use Illuminate\Database\Eloquent\Model;

class Brand extends Model
{
    protected $fillable = ['user_id', 'name'];

    protected static function boot()
    {
        parent::boot();

        static::addGlobalScope(new UserScope);

        static::deleting(function (Brand $brand) {
            $brand->items()->delete();
        });
    }

    public function items()
    {
        return $this->hasMany('App\Item');
    }

}
