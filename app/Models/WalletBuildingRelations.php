<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class WalletBuildingRelations extends Model
{
    //

    protected $table = "wallet_building_relations";

    protected $guarded = [];

    public $timestamps = [];

    public static $cartType = [
        2 => 'amenities',
        3 => 'additional_rent',
        4 => 'laundry',
    ];

    protected $appends = ['cart_type'];

    public function getCartTypeAttribute()
    {
        return self::$cartType[$this->trans_source_id];
    }

    public static function getWallet($buildingId, $walletType, $transSourceId)
    {
        $wallet = self::where([
            'building_id' => $buildingId,
            'default_area_status' => $walletType,
            'trans_source_id' => $transSourceId
        ])->orderBy('id', 'desc')->first();

        return $wallet;
    }

    public function wallet()
    {
        return $this->belongsTo(Wallets::class, 'wallet_id');
    }

    public function building()
    {
        return $this->belongsTo(Building::class, 'building_id');
    }
}
