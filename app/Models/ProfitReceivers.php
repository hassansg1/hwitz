<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ProfitReceivers extends Model
{
    protected $table = "profit_receivers";

    public $timestamps = null;

    protected $guarded = [];

    public function receiveable()
    {
        return $this->morphTo();
    }

    /**
     * @param $model
     * @param $value
     * @param $buildingId
     * @return mixed
     */
    public static function getReceivers($model, $value, $buildingId)
    {
        return self::where([
            'receiveable_type' => $model,
            'receiveable_id' => $value,
            'building_id' => $buildingId,
        ])->orderBy('receiver_percent', 'asc')->get();
    }
}
