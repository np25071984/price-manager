<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Contractor extends Model
{
    protected $fillable = ['name', 'config'];

    protected $casts = [
        'config' => 'array'
    ];

    public function job()
    {
        return $this->hasOne('App\JobStatus');
    }

    public function items()
    {
        return $this->hasMany('App\ContractorItem');
    }
}
