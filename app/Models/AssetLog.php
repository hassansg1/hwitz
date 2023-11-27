<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\Traits\LogsActivity;

class AssetLog extends Model
{
    protected $table = 'assets_log';
    protected $guarded = [];
    public $timestamps = null;

    use LogsActivity;

    protected static $logName = __CLASS__;
    protected static $logAttributes = ['*'];
    protected static $logOnlyDirty = true;
    protected static $logAttributesToIgnore = ['created_at', 'updated_at'];
    public function building()
    {
        return $this->belongsTo(Building::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function unit()
    {
        return $this->belongsTo(Unit::class, 'unit_id');
    }

    public function asset()
    {
        return $this->belongsTo(Asset::class, 'asset_id');
    }

    public function token()
    {
        return $this->belongsTo(Token::class, 'token_id');
    }

    public function entryLog()
    {
        return $this->hasOne(EntryLog::class, 'assets_log_id','id');
    }
}
