<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\Traits\LogsActivity;

class LoginAttempts extends Model
{

    protected $guarded = [];

    protected $table = "loggin_attempts";


    use LogsActivity;

    protected static $logName = __CLASS__;
    protected static $logAttributes = ['*'];
    protected static $logOnlyDirty = true;
    protected static $logAttributesToIgnore = ['created_at', 'updated_at'];
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}
