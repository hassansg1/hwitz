<?php

namespace App\Services\Parsers;


use App\Models\Unit;
use Illuminate\Database\Eloquent\Builder;

class PhoneParser
{
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

        $units = Unit::with(['telephoneLogs' => function ($query) use ($from, $to) {
            if ($from && $to) {
                $query->where('date', '>=', $from);
                $query->where('date', '<=', $to);
            }
        }, 'users'])
            ->whereHas('telephoneLogs', function ($query) use ($from, $to) {
                if ($from && $to) {
                    $query->where('date', '>=', $from);
                    $query->where('date', '<=', $to);
                }
            })
            ->where('is_physical', 1);
        if (isset($request->unitId) && $request->unitId != "") {
            $units = $units->where('id', $request->unitId);
        } else {
            $units = $units->where('building_id', $request->buildingId);
        }

        $units = $units->whereNotNull('did_number');
        $units = $units->where('did_number', '!=', '')->get();
        $total_minutesSum = 0;
        $total_callsSum = 0;
        $incomingSum = 0;
        $outgoingSum = 0;
        foreach ($units as $key => $unit) {
            $telephoneLogs = $unit->telephoneLogs;

            $total_minutes = $telephoneLogs->sum('duration') / 60;
            $total_minutesSum += $total_minutes;
            $units[$key]->total_minutes = number_format($total_minutes, 2);

            $total_calls = $telephoneLogs->sum('number_of_call');
            $total_callsSum += $total_calls;
            $units[$key]->total_calls = $total_calls;

            $incoming = $telephoneLogs->where('in', 1)->sum('number_of_call');
            $incomingSum += $incoming;
            $units[$key]->incoming = $incoming;

            $outgoing = $telephoneLogs->where('in', 0)->sum('number_of_call');
            $outgoingSum += $outgoing;
            $units[$key]->outgoing = $outgoing;

        }

        $average = 0;
        if (count($units) > 0)
            $average = number_format(($total_minutesSum / count($units)), 2);

        return [
            "data" => $units,
            "total_minutesSum" => number_format($total_minutesSum, 2),
            "total_callsSum" => $total_callsSum,
            "incomingSum" => $incomingSum,
            "outgoingSum" => $outgoingSum,
            "average" => $average,
        ];

    }
}
