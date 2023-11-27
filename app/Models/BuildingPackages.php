<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class BuildingPackages extends Model
{
    //
    protected $table = "building_package";

    public $timestamps = null;

    protected $guarded = [];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function package()
    {
        return $this->belongsTo(Packages::class, 'package_id');
    }

}
