<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\Traits\LogsActivity;

class ApplianceReservation extends Model
{
    use HasFactory;

    use LogsActivity;

    protected static $logName = __CLASS__;
    protected static $logAttributes = ['*'];
    protected static $logOnlyDirty = true;
    protected static $logAttributesToIgnore = ['created_at', 'updated_at'];

    protected $table = 'appliances_reservations';
    protected $guarded = [];
    const CREATED_AT = 'created';
    const UPDATED_AT = 'modified';

    public function user(){
        return $this->belongsTo(User::class,'user_id');
    }

}
