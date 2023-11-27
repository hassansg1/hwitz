<?php

namespace App\Http\Controllers;

use App\Models\AssetUnit;
use App\Models\LoginAttempts;
use App\Models\SystemLog;
use App\Models\User;
use App\Services\Parsers\UserAnalyticsParser;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class UserAnalyticsController extends Controller
{
    public function index(Request $request)
    {
        $data = app(UserAnalyticsParser::class)->parse($request);

        return response()->json($data);
    }

    /**
     * @return \Illuminate\Http\JsonResponse
     * @throws \Throwable
     */
    public function userActivityModal(Request $request)
    {
        $user = User::with('usertype')->where('id', $request->id)->first();
        $items = SystemLog::with('triggeredBy.usertype')->where('entity_id', $request->id);
        $items = $items->whereIn('entity_name',
            [
                SystemLog::MODULE_USER_ADMIN,
                SystemLog::MODULE_USER_BROKER,
                SystemLog::MODULE_USER_CONTRACTOR,
                SystemLog::MODULE_USER_OWNER,
                SystemLog::MODULE_USER_SERVICE_PROVIDER,
                SystemLog::MODULE_USER_STAFF,
                SystemLog::MODULE_USER_SUPPLIER,
                SystemLog::MODULE_RESIDENT,
                SystemLog::MODULE_APPLIANCE
            ]
        )->orderBy('triggered_at', 'desc')->get();

        return response()->json([
            'user' => $user,
            'items' => $items,
        ]);
    }

    /**
     * @return \Illuminate\Http\JsonResponse
     * @throws \Throwable
     */
    public function loginActivityModal(Request $request)
    {
        $user = User::with('usertype')->where('id', $request->id)->first();
        $items = LoginAttempts::where('user_id', $request->id)->orderBy('attempt_at', 'desc')->get();

        return response()->json([
            'user' => $user,
            'items' => $items,
        ]);
    }

    /**
     * @return \Illuminate\Http\JsonResponse
     * @throws \Throwable
     */
    public function userEntranceModal(Request $request)
    {
        $user = User::with('usertype')->where('id', $request->id)->first();
        $items = AssetUnit::with('asset', 'unit.building')->where('unit_id', $user->unit->id ?? null)->orderBy('id', 'desc')->get();

        return response()->json([
            'user' => $user,
            'items' => $items,
        ]);
    }

    /**
     * @return \Illuminate\Http\JsonResponse
     * @throws \Throwable
     */
    public function userEmailModal(Request $request)
    {
        $user = User::with('usertype')->where('id', $request->id)->first();
        $items = DB::table('change_emails')->where('user_id', $request->id)->orderBy('id', 'desc')->get();

        return response()->json([
            'user' => $user,
            'items' => $items,
        ]);
    }

}