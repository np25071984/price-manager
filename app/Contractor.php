<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Contractor extends Model
{
    protected $fillable = ['name'];

    public function job()
    {
        return $this->hasOne('App\JobStatus');
    }
}
