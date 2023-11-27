<?php

namespace App\Http\Controllers;

use App\Models\Addon;
use App\Models\AddonsUnits;
use App\Models\Building;
use App\Models\BuildingsUsers;
use App\Models\MailServiceProvider;
use App\Models\Token;
use App\Models\TransactionLog;
use App\Models\Unit;
use App\Models\UnitHistory;
use App\Models\UnitMac;
use App\Models\UnitTypes;
use App\Models\UnitUser;
use App\Models\User;
use App\Models\Vcom;
use App\Models\Wallets;
use App\Services\Parsers\DeviceParser;
use App\Services\Parsers\UnitsParser;
use App\Services\Parsers\LeaseHistoryParser;
use App\Traits\UnitTrait;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;

class UnitsController extends Controller
{
    use UnitTrait;

    public function index(Request $request)
    {
        if (!checkIfBuildingBelongsToOwner($request->buildingId)) {
            return response()->json(
                [
                    "status" => false,
                    "message" => "Unauthorized action.",
                ]
            );
        }

        $data = app(UnitsParser::class)->parse($request);

        return response()->json(
            [
                "status" => true,
                "data" => $data,
            ]
        );
    }

    public function getNextAndPreviousUnit(Request $request)
    {
        $unit = Unit::find($request->id);
        $occupied = count($unit->users) > 0 ? 1 : 0;
        $requestArray = new Request();
        $requestArray = $requestArray->replace([
            "buildingId" => $unit->building_id,
            "occupied" => $occupied,
        ]);
        $data = app(UnitsParser::class)->parse($requestArray);
        $units = $data['data'] ?? [];
        $previous = null;
        $next = null;

        $unitIndex = $units->search(function ($element) use ($unit) {
            return $element->id == $unit->id;
        });

        $previous = $unitIndex - 1;
        $next = $unitIndex + 1;

        $previous = isset($units[$previous]) ? $units[$previous]->id : null;
        $next = isset($units[$next]) ? $units[$next]->id : null;

        return response()->json(
            [
                "status" => true,
                "next" => $next,
                "previous" => $previous,
            ]
        );
    }

    public function loadUnverifiedUsers(Request $request)
    {
        if (!checkIfBuildingBelongsToOwner($request->buildingId)) {
            return response()->json(
                [
                    "status" => false,
                    "message" => "Unauthorized action.",
                ]
            );
        }

        $users = Unit::where('building_id', $request->buildingId)
            ->join('units_users', 'units_users.unit_id', 'units.id')
            ->join('users', 'users.id', 'units_users.user_id');

        $totalUsers = clone $users;
        $totalUsers = $totalUsers->count();

        $unverifiedUsers = clone $users;
        $unverifiedUsers = $unverifiedUsers->where(function ($query) {
            $query->where('users.email_verified', 0);
            $query->orWhere('users.mobile_verification', 'No');
        })->get();

        return response()->json(
            [
                "status" => true,
                "totalUsers" => $totalUsers,
                "unverifiedUsers" => count($unverifiedUsers),
                "unverifiedUsersList" => $unverifiedUsers,
            ]
        );
    }

    public function updateUnitColumn(Request $request)
    {
        $unitId = $request->id;
        $unit = Unit::find($unitId);
        if (!checkIfBuildingBelongsToOwner($unit->building_id)) {
            return response()->json(
                [
                    "status" => false,
                    "message" => "Unauthorized action.",
                ]
            );
        }
        $unit->{$request->column} = $request->value;
        $unit->save();

        return response()->json(
            [
                "status" => true,
            ]
        );
    }

    public function loadBuildingsByUnit(Request $request)
    {
        $unitId = $request->unitId;
        $unit = Unit::find($unitId);
        if (!checkIfBuildingBelongsToOwner($unit->building_id)) {
            return response()->json(
                [
                    "status" => false,
                    "message" => "Unauthorized action.",
                ]
            );
        }

        $building = $unit->building;
        $buildings = Building::where('user_id', $building->user_id)->where('name', '!=', '')->get();

        return response()->json(
            [
                "status" => true,
                "buildings" => $buildings,
            ]
        );
    }

