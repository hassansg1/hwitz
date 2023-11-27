<?php

namespace App\Services\Parsers;


use App\Models\Building;
use App\Models\InternetHourly;
use App\Models\Unit;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\DB;

class DataParser
{
    /**
     * @var Building
     */
    public $query;
    public $building;
    public $numbers;

    /**
     * @param $request
     * @return Builder[]|\Illuminate\Database\Eloquent\Collection
     */
    public function parse($request)
    {
        $dates = parseDate($request);
        $from = $dates["from"];
        $to = $dates["to"];


        $units = Unit::whereHas('internetHourly', function ($query) use ($from, $to) {
            if ($from && $to) {
//                $query->where('timestamp', '>=', $from);
//                $query->where('timestamp', '<=', $to);
            }
        })
            ->where('is_physical', 1);
        if (isset($request->unitId) && $request->unitId != "") {
            $units = $units->where('id', $request->unitId);
        } else {
            $units = $units->where('building_id', $request->buildingId);
        }

        $units = $units->get();
        $bandwidth = 0;
        $uniqueDevices = [];
        foreach ($units as $key => $unit) {
            $macs = InternetHourly::where('unit_id', $unit->id)->groupBy('unit_id')->select(DB::raw('SUM(data_in) as incoming_data,SUM(data_out) as outgoing_data,GROUP_CONCAT(DISTINCT mac SEPARATOR "=") as mac_addresses'))->first();
            $value = explode("=",$macs->mac_addresses);
            foreach($value as $mac){
                if(!in_array($mac,$uniqueDevices))
                {
                    $uniqueDevices[] = $mac;
                }
            }

            $units[$key]->macs = $value;

            $incoming = $macs->incoming_data;
            $bandwidth += $incoming;
            $units[$key]->incoming = $incoming;

            $outgoing = $macs->outgoing_data;
            $bandwidth += $outgoing;
            $units[$key]->outgoing = $outgoing;

        }

        $totalUnits =Unit::where('is_physical', 1)->where('building_id', $request->buildingId)->where('status', 1)->count();

        $participation = 0;
        if (count($units) > 0 && $totalUnits > 0) {
            $participation = count($units) / $totalUnits;
            $participation = $participation * 100;
            $participation = number_format($participation, 2);
        }

        return [
            "data" => $units,
            "bandwidth" => $bandwidth,
            "avg_bandwidth" => count($units) > 0 ? formatBytes($bandwidth / count($units)) : 0,
            "unique_devices" => count($uniqueDevices),
            "avg_bandwidth_per_device" => count($uniqueDevices) > 0 ? formatBytes($bandwidth / count($uniqueDevices)) : 0,
            "participation" => $participation,
        ];

    }
}
