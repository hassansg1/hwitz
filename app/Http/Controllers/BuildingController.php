<?php

namespace App\Http\Controllers;

use App\Models\Building;
use App\Models\BuildingNotifyEmail;
use App\Models\BuildingsUsers;
use App\Models\BuildingVitalSign;
use App\Models\LinkedBuildings;
use App\Models\Tasks;
use App\Models\Unit;
use App\Models\UnitTypes;
use App\Models\UnitUser;
use App\Models\User;
use App\Models\UserTypes;
use App\Repos\LogsRepo;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Laravel\Ui\Presets\React;
use Twilio\TwiML\Voice\Task;

class BuildingController extends Controller
{
    private $staff_usertypes = array('Agent', 'Custodian', 'Mail', 'Maintenance', 'Manager', 'First Responder');

    public function getBuildings(Request $request)
    {

        $building_id = $request->session()->get('building_id') ?? 0;

        $building_id_array = [];
        $buildings_array = [];
        $buildings = Building::with('units.users')->select('buildings.*', 'lockdowns.is_active as lockdown')->leftJoin('lockdowns', 'lockdowns.building_id', '=', 'buildings.id')->where('status', 1)->where('name', '!=', '');

        $buildings = $buildings->orderBy('name', 'asc')->get();
        if ($request->has('assign_building_flag')) {
            $assigned_building_flags = BuildingsUsers::where('user_id', $request->assign_building_flag)->get()->pluck('building_id')->toArray();
        }
        $usertype_ids = UserTypes::where('status', 1)->whereIn('name', $this->staff_usertypes)->pluck('id')->toArray();
        foreach ($buildings as $building) {
            $allUsers = [];
            $building_users = BuildingsUsers::select('users.*')->leftJoin('users', 'users.id', '=', 'buildings_users.user_id')
                ->whereIn('users.usertype_id', $usertype_ids)
                ->where('buildings_users.building_id', $building['id'])->distinct()->get();
            $building['staff_users'] = $building_users;

            $unverifiedUsers = $building_users->filter(function ($unverifiedUser) {
                return $unverifiedUser->email_verified == 0 || $unverifiedUser->mobile_verification == 'No';
            })->count();

            foreach ($building->units as $unit) {
                $unverifiedUsers += $unit->users->filter(function ($unverifiedUser) {
                    return $unverifiedUser->email_verified == 0 || $unverifiedUser->mobile_verification == 'No';
                })->count();
            }
            $building['unverifiedUsers'] = $unverifiedUsers;
            // $building['unverifiedUsers'] = 0;

            $urgentTasks = Tasks::where('building_id', $building->id)->where('priorities', 3)->where('task_state', 'O')->count();
            $overDue = Tasks::where('building_id', $building->id)->where('task_state', 'O')->whereDate('expiry', '<', Carbon::now())->count();

            $building['urgentTasks'] = $urgentTasks;
            $building['overDue'] = $overDue;

            if ($request->has('assign_building_flag')) {
                $building['is_assigned_to_user'] = false;
                if (in_array($building['id'], $assigned_building_flags)) $building['is_assigned_to_user'] = true;
            }
            $maxUsers = getConfigurations("maxUsersParkingStorage");
            if ($maxUsers == "") $maxUsers = 2;

            if ($request->has('unit_users_id')) {
                foreach ($building->units as $unit) {
                    $count = UnitUser::where('unit_id', $unit['id'])->count();
                    if ($count >= $maxUsers) $unit['$isDisabled'] = true;
                }
            }
        }

        return response()->json(['data' => $buildings]);
    }

    public function getBuildingsForUser()
    {
        if (Auth::user()->usertype_id == 15)
            $buildings_array = Building::where('user_id', Auth::user()->id)->where('name', '!=', '')->orderBy('name', 'asc')->where('status', 1)->get();
        else
            $buildings_array = Building::where('name', '!=', '')->where('status', 1)->orderBy('name', 'asc')->get();

        return response()->json([
            "buildings" => $buildings_array
        ]);
    }

    public function loadBuildingDetails($buildingId)
    {
        $building = Building::with('user', 'doors', 'appliance', 'laundry', 'lockers', 'units.users', 'assets')->where('id', $buildingId)->first();

        return response()->json([
            "building" => $building
        ]);
    }

