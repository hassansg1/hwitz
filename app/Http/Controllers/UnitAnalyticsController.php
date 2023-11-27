<?php

namespace App\Http\Controllers;

use App\Models\SystemLog;
use App\Models\Unit;
use App\Services\Parsers\UnitsParser;
use Illuminate\Http\Request;

class UnitAnalyticsController extends Controller
{
    public function index(Request $request)
    {
        $data = app(UnitsParser::class)->parse($request);

        return response()->json($data);
    }

    /**
     * @return \Illuminate\Http\JsonResponse
     * @throws \Throwable
     */
    public function loadUnitActivityData(Request $request)
    {
        $unit = Unit::with('building')->where('id', $request->unit_id)->first();
        $items = SystemLog::with('triggeredBy.usertype')->where('unit_id', $request->unit_id);
        $items = $items->whereIn('entity_name',
            [
                SystemLog::MODULE_RESIDENT,
                SystemLog::MODULE_UNIT,
                SystemLog::MODULE_UNIT_SHUT_OFF,
                SystemLog::MODULE_NOTIFY_OF_ENTRY,
                SystemLog::MODULE_INTERNET_SERVICE,
                SystemLog::MODULE_TELEPHONE_SERVICE,
                SystemLog::MODULE_APPLIANCE,
                'Asset',
                'Name Display',
                'Door Phone'
            ]
        )->orderBy('triggered_at', 'desc')->get();
        foreach ($items as $key => $item) {
            $fobsList = [];
            if (!empty($item->notes) &&
                (in_array($item->action_name, array(\App\Models\SystemLog::ACTION_RESIDENT_FOB_ASSIGN, \App\Models\SystemLog::ACTION_RESIDENT_FOB_UNASSIGN, \App\Models\SystemLog::ACTION_RESIDENT_FOB_TURNED_ON, \App\Models\SystemLog::ACTION_RESIDENT_FOB_TURNED_OFF, \App\Models\SystemLog::ACTION_RESIDENT_FOBM_TURNED_ON, \App\Models\SystemLog::ACTION_RESIDENT_FOBM_TURNED_OFF))
                    || $item->entity_name == \App\Models\SystemLog::MODULE_UNIT_SHUT_OFF)) {
                $notes = json_decode($item->notes);
                if (is_array($notes)) {
                    foreach ($notes as $row)
                        if (is_object($row)) {
                            $fobsList[] = $row->card_id;
                        }
                }
            }
            $item->fobsList = $fobsList;
            $items[$key] = $item;
        }

        return response()->json([
            'unit' => $unit,
            'items' => $items,
        ]);
    }
}