<?php

namespace App\Http\Controllers;

use App\Models\BuildingVitalSign;
use App\Models\Lockdown;
use App\Repos\LogsRepo;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LockdownController extends Controller
{
    public function changeLockdownStatus(Request $request, $building_id, $is_active){
        Lockdown::updateOrCreate(
            ['building_id' => $building_id],['is_active' => $is_active ? 1 : 0]
        );
        BuildingVitalSign::updateOrCreate([
            'building_id' => $building_id
        ],
        [
            'lockdown' => $is_active ? 1 : 0
        ]);
        return response()->json(['success' => true]);
    } 

    public function activateOrDeactivateLockdown(Request $request,$id,$value){
        Lockdown::where('building_id',$id)->update(['is_active'=>$value]);
        BuildingVitalSign::where('building_id',$id)->update(['lockdown'=>$value]);

        $action_name = $value ? 'Lockdown Activated' : 'Lockdown Deactivated';
        LogsRepo::addSystemLogs(null, $id,'Lockdown',$action_name,[],Auth::id());
        return 1;
    }
}