    public function onlyLoadBuildingDetails($buildingId)
    {
        $building = Building::where('id', $buildingId)->first();

        return response()->json([
            "building" => $building
        ]);
    }

    public function getEntriesPerPage()
    {
        $enrtiesPerPage = getEntriesPerPage();
        return response()->json([
            "enrtiesPerPage" => $enrtiesPerPage
        ]);
    }

    public function getBuildingsUserTypes()
    {
        $userTypes = UserTypes::whereIn('id', [1, 4, 12, 13, 15, 21, 22, 24, 30, 31, 32, 33,])->where('status', 1)->get();

        return response()->json([
            "usertypes" => $userTypes
        ]);
    }

    public function getAssignedBuildingsOfAStaffUser($id)
    {
        $building_users = BuildingsUsers::select('buildings.name', 'buildings.id')->leftJoin('buildings', 'buildings.id', '=', 'buildings_users.building_id')
            ->where('buildings_users.user_id', $id)->distinct()->get();
        return response()->json([
            "buildings" => $building_users
        ]);
    }

    public function getUnitsOfABuilding(Request $request, $id)
    {
        $units = Unit::where('building_id', $id)->where('status', 1)->orderBy('unit_no', 'asc');
        if ($request->isPhysical) {
            $units = $units->where('is_physical', $request->isPhysical);
        }
        $units = $units->get();
        return response()->json([
            "status" => true,
            "units" => $units
        ]);
    }

    public function assignBuildings(Request $request, $id)
    {
        $requestData = $request->all();
        foreach ($requestData as $data) {
            $building_user_data = BuildingsUsers::where(['building_id' => $data['building_id'], 'user_id' => $id])->first();
            if ($data['is_assigned_to_user'] && !$building_user_data) {
                BuildingsUsers::create([
                    'building_id' => $data['building_id'],
                    'user_id' => $id
                ]);
            } else if (!$data['is_assigned_to_user'] && $building_user_data) {
                $building_user_data->delete();
            }
        }
    }

    public function setBuildingIdInSession(Request $request)
    {
        session()->put('building_id', $request->building_id);
        $building = getBuildingDetails($request->building_id);
        return response()->json(['building_id' => $request->building_id, 'building' => $building]);
    }

    public function clearBuildingInSession(Request $request)
    {
        session()->remove('building_id');
        return response()->json(['building_id' => $request->building_id]);
    }

