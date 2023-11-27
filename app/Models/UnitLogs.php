<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\Traits\LogsActivity;

class UnitLogs extends Model
{
    use HasFactory;

    protected $guarded = [];

    protected $table = 'unit_logs';

    public $timestamps = null;

    use LogsActivity;

    protected static $logName = __CLASS__;
    protected static $logAttributes = ['*'];
    protected static $logOnlyDirty = true;
    protected static $logAttributesToIgnore = ['created_at', 'updated_at'];
    public function building()
    {
        return $this->belongsTo(Building::class, 'building_id');
    }


    public function unit()
    {
        return $this->belongsTo(Unit::class, 'unit_id');
    }

    public function triggeredBy()
    {
        return $this->belongsTo(User::class, 'triggered_by');
    }
}
