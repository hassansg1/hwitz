<?php

namespace App\Http\Controllers;

use App\Models\Building;
use App\Models\DefaultSequence;
use App\Models\UnitNotices;
use Illuminate\Http\Request;

class NonPerformanceController extends Controller
{
    //
    public function index(Request $request)
    {
        try {
            $nonPerformance = UnitNotices::with('unit.guarantor', 'unit.building')->where('is_cleared', 0)->get();

            return response()->json([
                "status" => true,
                "courtesy" => $nonPerformance->where('type', 'courtesy'),
                "default" => $nonPerformance->where('type', 'default'),
                "shutoff" => $nonPerformance->where('type', 'shutoff'),
            ]);
        } catch (\Exception $exception) {
            return response()->json([
                "status" => false,
                "message" => $exception->getMessage(),
            ]);
        }
    }

    public function loadSequenceData(Request $request)
    {
        $buildingId = $request->buildingId;
        if (!$buildingId) return response()->json(['status' => 'false', 'message' => 'No building selected. Please select building first and try again.']);

        $building = Building::find($buildingId);
        $sequenceId = $building->resident_rent_sequence;
        if (!$sequenceId)
            return response()->json(['status' => 'false', 'message' => 'No sequence selected for the building. Please select sequence first and try again.']);


        $sequence = DefaultSequence::with(
            [
                'sequenceItems' => function ($query) {
                    $query->with('itemTemplates');
                }
            ]
        )
            ->where(
                [
                    'id' => $sequenceId
                ]
            )->first();


        if (!$sequence)
            return response()->json(['status' => 'false', 'message' => 'Sequence not found.']);

        return response()->json(
            [
                'status' => 'true',
                'data' => $sequence
            ]);
    }
}
