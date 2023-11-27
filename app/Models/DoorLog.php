<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DoorLog extends Model
{
    protected $table = 'doors_log';
    protected $guarded = [];
    public $timestamps = null;

    public function building()
    {
        return $this->belongsTo(Building::class);
    }

    public function door()
    {
        return $this->belongsTo(Door::class, 'door_id');
    }

    public function entryLog()
    {
        return $this->hasOne(EntryLog::class, 'device_log_id', 'id')->whereIn('device_id', [4, 1]);
    }

    public function doorEventNames()
    {
        return $this->hasMany(DoorEventNames::class, 'event_type', 'event_type');
    }
}
