<?php

namespace App\Models;

use DB;
use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\Traits\LogsActivity;

class LaundryMachineState extends Model
{

	protected $table = 'laundrymachine_states';
	const CREATED_AT = 'created';
	const UPDATED_AT = 'modified';

	protected $fields = [];
    use LogsActivity;

    protected static $logName = __CLASS__;
    protected static $logAttributes = ['*'];
    protected static $logOnlyDirty = true;
    protected static $logAttributesToIgnore = ['created_at', 'updated_at'];
}
