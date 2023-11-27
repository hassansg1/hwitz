<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\Traits\LogsActivity;

class LinkAblesItems extends Model
{
    //
    protected $table = "linkables_itemables";

    protected $guarded = [];

    public $timestamps = null;

    use LogsActivity;

    protected static $logName = __CLASS__;
    protected static $logAttributes = ['*'];
    protected static $logOnlyDirty = true;
    protected static $logAttributesToIgnore = ['created_at', 'updated_at'];
    /**
     * @return \Illuminate\Database\Eloquent\Relations\MorphTo
     */
    public function itemable()
    {
        return $this->morphTo();
    }
}
