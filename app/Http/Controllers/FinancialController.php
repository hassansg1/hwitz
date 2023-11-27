<?php

namespace App\Http\Controllers;

use App\Models\Building;
use App\Models\Category;
use App\Models\LinkAbles;
use App\Models\TransactionLog;
use App\Models\User;
use App\Models\Wallets;
use App\Services\Parsers\AchParser;
use App\Services\Parsers\FinanceLastTransactionsParser;
use App\Services\Parsers\FinanceReceivablesParser;
use App\Services\Parsers\FinancialSummaryParser;
use App\Services\Parsers\OwnerOrderSummaryParser;
use App\Services\Parsers\PayablesParser;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class FinancialController extends Controller
{
    public function getMyAllWallets($userId, $portfolio = 'p', $origin = 's')
    {
        $user = User::find($userId);

        $transSource = ['4' => 'Laundry', '2' => 'Amenities', '3' => 'Rent'];

        $usersAllPayableCarts = Wallets::select('id', 'card_number', 'bnk_acc_no', 'card_expiry', 'trans_source_id', 'card_type', 'nick_name')
            ->whereUserId($userId)
            ->whereWalletType(0)
            ->orderBy('id', 'desc')
            ->get();

        $usersAllReceivableCarts = Wallets::select('id', 'bnk_acc_no', 'trans_source_id', 'nick_name')
            ->whereUserId($userId)
            ->whereWalletType(1)
            ->orderBy('id', 'desc')
            ->get();

        $cardName = ['' => 'Cheque', 4 => 'Visa', 5 => 'Master', 6 => 'Discover'];

        $paymentType = ['p' => 'Payable', 'r' => 'Receivable'];
        $walletType = ['p' => 0, 'r' => 1];

        $successMsg = $errorMsg = false;
        if ($origin == 'n')
            $successMsg = "A new account is added successfully.";
        elseif ($origin == 'u')
            $successMsg = "The account is updated successfully.";
        elseif ($origin == 'd')
            $successMsg = "The account is deleted successfully.";
        elseif ($origin == 'e')
            $errorMsg = "The account is not deleted, please try again.";

        $usersAllRentWallets = Wallets::select('id', 'bnk_acc_no', 'card_number')
            ->whereUserId($userId)
            ->whereTransSourceId(3)
            ->whereWalletType($walletType[$portfolio])
            ->get()
            ->toArray();

        $usersAllLaundryWallets = Wallets::select('id', 'bnk_acc_no', 'card_number')
            ->whereUserId($userId)
            ->whereTransSourceId(4)
            ->whereWalletType($walletType[$portfolio])
            ->get()
            ->toArray();

        $usersAllAmenitiesWallets = Wallets::select('id', 'bnk_acc_no', 'card_number')
            ->whereUserId($userId)
            ->whereTransSourceId(2)
            ->whereWalletType($walletType[$portfolio])
            ->get()
            ->toArray();

        $usersAllBuildings = Building::select('id', 'name')
            ->with('wallets')
            ->whereStatus(1)
            //->whereUserId($userId)
            ->get();


        $usersAllBuildings = getAllBuildingsOfAUser($userId);
        foreach ($usersAllBuildings as $key => $building) {
            $transSourceId = array_column($building->wallets->toArray(), 'id');
            $usersAllBuildings[$key]['transSourceId'] = $transSourceId;
            // $usersAllBuildings[$key]['selected'] = [];

            $rentData = [];
            $amenitiesData = [];
            $laundryData = [];
            foreach ($usersAllRentWallets as $id => $val) {
                if (in_array($val['id'], $transSourceId)) {
                    $rentData['id'] = $val['id'];
                    $rentData['name'] = '';
                    if ($val['bnk_acc_no']) $rentData['name'] = $val['bnk_acc_no'];
                    elseif ($val['card_number']) $rentData['name'] = $val['card_number'];
                }
            }
            foreach ($usersAllAmenitiesWallets as $id => $val) {
                if (in_array($val['id'], $transSourceId)) {
                    $amenitiesData['id'] = $val['id'];
                    $amenitiesData['name'] = '';
                    if ($val['bnk_acc_no']) $amenitiesData['name'] = $val['bnk_acc_no'];
                    elseif ($val['card_number']) $amenitiesData['name'] = $val['card_number'];
                }
            }
            foreach ($usersAllLaundryWallets as $id => $val) {
                if (in_array($val['id'], $transSourceId)) {
                    $laundryData['id'] = $val['id'];
                    $laundryData['name'] = '';
                    if ($val['bnk_acc_no']) $laundryData['name'] = $val['bnk_acc_no'];
                    elseif ($val['card_number']) $laundryData['name'] = $val['card_number'];
                }
            }
            $usersAllBuildings[$key]['selected_rent_cart'] = $rentData;
            $usersAllBuildings[$key]['selected_amenities_cart'] = $amenitiesData;
            $usersAllBuildings[$key]['selected_laundry_cart'] = $laundryData;
        }

        return response()->json(
            [
                'user' => $user, 'portfolio' => $portfolio, 'origin' => $origin, 'usersAllReceivableCarts' => $usersAllReceivableCarts,
                'usersAllPayableCarts' => $usersAllPayableCarts, 'transSource' => $transSource, 'cardName' => $cardName,
                'usersAllRentWallets' => $usersAllRentWallets, 'usersAllAmenitiesWallets' => $usersAllAmenitiesWallets,
                'usersAllLaundryWallets' => $usersAllLaundryWallets, 'usersAllBuildings' => $usersAllBuildings, 'successMsg' => $successMsg,
                'paymentType' => $paymentType, 'errorMsg' => $errorMsg
            ]
        );
    }

    public function buildingAccountAssignment(Request $request, $buildingId, $walletId, $userId, $transSourceId, $portfolio, $defaultAreaStatus)
    {
        $building = Building::find($buildingId);
        $walletType = ['p' => 4, 'r' => 5];

        $findOldWallet = $building->wallets()
            ->wherePivot('trans_source_id', '=', $transSourceId)
            ->wherePivot('default_area_status', '=', $walletType[$portfolio])
            ->detach();

        if ($findOldWallet > 0) {
            $walletHistoryLog = [
                'wallet_id' => $walletId,
                'change_flag' => 'Unlink',
                'wallet_logs' => 'Unlinked',
                'created_by' => $userId
            ];
            app(WalletController::class)->addWalletHistoryLogs($request, $walletHistoryLog);
        }

        $building->wallets()->attach([1 => [
            'wallet_id' => $walletId,
            'trans_source_id' => $transSourceId,
            'default_area_status' => $walletType[$portfolio],
            'is_default' => 1,
            'user_id' => $userId
        ]]);

        $log = ['wallet_id' => $walletId,
            'change_flag' => "link",
            'wallet_logs' => 'linked',
            'created_by' => $userId];
        app(WalletController::class)->addWalletHistoryLogs($request, $log);

        if (isset($building->name) && $walletId != 0)
            return "This account is assigned to the " . $building->name . ".";
        else
            return "This account is <b>unassigned</b> from the " . $building->name . ".";
    }

    public function loadPayables(Request $request)
    {
        $data = app(PayablesParser::class)->parse($request);

        return response()->json($data);
    }

    public function loadAchStatusData(Request $request)
    {
        $data = app(AchParser::class)->parse($request);

        return response()->json($data);
    }

    public function sendOneTimeInvoice(Request $request)
    {
        // dd($request->all());
        $buildingId = $request->building_id;
        $building = Building::find($request->building_id);

        $user = User::where('id', $request->getPayerData)->first();
        $unit = $user->unit;
        $unitId = $unit->id ?? null;
        $itrator = 0;

        $grandTotal = 0;
        foreach ($request->date_of_service as $date) {
            if (!$unitId)
                $unitId = getOwnerUnit($user->id, $buildingId);
            $amount = ($request->amount[$itrator] - floatval(preg_replace('/[^\d.]/', '', applyPercent($request->amount[$itrator], $request->discount[$itrator])))) * -1;
            $tLog = [
                "receiver_id" => $request->user_id,
                "user_id" => $user->id,
                "amount" => $amount,

                "actual_amount" => $request->amount[$itrator] * -1,
                "trans_source_id" => $request->cart,
                "term_discount" => $request->discount[$itrator],
                "comment" => $request->category[$itrator],
                "notes" => json_encode(['description' => $request->description[$itrator]]),
                "timestamp" => Carbon::parse($request->date_of_service[$itrator])->format('Y-m-d'),
                "building_id" => $buildingId,
                "unit_id" => $unitId ?? 0,
                "invoice_type" => "one time Invoice",
            ];

            $grandTotal = +$amount;
            $transectionLog[] = TransactionLog::create($tLog);


            $itrator += 1;
        }
        $transSourceId = $request->cart;
        if ($unit) {
            $cart = TransactionLog::$carts[$transSourceId];
            if ($cart) {
                $balance = $unit->{$cart};
                $unit->{$cart} = $balance + $amount;
                $unit->save();
            }
        }
        $linkable = addNewLinkable($user->id, $unitId, "one_time_invoice", 500, $transectionLog, null, null, TransactionLog::$transToCartOther[$request->cart]);
        $link = config('app.ATTENDANT_URL') . '/pay/' . $linkable->token;
        $link = yourls($link);
        $json_data['name'] = ucfirst($user->firstname) . ' ' . $user->lastname;
        $json_data['link'] = "<a href='" . $link . "' target='_blank'>click here</a>";
        $json_data['raw_link'] = $link;
        $title = "Invoice (building:  $building->address1), " . \Carbon\Carbon::parse("today")->format('M d, Y') . ", " . $linkable->id;


        createTask(7, 'One Time Invoice', $user->id, $unitId, $buildingId, 3, 3, 'User', $user->id, 'User', $user->id, $json_data, Auth::user()->id ?? 0);
        sendOneTimeInvoice($user, $building, $transectionLog, $link, $title, $transSourceId, User::find($request->user_id), $linkable, $path = null);

        $cartType = TransactionLog::$carts[$transSourceId];

        $notes = [
            'amount' => $grandTotal,
            'link' => $link,
            'transactionLog' => $transectionLog,
            'invoice_number' => $linkable->token,
        ];
        addUnitsLog(0, 9, "One time Invoice", $notes, $building->id, $unit->id ?? null, 1, $user->id, null);
        if ($user->usertype_id == 15)
            updateLandlordBalance(abs($grandTotal) * -1, $user->id, $buildingId, $transSourceId);

        if ($transectionLog) {
            foreach ($transectionLog as $transLogs) {
                $transLogs->payment_link_id = $linkable->id;
                $transLogs->save();
            }
        }
        return response()->json(["status" => "success", "message" => "Invoice is sent to the payer."]);
    }


    public function getUserTypesForReceivablesAndPayables()
    {
        $receivables = getUserTypesForReceviable();
        $payables = getUserTypesForPayable();
        $categories = Category::all();

        return response()->json(['payable_user_type' => $payables, 'receivable_user_types' => $receivables, 'categories' => $categories]);
    }

    public function getUsersOfABuildingByUserType($buildingId, $userTypeId)
    {
        $buildingUsers = DB::table('buildings_users')
            ->where('building_id', $buildingId)
            ->pluck('user_id')
            ->toArray();

        $users = User::where(function ($query) use ($buildingUsers, $userTypeId) {
            $query->where('usertype_id', $userTypeId)->whereIn('id', $buildingUsers);
        })
            ->where('usertype_id', $userTypeId)
            ->get();

        return $users;
    }

    public function getPayer($bid, $userTypeId)
    {
        $buildingUsers = DB::table('buildings_users')
//            ->where('building_id', $bid)
            ->pluck('user_id')
            ->toArray();


        $users = User::where(function ($query) use ($buildingUsers, $userTypeId) {
            $query->where('usertype_id', $userTypeId)->whereIn('id', $buildingUsers);
        })
            ->where('usertype_id', $userTypeId)
            ->get();
        return $users;
    }

    public function loadFinanceSummary(Request $request)
    {
        $data = app(FinancialSummaryParser::class)->parse($request);

        return response()->json($data);
    }

    public function loadOwnerOrderSummary(Request $request)
    {
        $data = app(OwnerOrderSummaryParser::class)->parse($request);

        return response()->json($data);
    }

    public function financeLastTransactions(Request $request)
    {
        $data = app(FinanceLastTransactionsParser::class)->parse($request);

        return response()->json($data);
    }

    public function ownerReceivables(Request $request)
    {
        $data = app(FinanceReceivablesParser::class)->parse($request);

        return response()->json($data);
    }
}
