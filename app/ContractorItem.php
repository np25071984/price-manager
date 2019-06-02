<?php

namespace App;

use App\Relation;
use Illuminate\Database\Eloquent\Model;

class ContractorItem extends Model
{
    protected $fillable = ['contractor_id', 'name', 'price'];

    protected static function boot()
    {
        parent::boot();

        static::deleting(function (ContractorItem $contractorItem) {
            Relation::where(['contractor_item_id' => $contractorItem->id])->delete();
        });
    }

    public function relatedItem()
    {
        return $this->hasOneThrough('App\Item', 'App\Relation', 'contractor_item_id', 'id', 'id', 'item_id');
    }

    public function contractor()
    {
        return $this->hasOne('App\Contractor', 'id', 'contractor_id');
    }
}
