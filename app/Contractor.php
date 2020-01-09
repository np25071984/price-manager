<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Contractor extends Model
{
    protected $fillable = ['name', 'config'];

    protected $casts = [
        'config' => 'array'
    ];

    protected static function boot()
    {
        parent::boot();

        static::deleting(function (Contractor $contractor) {
            $contractor->items()->delete();
        });
    }

    public function job()
    {
        return $this->hasOne('App\PriceProcessingJobStatus');
    }

    public function items()
    {
        return $this->hasMany('App\ContractorItem');
    }
}
