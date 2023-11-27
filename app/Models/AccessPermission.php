<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\Traits\LogsActivity;

class AccessPermission extends Model
{
    //
    protected $table = "access_permissions";

    use LogsActivity;

    protected static $logName = __CLASS__;
    protected static $logAttributes = ['*'];
    protected static $logOnlyDirty = true;
    protected static $logAttributesToIgnore = ['created_at', 'updated_at'];

    public $timestamps = null;
    protected $guarded = [];

    public function category()
    {
        return $this->belongsTo(PermissionCategories::class, 'category_id');
    }
}
