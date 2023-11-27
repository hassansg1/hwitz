<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\Traits\LogsActivity;

class DevicesButton extends Model
{

	protected $devices = 'devices_buttons';

	const CREATED_AT = 'created';
	const UPDATED_AT = 'modified';

    use LogsActivity;

    protected static $logName = __CLASS__;
    protected static $logAttributes = ['*'];
    protected static $logOnlyDirty = true;
    protected static $logAttributesToIgnore = ['created_at', 'updated_at'];
    public function device()
    {
        return $this->belongsTo(Device::class, 'id');
    }
}
