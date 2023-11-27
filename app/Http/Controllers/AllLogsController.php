<?php

namespace App\Http\Controllers;

use App\Models\AllLog;
use App\Models\BuildingsUsers;
use App\Services\Parsers\AclLogsParser;
use App\Services\Parsers\CronLogsParser;
use App\Services\Parsers\ExtensionLogsParser;
use App\Services\Parsers\LoginLogsParser;
use App\Services\Parsers\PaymentLogsParser;
use App\Services\Parsers\SystemLogsParser;
use App\Services\Parsers\UnitLogsParser;
use App\Services\Parsers\WalletLogsParser;
use App\Services\Parsers\WorkOrderLogsParser;
use App\Services\Parsers\LockerLogsParser;
use App\Services\Parsers\ErrorLogsParser;
use App\Services\Parsers\ActivityLogsParser;
use Illuminate\Http\Request;

class AllLogsController extends Controller
{
    //
    public function index()
    {
        $allLogs = AllLog::all();

        return response()->json([
            "status" => true,
            "data" => $allLogs
        ]);
    }

    public function aclLogs(Request $request)
    {
        BuildingsUsers::create([
            "user_id" => 1,
            "building_id" => 1,
        ]);
        dd("Ad");
        $data = app(AclLogsParser::class)->parse($request);

        return response()->json($data);
    }

    public function cronLogs(Request $request)
    {
        $data = app(CronLogsParser::class)->parse($request);

        return response()->json($data);
    }

    public function extensionLogs(Request $request)
    {
        $data = app(ExtensionLogsParser::class)->parse($request);

        return response()->json($data);
    }

    public function loginLogs(Request $request)
    {
        $data = app(LoginLogsParser::class)->parse($request);

        return response()->json($data);
    }

    public function paymentLogs(Request $request)
    {
        $data = app(PaymentLogsParser::class)->parse($request);

        return response()->json($data);
    }

    public function systemLogs(Request $request)
    {
        $data = app(SystemLogsParser::class)->parse($request);

        return response()->json($data);
    }

    public function unitLogs(Request $request)
    {
        $data = app(UnitLogsParser::class)->parse($request);

        return response()->json($data);
    }

    public function walletLogs(Request $request)
    {
        $data = app(WalletLogsParser::class)->parse($request);

        return response()->json($data);
    }

    public function workOrderLogs(Request $request)
    {
        $data = app(WorkOrderLogsParser::class)->parse($request);

        return response()->json($data);
    }

    public function lockerLogs(Request $request)
    {
        $data = app(LockerLogsParser::class)->parse($request);

        return response()->json($data);
    }

    public function errorLogs(Request $request)
    {
        $data = app(ErrorLogsParser::class)->parse($request);

        return response()->json($data);
    }

    public function activityLogs(Request $request)
    {
        $data = app(ActivityLogsParser::class)->parse($request);

        return response()->json($data);
    }
}
