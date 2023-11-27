<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\Traits\LogsActivity;

class AchBatchItem extends Model
{
    use HasFactory;

    use LogsActivity;

    protected static $logName = __CLASS__;
    protected static $logAttributes = ['*'];
    protected static $logOnlyDirty = true;
    protected static $logAttributesToIgnore = ['created_at', 'updated_at'];

    public function building(){
        return $this->belongsTo(Building::class,'building_id');
    }

    public function achBatch(){
        return $this->belongsTo(AchBatch::class,'ach_batch_id');
    }

    public function receiver(){
        return $this->belongsTo(User::class,'receiver_id');
    }

    public function wallet(){
        return $this->belongsTo(Wallets::class,'wallet_id');
    }
}
