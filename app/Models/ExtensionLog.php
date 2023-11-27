<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\Traits\LogsActivity;

class ExtensionLog extends Model
{
    protected $guarded = [];

    use LogsActivity;

    protected static $logName = __CLASS__;
    protected static $logAttributes = ['*'];
    protected static $logOnlyDirty = true;
    protected static $logAttributesToIgnore = ['created_at', 'updated_at'];
    public function package()
    {
        return $this->belongsTo(LockerPackage::class, 'locker_package_id');
    }

    public function locker()
    {
        return $this->belongsTo(Locker::class, 'locker_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'extended_by');
    }

    /**
     * @param $lockerPackageId
     * @param $oldTime
     * @param $newTime
     * @param $lockerId
     * @param $userId
     */
    public static function addNew($lockerPackageId, $oldTime, $newTime, $lockerId, $userId)
    {
        ExtensionLog::create(
            [
                'locker_id' => $lockerId,
                'locker_package_id' => $lockerPackageId,
                'extended_by' => $userId,
                'previous_time' => $oldTime,
                'extended_time' => $newTime,
            ]);
    }
}