    public function loadUnitDetails($unit_id)
    {
        if (!$unit_id) {
            return response()->json(
                [
                    'status' => false,
                    'message' => 'No unit selected. Please select unit first and try again.',
                ]
            );
        }
        $unit = Unit::with('users.units.building', 'users.tokens', 'building', 'unitType', 'guarantor', 'macs')->where('id', $unit_id)->first();
        if (!$unit)
            return response()->json(
                [
                    'status' => false,
                    'message' => 'Unit not found.',
                ]
            );
        if (!checkIfBuildingBelongsToOwner($unit->building_id)) {
            return response()->json(
                [
                    'status' => false,
                    'message' => 'Unauthorized action.',
                ]
            );
        }
        foreach ($unit->users as $user) {
            if ($user->id != $unit->guarantor_user_id || $user->id != $unit->co_guarantor_user_id) {
                $nonGuarantorUser = true;
            }
        }
        return response()->json(
            [
                'status' => true,
                'unit' => $unit,
                'nonGuarantorUser' => $nonGuarantorUser ?? false,
            ]
        );
    }

    public function vacateUnit(Request $request)
    {
        DB::beginTransaction();
        try {
            $unitId = $request->unitId;
            $unit = Unit::find($unitId);
            $unitUsers = $unit->users;

            if ($unitUsers) {
                $userids = array();
                foreach ($unitUsers as $user) {
                    array_push($userids, $user->id);

                    $user->modified = Carbon::now()->format('Y-m-d H:i:s');
                    $units_count = UnitUser::where('user_id', $user->id)->count();
                    if ($units_count <= 1) {
                        $user->status_delete = 1;
                        $user->status = 0;
                    }

                    if ($user->parent_unit_id == $unitId) {
                        $user->parent_unit_id = NULL;
                    }
                    $user->save();
                }
                $this->clearAmenityBalanceOnMoveOut($unit);
                $this->moveOutAllUnitUser($unit);

                $this->clearUnitData($unitId, $unit->building_id, $userids, 0);
            }
        } catch (\Exception $exception) {
            DB::rollBack();
            return response()->json(
                [
                    'status' => false,
                    'message' => $exception->getMessage()
                ]
            );
        }
        DB::commit();

        return response()->json(
            [
                'status' => true,
            ]
        );
    }

    public function disableServices(Request $request)
    {
        $unitId = $request->unitId;
        AddonsUnits::where('unit_id', $unitId)->update([
            "status" => 0
        ]);

        return response()->json(
            [
                'status' => true,
            ]
        );
    }

