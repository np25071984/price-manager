<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ItemAroma extends Model
{
    protected $fillable = ['item_id', 'aroma_id'];

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'item_aroma';
}
