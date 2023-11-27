<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class UserTypes extends Model
{
    //
    protected $table = "usertypes";

    public $timestamps = null;

    protected $guarded = [];

    public static $admin = 1;
    public static $owner = 15;

    // public function accessPermissions()
    // {
    //     return $this->morphMany(ModelPermissions::class, 'modelable');
    // }

    public function getNameAttribute($value)
    {
        if ($this->id == 15)
            return "Portfolio Owner";
        else
            return $value;
    }

    public function childs()
    {
        return $this->hasMany(UserTypes::class, 'parent_id');
    }

    public function accessPermissions()
    {
        return $this->morphMany(ModelPermissions::class, 'modelable');
    }

    public function parent()
    {
        return $this->belongsTo(UserTypes::class, 'parent_id');
    }
}