    public function relocateResident(Request $request)
    {
        $currentUnitId = $request->currentUnitId;
        $unitId = $request->unitId;
        $residents = $request->users;

        $newUnit = Unit::with('users', 'unitType')->where('id', $unitId)->first();
        $currentUnit = Unit::with('users', 'unitType')->where('id', $currentUnitId)->first();

        $unitUsers = $newUnit->users;
        $totalAllowedUsers = $newUnit->unitType->num_users;

        $totalUsers = count($unitUsers);
        $maxUsers = $totalAllowedUsers - $totalUsers;
        $userIds = [];
        if (count($residents) > $maxUsers) {
            return response()->json(
                [
                    'status' => false,
                    'message' => "Users exceed maximum number of allowed users."
                ]
            );
        } else
            if ($residents) {
                try {
                    foreach ($residents as $user_id) {
                        UnitUser::where([
                            "unit_id" => $currentUnitId,
                            'user_id' => $user_id
                        ])->delete();

                        $currentBuildingId = $currentUnit->building_id;
                        if ($currentUnit->guarantor_user_id == $user_id) {
                            $currentUnit->guarantor_user_id = null;
                            $currentUnit->save();
                            $notes = ["function" => "move_unit_users"];
                            moveCartsToNewUnit($currentUnit, $newUnit);
                            addUnitsLog($user_id, 14, 'GUARANTOR ROLE REVOKED', $notes, $currentBuildingId, $currentUnitId, 0, Auth::id(), Auth::id());
                        }
                        if ($currentUnit->co_guarantor_user_id == $user_id) {
                            $currentUnit->co_guarantor_user_id = null;
                            $currentUnit->save();
                            $notes = ["function" => "move_unit_users"];
                            addUnitsLog($user_id, 14, 'Co-GUARANTOR ROLE REVOKED', $notes, $currentBuildingId, $currentUnitId, 0, Auth::id(), Auth::id());
                        }

                        $user = User::find($user_id);
                        $user->parent_unit_id = $newUnit->id;
                        $user->save();

                        UnitUser::create([
                            "unit_id" => $newUnit->id,
                            'user_id' => $user_id,
                            'active' => 1
                        ]);
                        $notes = ["function" => "move_unit_users"];
                        addUnitsLog($user_id, 14, 'MOVED TO OTHER UNIT', $notes, $newUnit->building_id, $newUnit->unit_id, 0, Auth::id(), Auth::id());
                        addUnitsLog($user_id, 14, 'ADDED', $notes, $newUnit->building_id, $newUnit->unit_id, 0, Auth::id(), Auth::id());
                        if ($totalUsers == 0) {
                            $newUnit->guarantor_user_id = $user_id;
                            addUnitsLog($user_id, 14, 'GUARANTOR ROLE ASSIGNED', $notes, $newUnit->building_id, $newUnit->unit_id, 0, Auth::id(), Auth::id());
                        }
                        $totalUsers += 1;
                        $userIds[] = $user_id;
                    }

                    $data = $currentUnit->users;

                    $this->clearUnitData($currentUnitId, $currentUnit->building_id, $userIds, count($data));
                } catch (\Exception $exception) {
                    return response()->json(
                        [
                            'status' => false,
                            'message' => $exception->getMessage()
                        ]
                    );
                }
            }
        return response()->json(
            [
                'status' => true,
            ]
        );
    }

    public function leaseHistory(Request $request)
    {
        $data = app(LeaseHistoryParser::class)->parse($request);

        return response()->json($data);
    }

    public function startonboardingprocess(Request $request)
    {
        $unitId = $request->unitId;
        $url = rtrim(config('app.ADMIN_URL'), '/') . '/startOnBoardingProcess/' . $unitId;
        $response = doURL($url);

        return response()->json($response);
    }

    public function getDocumentPreview($docId)
    {
        $url = rtrim(config('app.ADMIN_URL'), '/') . '/previewDocument/' . $docId;

        return response()->json(
            [
                "status" => true,
                'url' => $url
            ]
        );
    }

    public function sendDocumentEmails(Request $request)
    {
        $signDocs = $request->signDocs ?? null;
        $term = $request->term ?? null;

        if (!$signDocs || count($signDocs) == 0) {
            return response()->json(
                [
                    "status" => false,
                    'message' => "Document missing."
                ]
            );
        }

        if (!$term) {
            return response()->json(
                [
                    "status" => false,
                    'message' => "Term cannot be empty."
                ]
            );
        }
        $url = rtrim(config('app.ADMIN_URL'), '/') . '/sendDocumentEmails';
        $post = ['signDocs' => $signDocs, 'term' => $term];

        $response = doURL($url, $post);

        return response()->json(
            [
                "status" => true,
                "response" => $response,
            ]
        );
    }

    public function cancelOnboardingDocument(Request $request)
    {
        $signDocs = $request->signDocs ?? null;

        if (!$signDocs || count($signDocs) == 0) {
            return response()->json(
                [
                    "status" => false,
                    'message' => "Document missing."
                ]
            );
        }
        $url = rtrim(config('app.ADMIN_URL'), '/') . '/cancelDocuments';
        $post = ['signDocs' => $signDocs];

        $response = doURL($url, $post);

        return response()->json(
            [
                "status" => true,
                "response" => $response,
            ]
        );
    }

