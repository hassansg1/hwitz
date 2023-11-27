<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Features extends Model
{
    //
    protected $table = "addons";
    const CREATED_AT = 'created';
    const UPDATED_AT = 'modified';


    public function buildingPackageAddon()
    {
        $this->hasMany(BuildingPackageAddon::class,'addons_id','id');
    }

    public function residentdiscountitems()
    {
        return $this->morphMany('App\ResidentDiscountsItems', 'itemsable');
    }

    public function receivers()
    {
        return $this->morphMany(ProfitReceivers::class, 'receiveable');
    }
}
