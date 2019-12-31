<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Group extends Model
{
    protected $fillable = ['name'];

    protected static function boot()
    {
        parent::boot();

        static::deleting(function (Group $group) {
            $group->items()->delete();
        });
    }

    public function items()
    {
        return $this->hasMany('App\Item');
    }
}
