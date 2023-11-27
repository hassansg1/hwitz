<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\Traits\LogsActivity;

class Laundry extends Model
{
    use HasFactory;

    protected $guarded = [];
    protected $table = 'laundries';

    use LogsActivity;

    protected static $logName = __CLASS__;
    protected static $logAttributes = ['*'];
    protected static $logOnlyDirty = true;
    protected static $logAttributesToIgnore = ['created_at', 'updated_at'];
    const CREATED_AT = 'created';
    const UPDATED_AT = 'modified';

    //if type 0 washer else dryer
    public function machines(){
        return $this->hasMany(LaundryMachine::class,'laundry_id');
    }

    public function washer(){
        return $this->hasMany(LaundryMachine::class,'laundry_id')->where('type',0);
    }

    public function dryer(){
        return $this->hasMany(LaundryMachine::class,'laundry_id')->where('type',1);
    }

}
