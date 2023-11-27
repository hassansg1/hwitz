<?php

namespace App\Http\Controllers;

use App\Models\Device;
use App\Services\Parsers\DeviceParser;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class DeviceAnalyticsController extends Controller
{
    public function index(Request $request)
    {
        $data = app(DeviceParser::class)->parse($request);

        return response()->json($data);
    }


    /**
     * @return \Illuminate\Http\JsonResponse
     * @throws \Throwable
     */
    public function loadFiltersForDevices(Request $request)
    {
        $builings = $request->buildingId;

        $devicesArray = [];
        $devices = Device::where('pos', '>', 0)->where('pos', '<', 16)->get();
        foreach ($devices as $device) {
            $items = DB::table($device->table_name)->where('building_id', $builings)->select('id', 'name')->get()->toArray();
            if (count($items) > 0) {
                $devicesArray[$device->device]['id'] = $device->id;
                $devicesArray[$device->device]['items'] = $items;
            }
        }

        return response()->json([
            "devices" => $devicesArray
        ]);
    }
}