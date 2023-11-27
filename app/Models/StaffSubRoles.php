<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\Traits\LogsActivity;

class StaffSubRoles extends Model
{
    use HasFactory;

    protected $table = "users_sub_roles_buildings";
    protected $guarded = [];

    use LogsActivity;

    protected static $logName = __CLASS__;
    protected static $logAttributes = ['*'];
    protected static $logOnlyDirty = true;
    protected static $logAttributesToIgnore = ['created_at', 'updated_at'];
    public function createdBy()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function subRole()
    {
        return $this->belongsTo(SubRoles::class, 'sub_role', 'alias');
    }

    public function building()
    {
        return $this->belongsTo(Building::class, 'building_id');
    }
}
