<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class RampUpSetting extends Model
{
    protected $table = 'ramp_up_settings';

    public $timestamps = false;

    protected $guarded = [];
    protected $primaryKey = false;
    public $incrementing = false;
}
