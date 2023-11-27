<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\Traits\LogsActivity;

class LockerScreen extends Model
{
    protected $table = 'locker_screens';	
    const CREATED_AT = 'created_at';
    const UPDATED_AT = 'updates_at';	
    const DELETED_AT = 'deleted_at';

    use LogsActivity;

    protected static $logName = __CLASS__;
    protected static $logAttributes = ['*'];
    protected static $logOnlyDirty = true;
    protected static $logAttributesToIgnore = ['created_at', 'updated_at'];
}
