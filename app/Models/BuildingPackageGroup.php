<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class BuildingPackageGroup extends Model
{
    //
    protected $table = "building_package_group";

    public $timestamps = false;

    protected $guarded = [];

    protected $appends = ['group_status', 'rid'];


    public function group()
    {
        return $this->hasOne(FeatureGroups::class, 'id', 'group_id');
    }

    public function getRidAttribute()
    {
        return rand(10000000, 100000000);
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function buildingPackageAddOns()
    {
        return $this->hasMany(BuildingPackageAddon::class, 'package_group_id');
    }

    public function parent()
    {
        return $this->hasOne(FeatureGroups::class, 'id', 'group_id');
    }

    public function getGroupStatusAttribute()
    {
        if ($this->accessFor == 1)
            $value = "Owner Bulk";
        else if ($this->accessFor == 2)
            $value = "3rd Party Billing";
        else if ($this->accessFor == 3)
            $value = "Resident Choice";
        else
            $value = "";

        return $value;
    }
}
