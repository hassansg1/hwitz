<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Crypt;
use Spatie\Activitylog\Traits\LogsActivity;

class LockerPackage extends Model
{

    public $timestamps = null;

    protected $appends = ['enid', 'pickup_video', 'expiration_days'];

    use LogsActivity;

    protected static $logName = __CLASS__;
    protected static $logAttributes = ['*'];
    protected static $logOnlyDirty = true;
    protected static $logAttributesToIgnore = ['created_at', 'updated_at'];

    public function getExpirationDaysAttribute()
    {
        return $this->building->expiration_days ?? 5;
    }

    public function getEnidAttribute()
    {
        return Crypt::encryptString($this->id);
    }

    public function locker()
    {
        return $this->belongsTo(Locker::class);
    }

    public function provider()
    {
        return $this->belongsTo(MailServiceProvider::class, 'provider_id','id');
    }

    public function receiver()
    {
        return $this->belongsTo(User::class, 'receiver_id');
    }

    public function receivedBy()
    {
        return $this->belongsTo(User::class, 'received_by');
    }

    public function building()
    {
        return $this->belongsTo(Building::class, 'building_id');
    }

    public function manager()
    {
        return $this->building->user ?? '';
    }

    public function logs()
    {
        return $this->hasMany(LockerLog::class, 'locker_package_id');
    }

    public function pickupLog()
    {
        return $this->logs()->where('event_type', 'pickup')->first();
    }

    public function dropOffLog()
    {
        return $this->logs()->where('event_type', 'dropoff')->first();
    }

    public function getPickUpEntryLog($pickUp)
    {
        $entrylog = EntryLog::where([
            'device_id' => 18,
            'device' => $this->locker_id,
            'event_type' => 29,
            'device_log_id' => $pickUp->id,
        ])->first();

        return $entrylog;
    }

    public function getDropOffEntryLog($log)
    {
        $entrylog = EntryLog::where([
            'device_id' => 18,
            'device' => $this->locker_id,
            'event_type' => 28,
            'device_log_id' => $log->id,
        ])->first();

        return $entrylog;
    }

    public function getPickupVideoAttribute()
    {
        $pickUp = $this->pickupLog();
        if ($pickUp) {
            $entrylog = $this->getPickUpEntryLog($pickUp);
            if ($entrylog)
                return $entrylog->video_file;
        }

        return null;
    }

    public function getPickupPersonPicturesAttribute()
    {
        $pickUp = $this->pickupLog();
        if ($pickUp) {
            $entrylog = $this->getPickUpEntryLog($pickUp);
            if ($entrylog) {
                $image = $entrylog->img_file;
                if ($image) {
                    return $this->getFiveImages($image);
                }
            }
        }

        return [];
    }

    public function getDeliveryPersonPicturesAttribute()
    {
        $log = $this->dropOffLog();
        if ($log) {
            $entrylog = $this->getDropOffEntryLog($log);
            if ($entrylog) {
                $image = $entrylog->img_file;
                if ($image) {
                    return $this->getFiveImages($image);
                }
            }
        }

        return [];
    }

    public function getFiveImages($image)
    {
        $imagesArray = [];
        $image = explode('.', $image);
        $extension = $image[1] ?? null;;
        $imageName = $image[0] ?? null;
        if ($imageName) {
            $imageName = substr($imageName, 0, -1);
            for ($index = 0; $index < 5; $index++) {
                $imagesArray[] = $imageName . $index . '.' . $extension;
            }

            return $imagesArray;
        }

        return [];
    }
}
