<?php

namespace App\Traits;

use App\Models\BuildingsUsers;
use App\Models\Token;
use App\Models\TransactionLog;
use App\Models\Unit;
use App\Models\UnitHistory;
use App\Models\UnitMac;
use App\Models\UnitUser;
use App\Models\User;
use App\Models\Vcom;
use App\Models\Wallets;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;

trait UnitTrait
{
    public function clearAmenityBalanceOnMoveOut($unit)
    {
        $unitId = $unit->id;
        $amenityBalance = $unit->amenity_balance;
        $rentBalance = $unit->rent_balance;
        if ($amenityBalance < 0 || $rentBalance < 0) {
            if (isset($unit->guarantor_user_id)) {
                $userId = $unit->guarantor_user_id;
            } else if (isset($unit->co_guarantor_user_id)) {
                $userId = $unit->co_guarantor_user_id;
            } else {
                $userId = Auth::id();
            }
            $arrTransactionLogs = array(
                'timestamp' => date('Y-m-d H:i:s'),
                'amount' => $amenityBalance,
                'balance' => 0,
                'building_id' => $unit->building_id,
                'unit_id' => $unitId,
                'user_id' => $userId,
                'laundry_id' => null,
                'laundrymachine_id' => null,
                'comment' => 'Amenities Balance Cleared (System Transaction)',
                'is_non_payment' => 0,
                'trans_source_id' => 2,
                'income_resource_id' => 3,
                'transaction_type_id' => 17,
                'identified_amount_type' => 1,
                'init_person_id' => Auth::id(),
            );
            TransactionLog::create($arrTransactionLogs);
            $notes = [
                'type' => 'Amenities balance write off (System Transaction)',
                'amount' => $amenityBalance,
            ];
            addUnitsLog($unitId, 2, 'Vacate Unit', $notes, $unit->building_id, $unit->id, 0, Auth::id(), Auth::id());

            $unit->amenity_balance = 0;

            $arrTransactionLogs = array(
                'timestamp' => date('Y-m-d H:i:s'),
                'amount' => $rentBalance,
                'balance' => 0,
                'building_id' => $unit->building_id,
                'unit_id' => $unitId,
                'user_id' => $userId,
                'laundry_id' => null,
                'laundrymachine_id' => null,
                'comment' => 'Rent Balance Cleared (System Transaction)',
                'is_non_payment' => 0,
                'trans_source_id' => 2,
                'income_resource_id' => 3,
                'transaction_type_id' => 17,
                'identified_amount_type' => 1,
                'init_person_id' => Auth::id(),
            );
            TransactionLog::create($arrTransactionLogs);
            $notes = [
                'type' => 'Rent balance write off (System Transaction)',
                'amount' => $rentBalance,
            ];
            $unit->rent_balance = 0;
            $unit->save();
        }
    }

    public function clearUnitData($unit_id, $building_id, $userIds, $move = 1)
    {
        $unit = Unit::find($unit_id);

        $author_id = Auth::id();
        foreach ($userIds as $k => $userid) {
            addUserTokenHistory($userid, $unit_id, $building_id, $author_id);

            Token::where('user_id', $userid)->update([
                'user_id' => '-1',
                'user_history' => $userid,
            ]);

            $user = User::find($userid);
            Vcom::where('unit_id', $unit_id)->first();
            $col = null;
            $phone = $unit->call1 ?? null;
            $vcom_ip = $vcom->ip_address ?? null;

            if (!is_null($phone)) {
                $flag = 0;
                if (strpos($phone, 'p:')) {
                    if (substr($phone, 4) == $vcom_ip) {
                        $flag = 1;
                    }
                }
                if ($phone != $unit->did_number && !$flag) {
                    $col = 'call1';
                }

            }


            if ($col != null) {
                $unit->{$col} = null;
            }

            if (strpos($unit->call1, 'SIP') !== false || strpos($unit->call1, 'sip') !== false) {
            } elseif ($unit->did_number && $unit->did_number != "") {
                $unit->call1 = $unit->did_number;
            }
            $unit->call2 = null;
            $unit->call3 = null;

            addUnitsLog($userid, 14, 'MOVED OUT', [], Session::get('building_id'), $unit_id, 0, Auth::id(), Auth::id());

            BuildingsUsers::where('user_id', $userid)->delete();
            UnitMac::where('unit_id', $unit_id)->where('user_id', $userid)->delete();
            Wallets::where('user_id', $userid)->update(['deleted_at' => Carbon::now()]);

            UnitHistory::where([
                "unit_id" => $unit_id,
                "user_id" => $userid,
                "status" => 1,
            ])->update([
                "move_out_time" => Carbon::now()->format('Y-m-d H:i:s'),
                "status" => 1
            ]);
        }

        if (!$move) {
            UnitMac::where('unit_id', $unit_id)->delete();
            $unit->internet_usage = null;
            $unit->internet_usage_devices = null;
            $unit->internet_daily = null;
            $unit->internet_daily_devices = null;
            $unit->save();
        }
    }

    public function moveOutAllUnitUser($unit)
    {
        UnitUser::where('unit_id', $unit->id)->delete();
        $unit->update([
            'primary_user_id' => 0,
            'guarantor_user_id' => NULL,
            'co_guarantor_user_id' => NULL
        ]);
    }
}