<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ItemTag extends Model
{
    protected $fillable = ['item_id', 'tag_id'];

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'item_tag';
}
