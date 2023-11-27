<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\Traits\LogsActivity;

class AddonsUnits extends Model
{
    //
    protected $table = "addons_units";

    use LogsActivity;

    protected static $logName = __CLASS__;
    protected static $logAttributes = ['*'];
    protected static $logOnlyDirty = true;
    protected static $logAttributesToIgnore = ['created_at', 'updated_at'];

    protected $guarded = [];

    public $timestamps = false;

    protected $appends = ["cart_label"];

    public function getCartLabelAttribute()
    {
        return TransactionLog::$cartLabelByCartType[$this->cart_type];
    }

    public function unit()
    {
        return $this->belongsTo(Unit::class);
    }
}
