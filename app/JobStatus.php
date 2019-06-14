<?php

namespace App;

use App\Scopes\UserScope;
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

    protected $fillable = ['user_id', 'contractor_id', 'status_id', 'message'];

    protected static function boot()
    {
        parent::boot();

        static::addGlobalScope(new UserScope);
    }

    public function hasError()
    {
        return $this->status_id === ParsePrice::ERROR;
    }
}
