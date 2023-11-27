<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AssetUnit extends Model
{

    protected $table = 'assets_units';
    const CREATED_AT = 'created';
    const UPDATED_AT = 'modified';

    protected $guarded = [];

    public function asset()
    {
        return $this->belongsTo(Asset::class, 'asset_id');
    }

    public function unit()
    {
        return $this->belongsTo(Unit::class, 'unit_id');
    }
}
