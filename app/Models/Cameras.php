<?php
namespace App\Models;

use Spatie\Activitylog\Traits\LogsActivity;

class Cameras extends CakeBaseModel
{

    protected $table = 'cameras';
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */

    use LogsActivity;

    protected static $logName = __CLASS__;
    protected static $logAttributes = ['*'];
    protected static $logOnlyDirty = true;
    protected static $logAttributesToIgnore = ['created_at', 'updated_at'];
    protected $primaryKey = 'id';

    public  function scopeActive($query) {
        return $query->where('active', 1);
    }

}
