<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\Traits\LogsActivity;

class LaundryBuildingOffer extends Model
{
    use HasFactory;

    protected $guarded = [];
    protected $table = 'laundry_building_offers';
    const CREATED_AT = 'created';
	const UPDATED_AT = 'modified';

    use LogsActivity;

    protected static $logName = __CLASS__;
    protected static $logAttributes = ['*'];
    protected static $logOnlyDirty = true;
    protected static $logAttributesToIgnore = ['created_at', 'updated_at'];
}
