<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class BuildingPackageAddon extends Model
{
    //
    protected $table = "building_package_addons";

    public $timestamps = false;

    protected $guarded = [];

    protected $appends = ['addon_status', 'addon_owner_price', 'addon_resident_price', 'rid'];


    public function feature()
    {
        return $this->hasOne(Features::class, 'id', 'addons_id');
    }

    public function getRidAttribute()
    {
        return rand(10000000, 100000000);
    }

    public function addonsUnit()
    {
        return $this->hasMany(AddonsUnits::class, 'addon_id', 'addons_id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function buildingPackageGroup()
    {
        return $this->belongsTo(BuildingPackageGroup::class, 'package_group_id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function addon()
    {
        return $this->belongsTo(Addon::class, 'addons_id');
    }

    public function parent()
    {
        return $this->belongsTo(Addon::class, 'addons_id');
    }


    public function getAddonStatusAttribute()
    {
        if ($this->is_added == 1)
            $value = "Owner Bulk";
        else if ($this->is_added == 3)
            $value = "3rd Party Billing";
        else if ($this->is_added == 2)
            $value = "Resident Choice";
        else if ($this->is_added == 0)
            $value = "Off";

        if ($this->is_included == 1)
            $value = "Included";
        if ($this->is_included == 2)
            $value = "Off";

        return $value;
    }

    public function getAddonOwnerPriceAttribute()
    {
        $value = "";
        if ($this->is_included == 1)
            $value = "Included";
        else
            $value = formatMoneyWithCommas($this->owner_amount);

        if ($this->is_included == 2)
            $value = "-";

        return $value;
    }

    public function getAddonResidentPriceAttribute()
    {
        $value = "";
        if ($this->is_included == 1)
            $value = "Included";
        else
            $value = formatMoneyWithCommas($this->resident_amount);

        if ($this->is_included == 2)
            $value = "-";

        return $value;
    }
}
