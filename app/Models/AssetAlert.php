<?php

namespace App\Models;

use App\Tax;
use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\Traits\LogsActivity;

class AssetAlert extends Model
{
    protected $table = 'assets_alerts';
    protected $guarded = [];
    public $timestamps = null;

    use LogsActivity;

    protected static $logName = __CLASS__;
    protected static $logAttributes = ['*'];
    protected static $logOnlyDirty = true;
    protected static $logAttributesToIgnore = ['created_at', 'updated_at'];
}
