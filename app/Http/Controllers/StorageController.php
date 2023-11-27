<?php

namespace App\Http\Controllers;

use App\Models\Building;
use App\Models\Configurations;
use App\Models\Unit;
use App\Models\UnitUser;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class StorageController extends Controller
{
    public function index(Request $request){
        $building_id = $request->building_id;
        

        $units = Unit::with(['users','unittype'])->where('building_id',$building_id)->get()->toArray();

        foreach($units as $key => $unit){

            $unittype = $unit['unittype'] ?? false;
            
            if($unittype) $maxOccupancy = $unittype['num_users'];
            else $maxOccupancy = 2;

            $units[$key]['maxOccupancy'] = $maxOccupancy;
            $unit_users = UnitUser::where('unit_id',$unit['id'])->get();
            if(count($unit_users) > 0) $units[$key]['occupied'] = 1;
            else $units[$key]['occupied'] = 0;

            $user_id = isset($unit['users']) && count($unit['users']) > 0 ? array_column($unit['users'], 'id') : 0;
            if($user_id == 0) continue;
            $users = User::
                        // select(DB::raw('CONCAT(users.firstname, " ", users.lastname, " - " , COALESCE(units.unit_no,"") , " (", usertypes.name , ")" ) as full_name'),'users.id','users.parent_unit_id','users.profile_picture')
                        select(DB::raw('CONCAT(users.firstname, " ", users.lastname, " - ", 
                            CASE 
                                WHEN users.parent_unit_id IS NULL THEN "(Non Resident)"
                                ELSE CONCAT(COALESCE(units.unit_no, ""), " (", usertypes.name, ")")
                            END) as full_name'),'users.id','users.parent_unit_id','users.profile_picture','users.firstname','users.lastname','usertypes.name as usertype_name')
                        ->leftJoin('units_users', function($q) use ($unit) {
                            $q->on('users.id','=','units_users.user_id');
                            // $q->where('units_users.user_id','=',$unit['id']);
                        })
                        ->leftJoin('units',function($q) use ($request)  {
                            $q->on('units.id','=','units_users.unit_id');
                            $q->where('units.building_id',session('building_id'));
                        })
                        ->leftJoin('usertypes','users.usertype_id','=','usertypes.id')
                        ->whereIn('users.id',$user_id)
                        ->orderBy('firstname','asc')
                        ->groupBy('users.id')
                        ->get();

            $units[$key]['user1'] = $users;
        }
        return response()->json($units);
    }

    public function getStorageBuildings(){
        $buildings = getBuildingsByOwner(Auth::id(), 'Storage');

        return response()->json($buildings);
    }

    public function vacateResident($id){
        return UnitUser::where('unit_id',$id)->delete();
    }

    public function loadUsers(Request $request){
        $building_id = $request->building_id;
        $unit_ids = Unit::where('building_id',$building_id)->pluck('id')->toArray();
        
        $users = User::
                        select(DB::raw("CONCAT(users.firstname, ' ', users.lastname) as fullname") , 'users.id','units_users.unit_id')
                        ->leftJoin('units_users','users.id','=','units_users.user_id')
                        ->whereIn('units_users.unit_id',$unit_ids)
                        ->get();
        return $users;
    }
    public function assignStorage(Request $request){
        $request->validate([
            'unit_id' => 'required',
            'user' => 'required'
        ]);

        $unit_user = UnitUser::where('unit_id',$request->unit_id)->where('user_id',$request->user)->first();

        if(!$unit_user){
            return UnitUser::create([
                'unit_id' => $request->unit_id,
                'user_id' => $request->user
            ]);
        }
        return 0;
    }
}
