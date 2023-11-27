<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\Traits\LogsActivity;

class Device extends Model
{

	protected $table = 'devices';

	const CREATED_AT = 'created';
	const UPDATED_AT = 'modified';

    use LogsActivity;

    protected static $logName = __CLASS__;
    protected static $logAttributes = ['*'];
    protected static $logOnlyDirty = true;
    protected static $logAttributesToIgnore = ['created_at', 'updated_at'];

    public function scopeActive($query) {
        return $query->where('active',1);
    }

    public function buttons()
    {
        return $this->hasMany(DevicesButton::class, 'device_id')->orderBy('pos', 'asc')->get();
    }

    public function getDeviceId($name) {
        return $this->where('table_name' , $name)->first();
    }
}