    public function updateDoorIntercom(Request $request)
    {
        $unit = Unit::find($request->unit_id);
        if (isset($request->call1))
            $unit->call1 = $request->call1;
        if (isset($request->call2))
            $unit->call2 = $request->call2;
        if (isset($request->call3))
            $unit->call3 = $request->call3;
        if (isset($request->group1))
            $unit->group1 = $request->group1;
        if (isset($request->group2))
            $unit->group2 = $request->group2;

        $unit->save();
        return response()->json(
            [
                "status" => "success",
                'message' => "Door intercom settings updated successfully."
            ]
        );
    }

    public function moveoutuser(Request $request)
    {

        $unit = Unit::select('call1', 'call2', 'call3', 'guarantor_user_id', 'co_guarantor_user_id', 'did_number')->where('id', $request->unit_id)->first();
        $user = User::select('mobile')->where('id', $request->user_id)->first();
        $vcom = Vcom::select('ip_address')->where('unit_id', $request->unit_id)->first();


        $col = null;
        $phone = isset($unit['call1']) ? $unit['call1'] : null;
        $vcom_ip = isset($vcom['ip_address']) ? $vcom['ip_address'] : null;

        $residents = UnitUser::select('users.id', 'users.mobile')
            ->join('users', 'users.id', '=', 'units_users.user_id')
            ->where('units_users.unit_id', $request->unit_id)->where('units_users.user_id', $request->user_id)
            ->get();


        if (!is_null($phone)) {
            $flag = 0;
            if (strpos($phone, 'p:')) {
                if (substr($phone, 4) == $vcom_ip) {
                    $flag = 1;
                }
            }

            foreach ($residents as $resident) {
                if ($phone == str_replace('-', '', $resident['mobile'])) {
                    $flag = 1;
                }
            }

            if ($phone != $unit['did_number'] && !$flag) {
                $col = 'call1';
            }

        }

        $UsrRecedData['modified'] = date("'Y-m-d H:i:s'");

        $units_count = UnitUser::where('user_id', $request->user_id)->count();
        if ($units_count <= 1) {
            $UsrRecedData['status'] = '0';
            $UsrRecedData['status_delete'] = '1';
        }

        $base_user = User::where('id', $request->user_id)->first();
        if (isset($base_user) && ($base_user['parent_unit_id'] == $request->unit_id)) {
            $UsrRecedData['parent_unit_id'] = NULL;
        }

        if ($base_user->save($UsrRecedData)) {
            if ($col != null) {
                Unit::where('id', $request->unit_id)->update([$col => null]);
            }
            if ($unit['call2'] == str_replace('-', '', $user['mobile'])) {
                Unit::where('id', $request->unit_id)->update(['call2' => null]);
            }

            if ($unit['call3'] == str_replace('-', '', $user['mobile'])) {
                Unit::where('id', $request->unit_id)->update(['call3' => null]);
            }

            UnitUser::where('user_id', $request->user_id)->delete();
            UnitMac::where('user_id', $request->user_id)->where('unit_id', $request->unit_id)->delete();

            if (isset($unit['guarantor_user_id']) && ($unit['guarantor_user_id'] == $request->user_id)) {
                Unit::where('id', $request->unit_id)->update(['guarantor_user_id' => null]);
            }
            if (isset($unit['co_guarantor_user_id']) && ($unit['co_guarantor_user_id'] == $request->user_id)) {
                Unit::where('id', $request->unit_id)->update(['co_guarantor_user_id' => null]);
            }

            Unit::where('id', $request->unit_id)->where('primary_user_id', $request->user_id)->update(['primary_user_id' => 0]);

            addUserTokenHistory($request->user_id, $request->unit_id, session('building_id'), Auth::id());
            $user_token = Token::where('user_id', $request->user_id)->update([
                'user_id' => -1,
                'user_history' => $request->user_id,
                'modified' => date('Y-m-d H:i:s')
            ]);

            BuildingsUsers::where('user_id', $request->user_id)->delete();
            Wallets::where('user_id', $request->user_id)->update(['deleted_at' => date('Y-m-d H:i:s')]);

            update_user_unit_history($request->unit_id, $request->user_id);
            return response()->json(['status' => 'success']);

        } else {
            return response()->json(['status' => 'error']);
        }
        return response()->json(['status' => 'unknown']);
    }

