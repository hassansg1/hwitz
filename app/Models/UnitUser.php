<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\Traits\LogsActivity;

class UnitUser extends Model
{
    use HasFactory;

    protected $table = 'units_users';
    protected $guarded = [];
    public $timestamps = false;
    use LogsActivity;

    protected static $logName = __CLASS__;
    protected static $logAttributes = ['*'];
    protected static $logOnlyDirty = true;
    protected static $logAttributesToIgnore = ['created_at', 'updated_at'];

    public function user(){
        return $this->belongsTo(User::class,'user_id');
    }

    public function unit(){
        return $this->belongsTo(Unit::class,'unit_id');
    }
}
