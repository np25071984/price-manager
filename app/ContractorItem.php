<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ContractorItem extends Model
{
    protected $fillable = ['contractor_id', 'name', 'price'];

    public function relatedItem()
    {
        return $this->hasOneThrough('App\Item', 'App\Relation', 'contractor_item_id', 'id', 'id', 'item_id');
    }

    public function contractor()
    {
        return $this->hasOne('App\Contractor', 'id', 'contractor_id');
    }
}