    public function buildingVitalSigns(Request $request)
    {
        $bld = $request->building ?? '';
        $is_admin = $request->is_admin;
        $auth_id = $request->auth_id ?? 0;
        $userLevel = $request->user_level ?? '';
        $view = $request->admin == 'true' ? 'vital_signs.table' : 'vital_signs.index';

        $Owner_id = '';
        $building_id_array = array();
        $buildings_array = [];
        $buildings = Building::select('buildings.name', 'buildings.address1', 'buildings.is_register', 'buildings.created', 'buildings.status', 'users.firstname', 'users.lastname', 'usertypes.name as usertype_name', 'building_vital_sign.*')->leftJoin('users', 'buildings.user_id', '=', 'users.id')
            ->join('usertypes', 'usertypes.id', '=', 'users.usertype_id')
            ->leftJoin('building_vital_sign', 'building_vital_sign.building_id', '=', 'buildings.id')
            ->where('buildings.name', '!=', '')->where('buildings.status', 1)
            ->when($request->building_id, function ($q) use ($request) {
                $q->where('building_id', $request->building_id);
            });

        if ($is_admin == 1) {
            if ($bld) {
                $buildingss = Building::where('id', $bld)->first();
                if (isset($buildingss['user_id'])) {
                    $Owner_id = $buildingss['user_id'];

                    $buildings_array = Building::select('id')->where('user_id', $Owner_id)->pluck('id')->toArray();
                }
                if (count($buildings_array) > 0) {
                    $building_id_array = $buildings_array;
                }
            }
        } else {
            $bld_id = BuildingsUsers::select('building_id')->where('user_id', $auth_id)->pluck('building_id')->toArray();
            if (count($bld_id) > 0) {
                $building_id_array = $bld_id;
                $buildings = $buildings->whereIn('buildings.id', $building_id_array);
            } else {
                $buildings = $buildings->whereIn('buildings.id', $building_id_array);
            }
        }

        if ($bld) {
            if ($userLevel != 'admin_id' && $userLevel != 'landlord_id' && $userLevel != 'manager_id') {
                $buildings = $buildings->where('buildings.user_id', $auth_id)->whereIn('buildings.id', $building_id_array);
            }
            $buildings = $buildings->whereIn('buildings.id', $building_id_array);
        }

        $buildings = $buildings->orderBy('name', 'asc')->get();
        $last_updated = BuildingVitalSign::select('updated_at')->where('building_id', 1)->first();

        $last_updated_timestamp = strtotime($last_updated->updated_at);


        $fifteen_minutes_ago = strtotime('-15 minutes');
        if ($last_updated_timestamp < $fifteen_minutes_ago) {
            $color = 'red';
        } else {
            $color = 'green';
        }

        dde(config('app.PHP_DATE_TIME_FORMAT'), 'lastupdate.log');
        dde(Carbon::parse($last_updated->updated_at)->format(config('app.PHP_DATE_TIME_FORMAT')), 'lastupdate.log');

        return response()->json([
            // 'html' => view($view)->with(
            // [
            'lists' => $buildings,
            'bldg_id' => $bld,
            'last_updated' => $last_updated ? Carbon::parse($last_updated->updated_at)->format(config('app.PHP_DATE_TIME_FORMAT')) : Carbon::now()->format(config('app.PHP_DATE_TIME_FORMAT')),
            'is_admin' => $is_admin,
            'color' => $color
            // 'id' => $id,
            // 'route' => $request->route,
            // ])->render(),
        ]);
    }

    public function alertSettings(Request $request, $building_id, $kind)
    {

        if ($building_id) {
            $notificationSummary = BuildingNotifyEmail::where('building_id', $building_id)->where('kind', $kind)->get();
            $user_types = array('Maintenance', 'Manager', 'Custodian', 'Commercial', 'Mail', 'Master', 'Contractor');

            $unitTypes = UnitTypes::whereIn('name', $user_types)->get();
            foreach ($unitTypes as $key => $unit_type) {
                $unit_types[] = $unit_type['id'];
            }


            $unit_users = User::
            select('buildings_notifyemails.sms', 'buildings_notifyemails.email as nemail', 'buildings_notifyemails.kind', 'buildings_notifyemails.building_id', 'unittypes.name as unitype_name', 'users.id', 'users.firstname', 'users.profile_picture', 'users.middlename', 'users.lastname', 'users.mobile', 'users.email', 'users.email_verified', 'users.mobile_verification', 'users.usertype_id')
                ->leftJoin('units_users', 'units_users.user_id', '=', 'users.id')
                ->leftJoin('units', 'units.id', '=', 'units_users.unit_id')
                ->leftJoin('unittypes', 'unittypes.id', '=', 'units.unittype_id')
                ->leftJoin('buildings_notifyemails', function ($join) use ($building_id, $kind) {
                    $join->on('buildings_notifyemails.user_id', '=', 'users.id')
                        ->where('buildings_notifyemails.building_id', $building_id)
                        ->where('buildings_notifyemails.kind', $kind);
                })
                ->where('users.status', 1)
                ->where('status_delete', 0)
                ->where('units.building_id', $building_id)
                ->whereIn('units.unittype_id', $unit_types)
                ->groupBy('users.id')
                ->get();

            $owner = User::
            select('buildings_notifyemails.sms', 'buildings_notifyemails.email as nemail', 'buildings_notifyemails.kind', 'buildings_notifyemails.building_id', 'users.id', 'users.firstname', 'users.middlename', 'users.lastname', 'users.mobile', 'users.email', 'users.email_verified', 'users.mobile_verification')
                ->leftJoin('buildings', 'buildings.user_id', '=', 'users.id')
                ->leftJoin('buildings_notifyemails', function ($join) use ($building_id, $kind) {
                    $join->on('buildings_notifyemails.user_id', '=', 'users.id')
                        ->where('buildings_notifyemails.building_id', $building_id)
                        ->where('buildings_notifyemails.kind', $kind);
                })
                ->where('buildings.id', $building_id)
                ->where('users.status', 1)
                ->first();
            if ($owner) $owner['user_type'] = 'Owner';

            return response()->json(['unit_users' => $unit_users, 'owner' => $owner, 'notificationSummary' => $notificationSummary]);
        }
    }

