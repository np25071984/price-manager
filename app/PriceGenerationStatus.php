<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\Jobs\GeneratePrice;

class PriceGenerationStatus extends Model
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'jobs_price_generation_status';

    /**
     * Indicates if the model should be timestamped.
     *
     * @var bool
     */
    public $timestamps = false;

    protected $fillable = ['shop_id', 'status_id', 'message'];

    public function hasError()
    {
        return $this->status_id === GeneratePrice::ERROR;
    }

}
