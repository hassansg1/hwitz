<?php

namespace App\Http\Controllers;

use App\Models\LinkAbles;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use League\CommonMark\Extension\CommonMark\Node\Inline\Link;

class LaundryPaymentController extends Controller
{
    //

    public function addMoneyLaundry(Request $request)
    {
        $buildingId = $request->buildingId;
        if (!$buildingId)
            return response()->json(['status' => 'false', 'message' => 'No building selected. Please select building first and try again.']);

        if (!checkIfBuildingBelongsToOwner($request->buildingId)) {
            return response()->json(
                [
                    "status" => false,
                    "message" => "Unauthorized action.",
                ]
            );
        }

        $user = Auth::user();

        LinkAbles::where('type', 'owner_laundry')->where('linkable_id', $user->id)->delete();

        $linkables = LinkAbles::createLinkAble([], $user->id, $user->unit->id ?? null,
            generatePaymentLinkToken(), "owner_laundry");

        return response()->json(
            [
                "status" => true,
                "message" => getPaymentLinkByToken($linkables->token),
            ]
        );
    }

    public function startAppliance(Request $request)
    {
        $buildingId = $request->buildingId;
        if (!$buildingId)
            return response()->json(['status' => 'false', 'message' => 'No building selected. Please select building first and try again.']);

        if (!checkIfBuildingBelongsToOwner($request->buildingId)) {
            return response()->json(
                [
                    "status" => false,
                    "message" => "Unauthorized action.",
                ]
            );
        }

        dd($request->all());
        $user = Auth::user();

        LinkAbles::where('type', 'owner_laundry')->where('linkable_id', $user->id)->delete();

        $linkables = LinkAbles::createLinkAble([], $user->id, $user->unit->id ?? null,
            generatePaymentLinkToken(), "owner_laundry");

        return response()->json(
            [
                "status" => true,
                "message" => getPaymentLinkByToken($linkables->token),
            ]
        );
    }
}
