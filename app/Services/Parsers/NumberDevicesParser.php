<?php

namespace App\Services\Parsers;


use App\Models\InternetBuildings;
use Illuminate\Support\Facades\DB;

class NumberDevicesParser
{
    /**
     * @param $request
     * @return array
     */
    public function parse($request)
    {

        $dates = parseDate($request);
        $from = $dates["from"];
        $to = $dates["to"];

        $devices = InternetBuildings::select('mac_address', DB::raw('count(mac_address) as count'))
            ->where('building_id', $request->buildingId)
            ->groupBy('mac_address');

        if ($from && $to) {
            $devices->whereBetween('date', [$from, $to]);
        }

        $devices = $devices->get()->toArray();
        $return = '';
        if (isset($from) && isset($to)) {
            $return .= " (For Date Range " . date('m/d/Y', strtotime($from)) . " - " . date('m/d/Y', strtotime($to)) . ")";
        }

        return [
            'total' => count($devices),
            'devices' => $devices,
            'dateRange' => $return ?? ''
        ];
    }
}
