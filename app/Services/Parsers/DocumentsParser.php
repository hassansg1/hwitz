<?php

namespace App\Services\Parsers;

use App\Models\Building;
use App\Models\Signdoc;
use App\Models\User;
use App\Models\UserTypes;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Auth;

class DocumentsParser
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
        $this->query = $this->getBaseQuery($request);
        $this->applySearchKeywordFilter($request);
        $this->applyBuildingFilter($request);
        $this->applyUnitFilter($request);
        $this->applyUserFilter($request);
        $this->applySort($request);
        $data = $this->paginate($request);
        return $data;

    }

    /**
     * @param $request
     * @return void
     */
    public function applyUserFilter($request)
    {
        if (isset($request->userId) && $request->userId && $request->userId != "")
            $this->query = $this->query->where('signdoc.doc_to', 'LIKE', '%' . $request->userId . '%');
    }

    /**
     * @param $request
     * @return void
     */
    public function applyUnitFilter($request)
    {
        if (isset($request->unitId) && $request->unitId && $request->unitId != "")
            $this->query = $this->query->where('signdoc.unit_id', $request->unitId);
    }

    /**
     * @param $request
     * @return void
     */
    public function applyBuildingFilter($request)
    {
        if (isset($request->buildingId) && $request->buildingId && $request->buildingId != "")
            $this->query = $this->query->where('signdoc.building_id', $request->buildingId);
    }

    /**
     * @param $request
     * @return void
     */
    public function applySort($request)
    {
        if (isset($request->sortBy) && $request->sortBy != "") {
            $sortOrder = $request->sortOrder ?? 'asc';
            $this->query = $this->query->orderBy($request->sortBy, $sortOrder);
        }
    }

    /**
     * @param $request
     * @return void
     */
    public function applySearchKeywordFilter($request)
    {
        if (isset($request->searchKeyword) && $request->searchKeyword != "") {
            $search_keyword = $request->searchKeyword;

            $user_id = $request->userId != "" ? $request->userId : Auth::id();
            $user = User::find($user_id);
            $usertype_id = $user->usertype_id ?? null;
            $selectedBuilding = $request->buildingId;

            if ($usertype_id == UserTypes::$admin) {

            }

            if (isset($selectedBuilding) && $selectedBuilding != "") {
                $building = Building::find($selectedBuilding);

                if ($usertype_id == UserTypes::$owner) {
                    $owner = $building->user;
                    $buildings_array = Building::where('user_id', $owner->id)->pluck('id')->toArray();
                } else {
                    $buildings_array = $user->buildingIds();
                }

            } else {
                if ($usertype_id == UserTypes::$owner) {
                    $buildings_array = Building::where('user_id', $user->id)->pluck('id')->toArray();
                } else {
                    $buildings_array = $user->buildingIds();
                }
            }

            $building_str = implode(',', array_values($buildings_array));
            $building_str = strlen($building_str) ? $building_str : 0;

            if ($search_keyword != '') {
                $this->query = $this->query->where(function ($query) use ($search_keyword) {
                    $query->orWhere('users.firstname', 'LIKE', "%$search_keyword%");
                    $query->orWhere('users.middlename', 'LIKE', "%$search_keyword%");
                    $query->orWhere('users.zipcode', 'LIKE', "%$search_keyword%");
                    $query->orWhere('users.firstname', 'LIKE', "%$search_keyword%");
                    $query->orWhere('users.email', 'LIKE', "%$search_keyword%");
                    $query->orWhere('users.mobile', 'LIKE', "%$search_keyword%");
                    $query->orWhere('users.ssn', 'LIKE', "%$search_keyword%");
                    $query->orWhere('packets.packet_name', 'LIKE', "%$search_keyword%");
                });

                $this->query = $this->query->leftJoin('users as users', function ($join) {
                    $join->on('users.id', '=', 'signdoc.current_sign_user');
                });

                $this->query = $this->query->leftJoin('packets as packets', function ($join) {
                    $join->on('packets.id', '=', 'signdoc.packet_id');
                });

                $this->query = $this->query->leftJoin('units as Units', function ($join) {
                    $join->on('Units.id', '=', 'signdoc.unit_id');
                });
                $this->query = $this->query->leftJoin('buildings as Building', function ($join) {
                    $join->on('Building.id', '=', 'signdoc.building_id');
                });
                if ($usertype_id != 1) {
                    $this->query = $this->query->where(function ($subQuery) use ($user_id, $buildings_array) {
                        $subQuery->orWhere('users.owner_id', $user_id);
                        $subQuery->orWhereIn('signdoc.building_id', $buildings_array);
                    });
                }
                $this->query = $this->query->select("signdoc.*");

            }
        }
    }

    public function getBaseQuery($request = null)
    {
        return Signdoc::with('packet', 'signUser')->where('signdoc.status', '0');
    }

    public function paginate($request)
    {
        $perPage = $request->totalItems ?? 10;
        $currentPage = $request->currentPage;

        $this->query = $this->query->orderBy('signdoc.id', 'desc');
        $total = $this->query->count();
        $data = $this->query->skip($perPage * $currentPage)->take($perPage)->get();

        $returnObj['currentPage'] = $currentPage;
        $returnObj['request'] = $request;
        $returnObj['totalPage'] = ceil(($total / $perPage));
        $returnObj['perPage'] = $perPage;
        $returnObj['currentPageCount'] = count($data);
        $returnObj['total'] = $total;
        $returnObj['currentStart'] = ($perPage * $currentPage) + 1;
        $returnObj['currentEnd'] = ($perPage * $currentPage) + count($data);
        $returnObj['data'] = $data;


        return $returnObj;
    }
}