    public function un_link_unit($unit_id, $user_id)
    {

        $user = User::find($user_id);
        $main_unit_id = $user['parent_unit_id'];
        $unit_user = UnitUser::where('unit_id', $unit_id)->where('user_id', $user_id)->delete();

        $user_data_arr = array();
        if ($user['usertype_id'] != 13 && ($unit_id == $user['resident_unit_id'])) {
            $user_data_arr['resident_unit_id'] = NULL;
        }

        $user_data_arr['modified'] = date('Y-m-d H:i:s');
        User::where('id', $user_id)->update($user_data_arr);

        return response()->json(['status' => 'success']);
    }

    public function makeGuarantorOrCoguarantor($unit_id, $user_id, $type)
    {

        $unit = Unit::where('id', $unit_id)->first();

        $onboardingCheck = $this->onBoardingConditionCheck($unit, $user_id);

        $newUserId = 0;
        $oldUserId = 0;
        if ($type == 'guarantor') {
            $actionName = 'Guarantor Changed';
            $oldUserId = $unit['guarantor_user_id'];
            $action = 'ACTION_USER_ASSIGN_GUARANTOR_ROLE';
            $userfields = array("guarantor_user_id" => $user_id, "modified" => date("Y-m-d H:i:s"));
            $newUserId = $user_id;
            if ($unit['co_guarantor_user_id'] == $user_id)
                $userfields['co_guarantor_user_id'] = null;

        } else {
            $actionName = 'Co-Guarantor Changed';
            $action = 'ACTION_USER_ASSIGN_COGUARANTOR_ROLE';
            $userfields = array('co_guarantor_user_id' => $user_id, "modified" => date("Y-m-d H:i:s"));
            if ($unit['guarantor_user_id'] == $user_id)
                $userfields['guarantor_user_id'] = null;
        }

        $unit->update($userfields);
        $this->setPreOnBoardingFlag(1, $unit_id, $onboardingCheck);

        $items = [
            'user_id' => $newUserId,
            'old_user_id' => $oldUserId
        ];

        addUnitLogs($unit_id, $unit['building_id'], 14, $actionName, $items, $user_id);

        return response()->json(['status' => 'success']);

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

    public function switchGuarantorCoGuarantor($unit_id)
    {
        $unit = Unit::find($unit_id);

        $guarantorUserId = $unit['guarantor_user_id'];
        $coGuarantorUserId = $unit['co_guarantor_user_id'];

        $this->setPreOnBoardingFlag(1, $unit_id);


        if ($guarantorUserId) {
            $items = [
                'user_id' => $guarantorUserId,
                'old_user_id' => $coGuarantorUserId
            ];

            addUnitLogs($unit_id, $unit['building_id'], 14, 'Co-Guarantor Changed', $items, $guarantorUserId);
            $unit->update([
                'co_guarantor_user_id' => $guarantorUserId
            ]);
        }
        if ($coGuarantorUserId) {
            $items = [
                'user_id' => $coGuarantorUserId,
                'old_user_id' => $guarantorUserId
            ];

            addUnitLogs($unit_id, $unit['building_id'], 14, 'Guarantor Changed', $items, $coGuarantorUserId);
            $unit->update([
                'guarantor_user_id' => $coGuarantorUserId
            ]);
        }

        return response()->json(['status' => 'success']);
    }
}