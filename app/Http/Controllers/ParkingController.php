<?php

namespace App\Http\Controllers;

use App\Models\Building;
use App\Models\BuildingsUsers;
use App\Models\Configurations;
use App\Models\Token;
use App\Models\Unit;
use App\Models\UnitHistory;
use App\Models\UnitUser;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Svg\Tag\Rect;

class ParkingController extends Controller
{
    public function getParkingBuildings(){
        $buildings = getBuildingsByOwner(Auth::id(), 'Parking');

        return response()->json($buildings);
    }

    public function index(Request $request){
        $building_id = $request->building_id;

        $units = Unit::with(['unittype','users','building'])->where('building_id',$building_id)->where('is_physical',1)->get()->toArray();

        foreach($units as $key => $unit){

            $unittype = $unit['unittype'] ?? false;
            
            if($unittype) $maxOccupancy = $unittype['num_users'];
            else $maxOccupancy = 2;

            $units[$key]['maxOccupancy'] = $maxOccupancy;
            $user_id = isset($unit['users']) && count($unit['users']) > 0 ? array_column($unit['users'], 'id') : 0;
            if($user_id == 0){
                $units[$key]['user1'] = [];
                // if($request->type != 'resident') unset($units[$key]);
                continue;
            }
            $users = User::
                            // select(DB::raw('CONCAT(users.firstname, COALESCE(units.unit_no,"") ) as user'))
                        // select(DB::raw('CONCAT(users.firstname, " ", users.lastname, " - " , COALESCE(units.unit_no,"") , " (", usertypes.name , ")" ) as full_name'),'users.*','units.unit_no','usertypes.name as usertype')
                        select(DB::raw('CONCAT(users.firstname, " ", users.lastname, " - " , COALESCE(units.unit_no,"") , " (", usertypes.name , ")" ) as full_name'),'users.id','users.parent_unit_id','users.profile_picture','users.firstname','users.lastname','usertypes.name as usertype_name')
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
            
            $parent_unit_ids = $users->pluck('parent_unit_id')->toArray();

            if($request->type && $request->type == 'resident'){
                // dd(count($parent_unit_ids) > 0 && in_array($unit['id'],$parent_unit_ids));
                if (count($parent_unit_ids) > 0 && in_array($unit['id'],$parent_unit_ids)){
                    unset($units[$key]);
                    continue;
                }
                else if(!containsInteger($parent_unit_ids)) {
                    unset($units[$key]);
                    continue;
                }
            }else{
                if(count($parent_unit_ids) == 0) {
                    // unset($units[$key]);
                    continue;
                }
                else if (count($parent_unit_ids) > 0 && in_array($unit['id'],$parent_unit_ids)){
                    continue;
                }
                else if( !containsOnlyNull($parent_unit_ids)){
                    unset($units[$key]);
                    continue;
                }
                
            }
        }
        $units = array_values($units);
        
        return response()->json($units);
    }

    public function loadUsers(Request $request){
        $user_ids = BuildingsUsers::select('user_id')->where('building_id',$request->building_id)->pluck('user_id')->toArray();
        $units_user_ids = Unit::select('units_users.user_id')
                ->join('units_users','units_users.unit_id','=','units.id')
                ->where('units.building_id',$request->building_id)
                ->pluck('units_users.user_id')->toArray();

        $user_ids = array_merge($user_ids,$units_user_ids);
        // dd($user_ids);
        $users = User::
                    // select(DB::raw('CONCAT(users.firstname, COALESCE(units.unit_no,"") ) as user'))
                // select(DB::raw('CONCAT(users.firstname, " ", users.lastname, " - " , COALESCE(units.unit_no,"") , " (", usertypes.name , ")" ) as full_name'),'users.*','units.unit_no','usertypes.name as usertype')
                // select(DB::raw('CONCAT(users.firstname, " ", users.lastname, " - " , COALESCE(units.unit_no,"") , " (", usertypes.name , ")" ) as full_name'),'users.id','users.parent_unit_id')
                // select(DB::raw('CONCAT(users.firstname, " ", users.lastname, " - " , COALESCE(units.unit_no,"") , " (", usertypes.name , ")" ) as full_name'),'users.id','users.parent_unit_id','users.profile_picture','users.firstname','users.lastname','usertypes.name as usertype_name')
                select(DB::raw('CONCAT(users.firstname, " ", users.lastname, " - ", 
                CASE 
                    WHEN users.parent_unit_id IS NULL THEN "(Non Resident)"
                    ELSE CONCAT(COALESCE(units.unit_no, ""), " (", usertypes.name, ")")
                END) as full_name'),'users.id','users.parent_unit_id','users.profile_picture','users.firstname','users.lastname','usertypes.name as usertype_name')
                ->leftJoin('units_users','users.id','=','units_users.user_id')
                ->leftJoin('units',function($q) use ($request)  {
                    $q->on('units.id','=','units_users.unit_id');
                    $q->where('units.building_id',$request->building_id);
                })
                ->leftJoin('usertypes','users.usertype_id','=','usertypes.id')
                ->whereIn('users.id',$user_ids)
                ->orderBy('firstname','asc')
                ->groupBy('users.id')
                ->get();
        // $users = User::with(['units'=>function($q) use ($request) {
        //     $q->select('unit_no');
        //     $q->where('building_id',$request->building_id)->first();
        // },'usertype'])->whereIn('id',$user_ids)->get();

        return response()->json($users);
    }
    
    public function assignParking(Request $request){


        $request->validate([
            'unit_id' => 'required',
            'user' => 'required',
            'is_handicapped' => 'sometimes'
        ]);

        $unit = Unit::with(['unittype'])->where('id',$request->unit_id)->first();

        if($unit && $unit->unittype) $maxOccupancy = $unit->unittype->num_users;
        else $maxOccupancy = 2;
        Unit::where('id',$request->unit_id)->update(['is_handicapped' => $request->is_handicapped]);

        $currentOccupants = UnitUser::where('unit_id',$request->unit_id)->count();
        if($currentOccupants > $maxOccupancy || count($request->user) > $maxOccupancy){
            return response()->json([
                'status' => 'error',
                'message' => 'You cannot assign more than ' . $maxOccupancy . ' users in a unit'
            ]);
        }

        

        if(count($request->user) > 0){
            $users = $request->user;
            $users = array_column($users, 'parent_unit_id');
            
            if(count($users) > 0 && count(array_unique($users)) > 1){
                return response()->json([
                    'status' => 'error',
                    'message' => 'Cannot share unit among these users'
                ]);
            }
            UnitUser::where('unit_id',$request->unit_id)->delete();
            foreach($request->user as $user){
                UnitUser::updateOrCreate([
                    'unit_id' => $request->unit_id,
                    'user_id' => $user['id']
                ],[
                    'unit_id' => $request->unit_id,
                    'user_id' => $user['id']
                ]);
                // addUnitLogs(Null, $building->id, 21, 'One Time Invoice', $title, $user->id);
                UnitHistory::addUserUnitistory($request->unit_id, $user['id']);
            }
        }
        
        return 1;
    }

    public function vacateResidents(Request $request, $id){
        $request->validate([
            'checkedUsers' => 'required'
        ]);

        if(count($request->checkedUsers) > 0){
            foreach($request->checkedUsers as $user_id){
                UnitUser::where('unit_id',$id)->where('user_id' , $user_id)->delete();
                // Token::addUserTokenHistory($user_id, $id, session('building_id'), Auth::id());
                UnitHistory::update_user_unit_history($id, $user_id);
            }
        }
        return 1;
    }
}
