<?php

namespace App\Services\Parsers;

use App\Models\AchBatch;
use App\Models\Building;
use App\Models\BuildingPackageGroup;
use App\Models\BuildingPackages;
use App\Models\TransactionLog;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Auth;

class OwnerOrderSummaryParser
{
    /**
     * @var Building
     */
    public $query;

    /**
     * @param $request
     * @return Builder[]|\Illuminate\Database\Eloquent\Collection
     */
    public function parse($request)
    {
        if ($request->buildingId) {
            $buildingId = $request->buildingId;
            $buildingPackage = BuildingPackages::with('package')->where('building_id', $buildingId)->first();

            $buildingPackageGroups = BuildingPackageGroup::with(['buildingPackageAddOns.parent',
                'buildingPackageAddOns.addonsUnit' => function ($query) use ($request) {
                    $query->where('building_id', $request->buildingId);
                }
                , 'buildingPackageAddOns.addonsUnit.unit.guarantor', 'parent'])->where('building_id', $buildingId)->get();

            foreach ($buildingPackageGroups as $index => $buildingPackageGroup) {
                $buildingPackageAddons = $buildingPackageGroup->buildingPackageAddOns;
                foreach ($buildingPackageAddons as $key => $addons) {
                    if ($addons->is_added == 1) {
                        $addonsStatus = "Owner Bulk";
                    } else if ($addons->is_added == 0) {
                        $addonsStatus = "Off";
                    } else if ($addons->is_added == 2) {
                        $addonsStatus = "Resident Choice";
                    } else if ($addons->is_added == 3) {
                        $addonsStatus = "3rd Party Billing";
                    }

                    $cartType = $addons->cart_type;
                    $cartLabel = $this->getCartLabel($cartType);
                    if ($addons->is_included == 1) {
                        $addonsStatus = "Included";
                        $addonsPrice = "Included";
                        $addonsPriceResident = "Included";
                    } else {
                        $addonsPrice = $addons->owner_amount;
                        $addonsPriceResident = $addons->resident_amount;
                        $addonsPrice = formatMoneyWithCommas($addonsPrice);
                        $addonsPriceResident = formatMoneyWithCommas($addonsPriceResident);
                    }
                    if ($addons->is_included == 2) {
                        $addonsStatus = "Off";
                        $addonsPrice = "-";
                        $addonsPriceResident = "Included";
                    }

                    $addons->addonStatus = $addonsStatus;
                    $addons->cartLabel = $cartLabel;
                    $addons->addonsPrice = $addonsPrice;
                    $addons->addonsPriceResident = $addonsPriceResident;
                    $buildingPackageAddons[$key] = $addons;
                }
                $buildingPackageGroups[$index]->addons = $buildingPackageAddons;
            }
            $residentIncludedOfferings = [];
            $residentPaidOfferings = [];
            $ownerIncludedOfferings = [];
            $ownerPaidOfferings = [];

            if (isset($request->offering) && $request->offering == "resident") {
                foreach ($buildingPackageGroups as $buildingPackageGroup) {
                    if ($buildingPackageGroup->accessFor == 1 || $buildingPackageGroup->accessFor == 2) {
                        if ($buildingPackageGroup->accessFor == 1) $price = "Included";
                        else if ($buildingPackageGroup->accessFor == 2) $price = formatMoneyWithCommas($buildingPackageGroup->resident_amount);
                        $buildingPackageGroup->price = $price;
                        $array = [
                            "name" => $buildingPackageGroup->parent->name,
                            "rid" => $buildingPackageGroup->rid,
                            "price" => $price,
                            "description" => $buildingPackageGroup->parent->description,
                            "addons_unit" => $buildingPackageGroup->addons_unit ?? null,
                        ];
                        if ($buildingPackageGroup->accessFor == 1) $residentIncludedOfferings[] = $array;
                        else $residentPaidOfferings[] = $array;
                    }
                    foreach ($buildingPackageGroup->addons as $addon) {
                        if ($addon->is_added == 1 || $addon->is_added == 3) {
                            if ($addon->addonStatus == 'Included') $price = "Included";
                            else if ($addon->addonStatus == 'Owner Bulk') $price = "Included";
                            else if ($addon->addonStatus == '3rd Party Billing') $price = formatMoneyWithCommas($addon->resident_amount);
                            $addon->price = $price;

                            $array = [
                                "name" => $addon->parent->name,
                                "rid" => $addon->rid,
                                "price" => $price,
                                "description" => $addon->parent->description,
                                "addons_unit" => $addon->addons_unit ?? null,
                            ];
                            if ($addon->addonStatus == 'Included' || $addon->addonStatus == 'Owner Bulk') $residentIncludedOfferings[] = $array;
                            else $residentPaidOfferings[] = $array;
                        }
                    }
                }

                $price = array_column($residentPaidOfferings, 'price');

                array_multisort($price, SORT_DESC, $residentPaidOfferings);

                $price = array_column($residentIncludedOfferings, 'price');

                array_multisort($price, SORT_DESC, $residentIncludedOfferings);
            }

            if (isset($request->offering) && $request->offering == "owner") {
                foreach ($buildingPackageGroups as $buildingPackageGroup) {
                    if ($buildingPackageGroup->accessFor == 1 || $buildingPackageGroup->accessFor == 2) {
                        $price = formatMoneyWithCommas($buildingPackageGroup->owner_amount);
                        $buildingPackageGroup->price = $price;
                        $array = [
                            "name" => $buildingPackageGroup->parent->name,
                            "rid" => $buildingPackageGroup->rid,
                            "price" => $price,
                            "description" => $buildingPackageGroup->parent->description,
                            "addons_unit" => $buildingPackageGroup->addons_unit ?? null,
                        ];
                        $ownerPaidOfferings[] = $array;
                    }
                    foreach ($buildingPackageGroup->addons as $addon) {
                        if ($addon->is_added == 1 || $addon->is_added == 3) {
                            if ($addon->addonStatus == 'Included') $price = "Included";
                            else if ($addon->addonStatus == 'Owner Bulk') $price = formatMoneyWithCommas($addon->owner_amount);
                            else if ($addon->addonStatus == '3rd Party Billing') $price = formatMoneyWithCommas($addon->owner_amount);
                            $addon->price = $price;

                            $array = [
                                "name" => $addon->parent->name,
                                "rid" => $addon->rid,
                                "price" => $price,
                                "description" => $addon->parent->description,
                                "addons_unit" => $addon->addons_unit ?? null,
                            ];

                            if ($addon->addonStatus == 'Included') $ownerIncludedOfferings[] = $array;
                            else $ownerPaidOfferings[] = $array;
                        }
                    }
                }

                $price = array_column($ownerIncludedOfferings, 'price');

                array_multisort($price, SORT_DESC, $ownerIncludedOfferings);

                $price = array_column($ownerPaidOfferings, 'price');

                array_multisort($price, SORT_DESC, $ownerPaidOfferings);
            }
            $status = true;

            return compact('buildingPackage', 'buildingPackageGroups',
                'residentPaidOfferings', 'residentIncludedOfferings',
                'ownerPaidOfferings', 'ownerIncludedOfferings', 'status');
        }
    }


    public function getCartLabel($cartType)
    {
        $cartLabelByCartType = [
            'rent' => 'Additional Rent',
            'additional_rent' => 'Additional Rent',
            'amenities' => 'Amenities',
            null => ''
        ];

        return $cartLabelByCartType[$cartType];
    }
}