<?php

namespace App\Services\Parsers;


use App\Models\BuildingPackageAddon;
use App\Models\BuildingPackageGroup;
use App\Models\TransactionLog;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Request;

class OwnerOfferingsParser
{
    public $query;

    /**
     * @param $request
     * @return Builder[]|\Illuminate\Database\Eloquent\Collection
     */
    public function parse($request)
    {
        $data = BuildingPackageAddon::with(['feature', 'addonsUnit' => function ($query) use ($request) {
            $query->where('building_id', $request->buildingId);
        }, 'addonsUnit.unit.guarantor'])->where('is_added', 1)->where('building_id', $request->buildingId)->get();


        $groupData = BuildingPackageGroup::with(['group'])->where('accessFor', 1)->where('building_id', $request->buildingId)->get();

        return [
            "status" => true,
            "data" => $data,
            "offeringsGroups" => $groupData,
        ];
    }
}
