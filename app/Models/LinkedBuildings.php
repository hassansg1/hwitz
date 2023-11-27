<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\Traits\LogsActivity;

class LinkedBuildings extends Model
{
    use HasFactory;

    protected $table = "linked_buildings";

    use LogsActivity;

    protected static $logName = __CLASS__;
    protected static $logAttributes = ['*'];
    protected static $logOnlyDirty = true;
    protected static $logAttributesToIgnore = ['created_at', 'updated_at'];
    public $timestamps = false;

    protected $guarded = [];

    public function linkedBuilding()
    {
        return $this->belongsTo('App\Models\Building','linked_building_id');
    }
}
