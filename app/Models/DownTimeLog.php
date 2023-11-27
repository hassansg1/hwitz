<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

class DownTimeLog extends Model
{

    protected $guarded = [];

    protected $table = "downtime_log";

    protected $appends = ['up_time_formatted'];

    public function getUpTimeFormattedAttribute()
    {
        if (is_numeric($this->uptime))
            return round($this->uptime, 2) . '%';
        else
            return $this->uptime;
    }
}
