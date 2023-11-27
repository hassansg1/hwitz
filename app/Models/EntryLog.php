<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class EntryLog extends Model
{
    //
    public $table = 'entry_log';

    public $timestamps = null;

    public static function getData($deviceId, $device, $eventType, $deviceLogId)
    {
        $log = EntryLog::where(
            [
                'device_id' => $deviceId,
                'device' => $device,
                'event_type' => $eventType,
                'device_log_id' => $deviceLogId,
            ]
        )->first();

        return $log;
    }
}
