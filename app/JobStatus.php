<?php

namespace App;

use App\Jobs\ParsePrice;
use Illuminate\Database\Eloquent\Model;

class JobStatus extends Model
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'jobs_status';

    /**
     * Indicates if the model should be timestamped.
     *
     * @var bool
     */
    public $timestamps = false;

    protected $fillable = ['contractor_id', 'status_id', 'message'];

    public function hasError()
    {
        return $this->status_id === ParsePrice::ERROR;
    }
}