    public function saveAlertSettings(Request $request, $building_id, $kind)
    {

        if ($request->has('owner')) {
            $owner = $request->owner ?? false;
            if ($owner) {
                BuildingNotifyEmail::updateOrCreate([
                    'building_id' => $building_id,
                    'user_id' => $owner['id'],
                    'kind' => $kind
                ], [
                    'sms' => $owner['sms'] ? 1 : 0,
                    'email' => $owner['nemail'] ? 1 : 0
                ]);
            }
        }

        if ($request->has('unit_users')) {
            $users = $request->unit_users ?? [];

            foreach ($users as $user) {
                BuildingNotifyEmail::updateOrCreate([
                    'building_id' => $building_id,
                    'user_id' => $user['id'],
                    'kind' => $kind
                ], [
                    'sms' => $user['sms'] ? 1 : 0,
                    'email' => $user['nemail'] ? 1 : 0
                ]);
            }
        }
    }

    public function saveFobSettings(Request $request, $building_id)
    {
        $require_fob_verification = $request->require_fob_verification;
        return Building::where('id', $building_id)->update(['require_fob_verification' => $require_fob_verification ? 1 : 0]);
    }

    public function saveBuildingSettings(Request $request, $building_id)
    {
        $updated = 0;
        $data = $request->all();
        unset($data['id']);
        unset($data['created']);
        unset($data['modified']);

        foreach ($data as $key => $value) {
            if (is_bool($value)) {
                $value = $value ? 1 : 0;
                $updated = Building::where('id', $building_id)->update([$key => $value]);
            } else {
                $updated = Building::where('id', $building_id)->update([$key => $value]);
            }
        }
        return $updated;


    }

    public function addLinkedBuildings(Request $request, $buildingId)
    {
        $linkedBuildings = $request->linkedBuildings;

        if ($linkedBuildings) {
            LinkedBuildings::where('building_id', $buildingId)->delete();

            foreach ($linkedBuildings as $linkedBuilding) {
                $lBuilding = new LinkedBuildings();
                $lBuilding->building_id = $buildingId;
                $lBuilding->linked_building_id = $linkedBuilding['id'];
                $lBuilding->save();
            }
            return 1;
        }
        return 0;
    }

    public function getLinkedBuildings($id)
    {
        $linkedBuildings = getLinkedBuildings($id);
        $parkingBuildings = [];
        $storageBuildings = [];
        foreach ($linkedBuildings as $linkedBuilding) {
            if ($linkedBuilding->linkedBuilding && $linkedBuilding->linkedBuilding->type == 'Parking') {
                $parkingBuildings[] = $linkedBuilding->linkedBuilding->only('id', 'name');
            } else if ($linkedBuilding->linkedBuilding && $linkedBuilding->linkedBuilding->type == 'Storage') {
                $storageBuildings[] = $linkedBuilding->linkedBuilding->only('id', 'name');
            }
        }

        return response()->json([
            'parking' => $parkingBuildings,
            'storage' => $storageBuildings
        ]);
    }

    public function updateBuildingImage(Request $request, $id)
    {
        $record = Building::find($id);
        $previous_image = $record->picture;
        $record->update(['picture' => $request->picture]);

        LogsRepo::addSystemLogs(null, $id, 'Building', 'Building Image Updated', ['previous_image' => $previous_image, 'new_image' => $request->picture], Auth::id());
        return $record;
    }

    public function getUserTypes($type)
    {
        if ($type == "staff") {
            $userTypeId = [24, 25, 30, 31, 12];
            if (hasPermission("manage_third_party_managers")) {
                $userTypeId[] = 9;
            }
        }
        $userTypes = UserTypes::whereIn("id", $userTypeId)->get();

        return response()->json([
            "status" => true,
            "data" => $userTypes
        ]);
    }
}
