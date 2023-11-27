<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\Traits\LogsActivity;

class SmsLogs extends Model
{

    protected $guarded = [];

    protected $table = "sms_log";

    public function user(){
        return $this->belongsTo(User::class,'user_id');
    }
    use LogsActivity;

    protected static $logName = __CLASS__;
    protected static $logAttributes = ['*'];
    protected static $logOnlyDirty = true;
    protected static $logAttributesToIgnore = ['created_at', 'updated_at'];
}
