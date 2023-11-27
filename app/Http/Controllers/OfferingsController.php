<?php

namespace App\Http\Controllers;

use App\Services\Parsers\OwnerOfferingsParser;
use App\Services\Parsers\OwnerOrderSummaryParser;
use App\Services\Parsers\ResidentOfferingsParser;
use Illuminate\Http\Request;

class OfferingsController extends Controller
{
    //
    public function index(Request $request)
    {
        if (!checkIfBuildingBelongsToOwner($request->buildingId)) {
            return response()->json(
                [
                    "status" => false,
                    "message" => "Unauthorized action.",
                ]
            );
        }

        $data = app(OwnerOrderSummaryParser::class)->parse($request);

        return response()->json($data);
    }

    public function ownerOfferings(Request $request)
    {
        if (!checkIfBuildingBelongsToOwner($request->buildingId)) {
            return response()->json(
                [
                    "status" => false,
                    "message" => "Unauthorized action.",
                ]
            );
        }

        $data = app(OwnerOfferingsParser::class)->parse($request);

        return response()->json($data);
    }
}
