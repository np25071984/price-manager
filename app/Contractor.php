<?php

namespace App;

use App\Scopes\UserScope;
use Illuminate\Database\Eloquent\Model;

class Contractor extends Model
{
    protected $fillable = ['user_id', 'name', 'config'];

    protected $casts = [
        'config' => 'array'
    ];

    protected static function boot()
    {
        parent::boot();

        static::addGlobalScope(new UserScope);

        static::deleting(function (Contractor $contractor) {
            $contractor->items()->delete();
        });
    }

    public function job()
    {
        return $this->hasOne('App\JobStatus');
    }

    public function items()
    {
        return $this->hasMany('App\ContractorItem');
    }
}
