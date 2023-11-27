<?php

namespace App\Http\Controllers;

use App\Models\AssetUnit;
use App\Models\Building;
use App\Models\BuildingToken;
use App\Models\MacVendor;
use App\Models\Token;
use App\Models\TokenHistory;
use App\Models\Unit;
use App\Models\UnitHistory;
use App\Models\UnitMac;
use App\Models\UnitMacHistory;
use App\Models\UnitUser;
use App\Models\User;
use App\Services\Parsers\ResidentListingParser;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ResidentController extends Controller
{
    public function index(Request $request)
    {

        $data = app(ResidentListingParser::class)->parse($request);

        return response()->json($data);

    }

    public function loadResidentData($id)
    {
        $user = User::with(['tokens', 'macs', 'units.building', 'usertype', 'unit'])->where('id', $id)->first();


        $result = UnitUser::
        select('units.id', 'units.created', 'units.unit_no', 'buildings.type', 'buildings.name')
            ->join('units', 'units.id', '=', 'units_users.unit_id')
            ->join('buildings', 'buildings.id', '=', 'units.building_id')
            ->where('units_users.user_id', $id)
            ->whereIn('buildings.type', ['Parking', 'Storage'])
            ->get();
        $parentUnit = $user->unit;
        if (!$parentUnit)
            $parentUnit = UnitUser::
            select('units.id', 'units.created', 'units.unit_no', 'buildings.type', 'buildings.name')
                ->join('units', 'units.id', '=', 'units_users.unit_id')
                ->join('buildings', 'buildings.id', '=', 'units.building_id')
                ->where('units_users.user_id', $id)
                ->whereIn('buildings.type', ['Residential'])
                ->first();

        $user['storageParkingData'] = $result;
        $user['parentUnit'] = $parentUnit;
        return $user;
    }

    public function getEntrancesList($unit_id)
    {
        $assets = AssetUnit::
        select('assets.name as asset_name', 'buildings.name as building_name')
            ->join('assets', 'assets.id', '=', 'assets_units.asset_id')
            ->join('buildings', 'buildings.id', '=', 'assets.building_id')
            ->where('assets_units.unit_id', $unit_id)
            ->orderBy('buildings.name')
            ->get();

        return response()->json([
            'status' => true,
            'html' => view('partials.entrance_lists')->with(
                [
                    'data' => $assets,
                ])->render(),
        ]);
    }

    public function addNewMacAddress(Request $request)
    {
        $request->validate([
            'mac_address' => 'required',
            'device_type' => 'required',
            'location' => 'required',
            'connection_type' => 'required',
            'other' => 'sometimes',
        ]);
        $data = $request->all();

        $sub_mac = substr($data['mac_address'], 0, 8);

        $macs_count = UnitMac::where('mac_address', $data['mac_address'])->count();
        if ($macs_count) {
            return response()->json(['status' => 'warning', 'message' => 'The mac address entered already exists, please recheck address or contact tech support']);
        }
        UnitMacHistory::create([
            'action_date' => date('Y-m-d H:i:s'),
            'action_by' => Auth::id(),
            'action' => 1,
            'unit_id' => $data['unit_id'],
            'mac_address' => $data['mac_address'],
        ]);
        $mac_exists = MacVendor::where('mac', $sub_mac)->first();
        if (empty($mac_exists)) {
            $mac_vendor_api = "http://api.macvendors.com/" . trim($data['mac_address'], "'");

            $ch = curl_init();

            curl_setopt($ch, CURLOPT_URL, $mac_vendor_api);
            curl_setopt($ch, CURLOPT_HEADER, 0);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
            curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);

            try {
                $result = curl_exec($ch);
            } catch (Exception $exc) {

            }
            curl_close($ch);

            if ($result) {
                MacVendor::create([
                    'mac' => $sub_mac,
                    'vendor' => $result
                ]);
            }
        }

        UnitMac::create([
            'unit_id' => $data['unit_id'],
            'mac_address' => $data['mac_address'],
            'device_type' => $data['device_type'],
            'description' => $data['device_type_value'] ?? '',
            'location' => $data['location'],
            'description' => $data['other'],
            'connection_type' => $data['connection_type'],
            'user_id' => $data['user_id'] ?? Auth::id(),
            'created_by' => Auth::id(),
            'modified_by' => Auth::id(),
            'modified' => date('Y-m-d H:i:s'),
        ]);

        $items = [
            'mac_address' => $data['mac_address'],
            'device_type' => $data['device_type']
        ];
        addUnitLogs($data['unit_id'], $data['building_id'], 8, 'Mac Address added', 'Mac Address added', Auth::id(), $data['user_id'] ?? Auth::id());
        return response()->json(['status' => 'success', 'message' => 'Mac Address saved.']);
    }

    public function getStorageAndParkingUnits($user_id, $building_id, $type)
    {
        $result = UnitUser::
        select('units.id', 'units.created', 'units.unit_no')
            ->join('units', 'units.id', '=', 'units_users.unit_id')
            ->join('buildings', 'buildings.id', '=', 'units.building_id')
            ->where('units_users.user_id', $user_id)
            ->where('buildings.type', $type)
            ->get();

    }

    public function addNewStorageAndParking(Request $request)
    {
        $request->validate([
            'unit_id' => 'required'
        ]);
        $response = UnitUser::updateOrCreate([
            'unit_id' => $request->unit_id,
            'user_id' => $request->user_id
        ]);
        return $response;
    }

    public function loadAllTokens($building_id, $user_id)
    {

        $building = Building::find($building_id);
        $all_tokens = BuildingToken::
        select('tokens.id', 'tokens.card_id')
            ->join('tokens', 'tokens.id', '=', 'buildings_tokens.token_id')
            ->where('tokens.user_id', 0)
            ->where('tokens.is_active', 1)
            ->where('buildings_tokens.building_id', $building_id)
            ->get();


        return $all_tokens;
    }

    public function updateFobAssignment(Request $request)
    {
        $building_id = $request->building_id;
        $previous_token = $request->prev_token_id;
        $user_id = $request->user_id;
        $token_id = $request->token_id;
        $unit_id = $request->unit_id;

        $building = Building::select('id', 'fob_model_id')->first();

        if ($request->has('token_id') && !empty($token_id)) {

            $token = Token::where('id', $token_id)->first();

            if ($token) {
                $token->update([
                    'user_id' => $user_id,
                    'modified' => date('Y-m-d H:i:s')
                ]);

                addTokenHistory($token, $building_id, TokenHistory::TYPE_ASSIGNED);

                addUnitLogs($unit_id, $building_id, 18, "Fob Assigned", ['card_id' => $token['card_id']], Auth::id(), $unit_id);

                return response()->json(['status' => 'success', 'message' => 'Fob assigned successfully.']);
            }
            return response()->json(['status' => 'warning', 'message' => 'Unable to find FOB']);

        } else if ($request->has('prev_token_id') && !empty($previous_token)) {
            $token = Token::where('id', $previous_token)->first();

            if ($token) {
                $token->update([
                    'user_id' => -1,
                    'user_history' => $user_id,
                    'model_id' => $building ? $building['fob_model_id'] : '',
                ]);

                addTokenHistory($token, $building_id, TokenHistory::TYPE_DISCARDED);
                addUnitLogs($unit_id, $building_id, 18, "Fob Unassigned", ['card_id' => $token['card_id']], Auth::id(), $unit_id);

                return response()->json(['status' => 'success', 'message' => 'Fob unassigned successfully.']);
            }

            return response()->json(['status' => 'warning', 'message' => 'Unable to find FOB']);
        }

        return response()->json(['status' => 'warning', 'message' => 'Something went wrong. Please try again later']);

    }

    public function searchUsers(Request $request)
    {
        $building_id = $request->building_id;
        $unit_id = $request->unit_id;
        $text = '';
        $building = Building::select('id', 'user_id')->find($building_id);
        $unit = Unit::find($unit_id);

        $user_ids = Building::select('user_id')->where('id', $building_id)->where('status', 1)->pluck('user_id')->toArray();

        $buildings_ids = Building::select('id')->whereIn('user_id', $user_ids)->get();

        $all_landlord_buildings = array();
        foreach ($buildings_ids as $build) {
            array_push($all_landlord_buildings, $build['id']);
        }

        $allunits_resident = Unit::select('id')->whereIn('building_id', $all_landlord_buildings)->get();
        $allunit = array();
        foreach ($allunits_resident as $units) {
            if ($units['id'] != $unit_id) {
                array_push($allunit, $units['id']);
            }
            if ((strtolower($request->type) == 'guarantor' || strtolower($request->type) == 'co-guarantor') && !in_array($unit_id, $allunit)) {
                array_push($allunit, $unit_id);
            }
        }


        $users = User::
        select('users.*', 'usertypes.name as usertype_name', 'units_users.unit_id as units_users_unit_id', 'units.unit_no', 'buildings.name as building_name')
            ->leftJoin('units_users', 'users.id', '=', 'units_users.user_id')
            ->leftJoin('units', 'units.id', '=', 'units_users.unit_id')
            ->leftJoin('buildings', 'buildings.id', '=', 'units.building_id')
            ->join('usertypes', 'usertypes.id', 'users.usertype_id')
            ->where('users.usertype_id', 13)
            ->where('users.owner_id', $building['user_id'])
            ->when(!empty($request->firstname), function ($q) use ($request) {
                $q->where('users.firstname', 'LIKE', '%' . $request->firstname . '%');
            })
            ->when(!empty($request->lastname), function ($q) use ($request) {
                $q->where('users.lastname', 'LIKE', '%' . $request->lastname . '%');
            })
            ->when(!empty($request->mobile), function ($q) use ($request) {
                $q->where('users.mobile', 'LIKE', '%' . $request->mobile . '%');
            })
            ->when(!empty($request->email), function ($q) use ($request) {
                $q->where('users.email', 'LIKE', '%' . $request->email . '%');
            })
            ->limit(10)->get();
        foreach ($users as $user) {

        }
        return $users;
    }

    public function checkResidentMobileExists(Request $request)
    {
        $mobile = $request->mobile;
        $bypass_user = $request->bypass_id;

        $userData = User::
        where('mobile', $mobile)
            ->when($bypass_user, function ($q) use ($bypass_user) {
                $q->where('id', '!=', $bypass_user);
            })
            ->first();

        if ($userData) {
            if ($userData['usertype_id'] != 13) {
                return response()->json(['status' => 'warning', 'message' => 'This number belongs to staff. Please contact Admin.']);
            } else {
                $data = User::
                select('units.unit_no', 'buildings.name')
                    ->join('units_users', 'users.id', '=', 'units_users.user_id')
                    ->join('units', 'units_users.unit_id', '=', 'units.id')
                    ->join('buildings', 'buildings.id', '=', 'units.building_id')
                    ->where('users.id', $userData['id'])
                    ->first();

                if (!$data) {
                    return response()->json(['status' => 'warning', 'message' => 'Number already exists.']);
                } else {
                    return response()->json(['status' => 'warning', 'message' => 'This mobile belongs to a user in ' . $data['name'] . '/' . $data['unit_no']]);
                }
            }
        }

        return response()->json(['status' => 'success', 'message' => '']);
    }

    public function checkResidentEmailExists(Request $request)
    {
        $email = $request->email;
        $bypass_user = $request->bypass_id;

        $userData = User::
        where('email', $email)
            ->when($bypass_user, function ($q) use ($bypass_user) {
                $q->where('id', '!=', $bypass_user);
            })
            ->first();

        if ($userData) {
            if ($userData['usertype_id'] != 13) {
                return response()->json(['status' => 'warning', 'message' => 'This email belongs to staff. Please contact Admin.']);
            } else {
                $data = User::
                select('units.unit_no', 'buildings.name')
                    ->join('units_users', 'users.id', '=', 'units_users.user_id')
                    ->join('units', 'units_users.unit_id', '=', 'units.id')
                    ->join('buildings', 'buildings.id', '=', 'units.building_id')
                    ->where('users.id', $userData['id'])
                    ->first();

                if (!$data) {
                    return response()->json(['status' => 'warning', 'message' => 'Email already exists.']);
                } else {
                    return response()->json(['status' => 'warning', 'message' => 'This email belongs to a user in ' . $data['name'] . '/' . $data['unit_no']]);
                }
            }
        }

        return response()->json(['status' => 'success', 'message' => '']);
    }

    public function addResident(Request $request)
    {

        $request->validate([
            'firstname' => 'required',
            'lastname' => 'required',
            'email' => 'required',
            'mobile' => 'required',
            'prefix' => 'required',
            'building_id' => 'required',
            'unit_id' => 'required',
        ]);
        // dd('call'.$request->intercom);
        $building_id = $request->building_id;
        $unit_id = $request->unit_id;

        $building_data = Building::
        select('buildings.*', 'units.*')
            ->leftJoin('units', 'units.building_id', '=', 'buildings.id')
            ->where('buildings.id', $building_id)
            ->where('units.id', $unit_id)
            ->first();

        $unit = Unit::find($unit_id);

        $user_data = [];
        $user_data['firstname'] = $request->firstname;
        $user_data['lastname'] = $request->lastname;
        $user_data['email'] = $request->email;
        $user_data['mobile'] = $request->mobile;
        $user_data['work_phone'] = $request->work_phone;
        $user_data['home_phone'] = $request->home_phone;
        $user_data['other_phone'] = $request->other_phone;
        // $user_data['is_mobile_verified'] = $request->is_mobile_verified ?? 0;
        $user_data['mobile_verification'] = $request->is_mobile_verified ? "Yes" : "No";
        $user_data['usertype_id'] = 13;
        $user_data['prefix'] = $request->prefix;

        $unitaddress = '';
        $unitaddress .= ($building_data['street_no'] != "" && $building_data['street_no'] != NULL) ? $building_data['street_no'] . ", " : '';
        $unitaddress .= ($building_data['prefix'] != "" && $building_data['prefix'] != NULL) ? $building_data['prefix'] . " " : '';
        $unitaddress .= ($building_data['street_name'] != "" && $building_data['street_name'] != NULL) ? $building_data['street_name'] . " " : '';
        $unitaddress .= ($building_data['suffix'] != "" && $building_data['suffix'] != NULL) ? $building_data['suffix'] : '';

        $user_data['city'] = $building_data['city'];
        $user_data['state'] = $building_data['state'];
        $user_data['zipcode'] = $building_data['zipcode'];
        $user_data['address1'] = $unitaddress;
        $user_data['owner_id'] = $building_data['user_id'];
        $user_data['status'] = '1';


        $user_data['parent_unit_id'] = $unit_id;
        // $user_data['parent_unit_id'] = $request->fromParking ? null : $unit_id;

        $unitsUsersCount = UnitUser::where('unit_id', $unit_id)->count();

        if ($unitsUsersCount == 0) {
            Unit::where('id', $unit_id)->update(['service_start_date' => date('Y-m-d')]);
        }

        $user_exists = User::
        when($request->user_id, function ($q) use ($request) {
            $q->where('id', $request->user_id);
        })
            ->when(!$request->user_id, function ($q) use ($user_data) {
                $q->where(function ($q) use ($user_data) {
                    $q->where('email', $user_data['email'])->orWhere('mobile', $user_data['mobile']);
                });
            })
            ->first();


        if ($user_exists) {
            $user_data['status_delete'] = 0;
            $user_id = $user_exists['id'];
            $onboardingCheck = $this->onBoardingConditionCheck($unit, $user_id);
            $user_exists->update($user_data);
            UnitUser::updateOrCreate([
                'user_id' => $user_id,
                'unit_id' => $unit_id
            ], [
                'active' => 1
            ]);
            UnitHistory::addUserUnitistory($unit_id, $user_id);

            if ($unitsUsersCount == 0) {
                $this->setPreOnBoardingFlag(1, $unit_id, $onboardingCheck);
                Unit::where('id', $unit_id)->update(['primary_user_id' => $user_id, 'guarantor_user_id' => $user_id]);
            }

            if (!empty($request->intercom)) {
                $position = $request->intercom;
                Unit::where('id', $unit_id)->update(['call' . $position => str_replace("-", "", $user_exists['mobile'])]);
            }

        } else {
            $user = User::create($user_data);

            if ($user) {
                $user_id = $user['id'];
                $onboardingCheck = $this->onBoardingConditionCheck($unit, $user_id);
                UnitUser::updateOrCreate([
                    'user_id' => $user_id,
                    'unit_id' => $unit_id
                ], [
                    'active' => 1
                ]);
                UnitHistory::addUserUnitistory($unit_id, $user_id);
                if ($unitsUsersCount == 0) Unit::where('id', $unit_id)->update(['primary_user_id' => $user_id]);

                if (!empty($request->intercom)) {
                    $position = $request->intercom;
                    Unit::where('id', $unit_id)->update(['call' . $position => str_replace("-", "", $user['mobile'])]);
                }

                if ($request->showCoGuarantor) {
                    $data = ['co_guarantor_user_id' => $user_id];
                    if ($unit['guarantor_user_id'] == $user_id) $data['guarantor_user_id'] = null;
                    Unit::where('id', $unit_id)->update($data);
                    $this->setPreOnBoardingFlag(1, $unit_id, $onboardingCheck);
                }

                if ($building_data['enable_create_e_wallet']) {
                    createWalletTask($user_id, $unit_id, $building_id);
                }

            }
        }


    }

    public function onBoardingConditionCheck($unit, $user_id)
    {
        $onboardingCheck = ($user_id != $unit['guarantor_user_id'] && $user_id != $unit['co_guarantor_user_id']);
        return $onboardingCheck;
    }

    public function setPreOnBoardingFlag($value, $unit_id, $onboardingCheck = 1)
    {
        if ($onboardingCheck) {
            Unit::where('id', $unit_id)->update(['start_onboarding_process' => $value]);
        }
    }

    public function resendVerificationLink($userId)
    {
        $user = User::find($userId);
        if ($user) {
            $verify_url = getConfigExceptHash('app.portal_url') . '/users' . '/reset_password/' . md5($user->email);

            $str = $user->firstname . ", for uninterrupted Fob usage,\n";

            if ($user->mobile_verification == 'Yes') {
                $str .= "SMS: Verified\n\n";
            } else {
                $str .= "SMS: Verify mobile NOW. REPLY with two words \"VERIFY\" & your \"UNIT#\" \n\n";
            }

            if ($user->email_verified) {
                $str .= "Email: Verified\n";
            } else {
                $str .= "Email: Not Verified. Visit " . $verify_url . "\n";
            }

            sendSMS($str, $user->mobile);

            return response()->json(
                [
                    "status" => "success",
                    "message" => "Verification links sent successfully."
                ]
            );
        }
        return response()->json(
            [
                "status" => "error",
                "message" => "User not found."
            ]
        );
    }

    public function manuallyVerifyUser($userId)
    {
    }
}
