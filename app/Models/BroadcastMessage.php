<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\Traits\LogsActivity;

class BroadcastMessage extends Model
{
    use HasFactory;

    protected $table = 'broadcast_messages';
    protected $guarded = [];
    public $appends = ['date'];
    use LogsActivity;

    protected static $logName = __CLASS__;
    protected static $logAttributes = ['*'];
    protected static $logOnlyDirty = true;
    protected static $logAttributesToIgnore = ['created_at', 'updated_at'];
    /**
     * The name of the "created at" column.
     *
     * @var string
     */
    const CREATED_AT = 'created';

    /**
     * The name of the "updated at" column.
     *
     * @var string
     */
    const UPDATED_AT = 'modified';

    public function verification(){
        return $this->hasOne(VerificationSequence::class,'message_id');
    }

    public function getDateAttribute(){
        return date('Y-m-d');
    }
}
