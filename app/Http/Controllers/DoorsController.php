<?php

namespace App\Http\Controllers;

use App\Libs\HID;
use App\Models\Asset;
use App\Models\AssetRelock;
use App\Models\Building;
use App\Models\Device;
use App\Models\Schedule;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class DoorsController extends Controller
{
    public function index(Request $request, $bid = 0)
    {
        $assets =  Asset::with('schedule')->where('building_id', $bid)->orderBy('name', 'asc')->get();
        
        return response()->json(['assets' => $assets , 'schedules' => getAllSchedules()]);
    }

    public function doorStatus(Request $request, $asset_id = '', $action = '')
    {

        $door = new HID;

        if ($asset_id) {

            if ($action) {
                echo $door->hidAction(array('hid' => $asset_id, 'action' => $action));

                $building_id = $request->session()->get('building_id');

                DB::table('system_logs')->insert(
                    [
                        'building_id' => isset($building_id) ? $building_id : null,
                        'unit_id' => null,
                        'entity_id' => $asset_id,
                        'entity_name' => 'Building Entrance',
                        'action_name' => strtoupper($action),
                        'triggered_at' => date('Y-m-d H:i:s'),
                        'triggered_by' => Auth::user()->id,
                        'notes' => '{"url":"'.config('app.url').'"}',
                        'ip_address' => $request->ip(),
                        'user_agent' => $request->header('user-agent')
                    ]
                );

                switch (strtoupper($action)) {
                case 'UNLOCK':
                    $relock = new AssetRelock();
                    $relock->unlock($asset_id);
                    break;
                case 'LOCK':
                    $relock = new AssetRelock();
                    $relock->lock($asset_id);
                    break;
                }
                
            } else {
                echo $door->getDoorState($asset_id);
            }
            exit;
        }
    }

    public function assignSchedule($asset_id, $schedule_id){

        $asset = Asset::find($asset_id);
        if(!isset($asset)) return response()->json(['status' => 'warning' , 'message'=>'Unable to find door']);

        $brivo_schedule_id = $asset->brivo_schedule_id;
        $schedule = Schedule::find($schedule_id);
        if(!isset($schedule)) return response()->json(['status' => 'warning' ,'message'=>'Selected schedule not found.']);
        
        if($asset->model_id == 155 && $brivo_schedule_id){
            updateScheduleInBrivo($brivo_schedule_id, $schedule);

        }

        $asset->update([
            'schedule_id' => $schedule_id 
        ]);

        return response()->json(['status' => 'success' ,'message'=>'Schedule assigned succesfully.']);
    }
}
