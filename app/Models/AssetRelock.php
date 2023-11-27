<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

use Illuminate\Support\Facades\Auth;

use App\Models\Building;
use App\Models\Devices\Asset;

class AssetRelock extends Model
{
    protected $table = 'assets_relock';
    protected $guarded = [];
    public $timestamps = false;

    // Remove any entries in the assets_relock table
    public function lock($asset_id) {
        $rows = self::where('asset_id',$asset_id)->get();
        if ($rows) {
            foreach ($rows as $row)
                $row->delete();
        }
    }

    public function unlock($asset_id) {
        $rec = self::select('building_id')->find($asset_id);
        if ($rec)
            $building_id = $rec->building_id;
        else
            $building_id = null;

        $relock_at = date('Y-m-d H:i:s', time()+ 7200);
        if ($building_id) {
            $rec = Building::select('relock_delay')->find($building_id);
            if ($rec) 
                $relock_at = date('Y-m-d H:i:s', time()+ ($rec->relock_delay * 60));
        }

        $relock = new AssetRelock();
        $relock->building_id = $building_id;
        $relock->asset_id = $asset_id;
        $relock->user_id = Auth::user()->id;
        $relock->relock_at = $relock_at;
        $relock->save();
    }
}
