<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\Traits\LogsActivity;

class Signdoc extends Model
{
    use HasFactory;

    protected $table = 'signdoc';

    use LogsActivity;

    protected static $logName = __CLASS__;
    protected static $logAttributes = ['*'];
    protected static $logOnlyDirty = true;
    protected static $logAttributesToIgnore = ['created_at', 'updated_at'];

    public function packet()
    {
        return $this->belongsTo(Packets::class, 'packet_id');
    }

    public function signUser()
    {
        return $this->belongsTo(User::class, 'current_sign_user');
    }
}
