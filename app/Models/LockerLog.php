<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

use App\Models\Locker;
use Spatie\Activitylog\Traits\LogsActivity;

class LockerLog extends Model
{
    //
    protected $table = "lockers_log";

    use LogsActivity;

    protected static $logName = __CLASS__;
    protected static $logAttributes = ['*'];
    protected static $logOnlyDirty = true;
    protected static $logAttributesToIgnore = ['created_at', 'updated_at'];
    protected $primaryKey = 'id';

    public $timestamps = false;

    public function building()
    {
        return $this->belongsTo(Building::class);
    }

    public function unit()
    {
        return $this->belongsTo(Unit::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function locker()
    {
        return $this->belongsTo(Locker::class);
    }

    public function package()
    {
        return $this->belongsTo(LockerPackage::class, 'locker_package_id');
    }

    public function screen()
    {
        return $this->belongsTo(LockerScreen::class, 'locker_screen_id');
    }

    static public function log($building_id, $locker_id, $locker_package_id, $user_id, $event_type, $unit_id = null, $token_id = null, $hid_id = null)
    {
        if ($locker = Locker::select('locker_screen_id')->find($locker_id)) {
            $locker_screen_id = $locker->locker_screen_id;
        } else {
            $locker_screen_id = null;
        }
        $log = new LockerLog;
        $log->building_id = $building_id;
        $log->locker_screen_id = $locker_screen_id;
        $log->locker_id = $locker_id;
        $log->locker_package_id = $locker_package_id;
        $log->user_id = $user_id;
        $log->event_type = $event_type;
        $log->timestamp = date('Y-m-d H:i:s');
        $log->unit_id = $unit_id;
        $log->token_id = $token_id;
        $log->hid_id = $hid_id;
        $log->save();


        /*
        switch($event_type) {
        case 'dropoff':
            $event_type = '28';
            break;
        default:
            $event_type = '29';
            break;
        }
        $e_log = new EntryLog;
        $e_log->timestamp = $log->timestamp;
        $e_log->building_id = $log->building_id;
        $e_log->device_id = 18;
        $e_log->device = $log->locker_id;
        $e_log->device_log_id = $log->id;
        $e_log->event_type = $event_type;
        $e_log->video_file = '2021-11-17_10-20-09_D2f.mp4';
        $e_log->save();
         */
    }
}
