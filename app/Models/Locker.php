<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\Traits\LogsActivity;

//use App\Models\Building;
class Locker extends Model
{
    protected $table = 'lockers';
    const CREATED_AT = 'created_at';
    const UPDATED_AT = 'updated_at';
    const DELETED_AT = 'deleted_at';

    use LogsActivity;

    protected static $logName = __CLASS__;
    protected static $logAttributes = ['*'];
    protected static $logOnlyDirty = true;
    protected static $logAttributesToIgnore = ['created_at', 'updated_at'];
    public function building()
    {
        return $this->belongsTo('App\Models\Building');
    }

    public function lockerScreen()
    {
        return $this->belongsTo('App\Models\LockerScreen');
    }

    public function locker()
    {
        return $this->hasOne('App\Models\LockerPackage', 'locker_id')->latest('arrived_at');
    }

    public function package()
    {
        return $this->hasOne('App\Models\LockerPackage', 'locker_id')->latest('arrived_at');
    }
}
