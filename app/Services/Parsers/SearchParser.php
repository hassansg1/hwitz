<?php

namespace App\Services\Parsers;

use App\Models\Building;
use App\Models\User;
use App\Models\UserTypes;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Auth;

class SearchParser
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
        $this->applyCategoryFilter($request);
        $this->applySort($request);
        $data = $this->paginate($request);
        return $data;

    }

    /**
     * @param $request
     * @return void
     */
    public function applyCategoryFilter($request)
    {
        if (isset($request->usertypeid) && $request->usertypeid != "") {
            $this->query = $this->query->where('users.usertype_id', $request->usertypeid);
        }
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

            if (isset($selectedBuilding) && $selectedBuilding != "") {
                $buildings_array = [$selectedBuilding];
            } else {
                $buildings_array = $user->buildingIds();
            }

            if ($search_keyword != '') {
                $this->query = $this->query->where(function ($query) use ($search_keyword) {
                    $query->orWhere('users.firstname', 'LIKE', "%$search_keyword%");
                    $query->orWhere('users.middlename', 'LIKE', "%$search_keyword%");
                    $query->orWhere('users.zipcode', 'LIKE', "%$search_keyword%");
                    $query->orWhere('users.firstname', 'LIKE', "%$search_keyword%");
                    $query->orWhere('users.email', 'LIKE', "%$search_keyword%");
                    $query->orWhere('users.mobile', 'LIKE', "%$search_keyword%");
                    $query->orWhere('users.ssn', 'LIKE', "%$search_keyword%");
                });

                $this->query = $this->query->leftJoin('usertypes as UserType', function ($join) {
                    $join->on('UserType.id', '=', 'users.usertype_id');
                });
                $this->query = $this->query->leftJoin('units_users as UnitsUser', function ($join) {
                    $join->on('UnitsUser.user_id', '=', 'users.id');
                });
                $this->query = $this->query->leftJoin('units as Units', function ($join) {
                    $join->on('Units.id', '=', 'UnitsUser.unit_id');
                });
                $this->query = $this->query->leftJoin('buildings as Building', function ($join) {
                    $join->on('Building.id', '=', 'Units.building_id');
                });
                $this->query = $this->query->leftJoin('users as Owner', function ($join) {
                    $join->on('Building.user_id', '=', 'Owner.id');
                });
                if (isset($request->unitId) && $request->unitId != "" && $request->unitId) {
                    $this->query = $this->query->where('Units.id', $request->unitId);
                }
                if (isset($selectedBuilding) && $selectedBuilding != "") {
                    $this->query = $this->query->where(function ($subQuery) use ($user_id, $buildings_array) {
                        $subQuery->orWhereIn('building_id', $buildings_array);
                    });
                } else {
                    $this->query = $this->query->where(function ($subQuery) use ($user_id, $buildings_array) {
                        $subQuery->orWhere('users.owner_id', $user_id);
                        $subQuery->orWhereIn('building_id', $buildings_array);
                    });
                }
                $this->query = $this->query->select("Units.id as unit_id_uit", "Owner.firstname as owner_first_name", "Owner.lastname as owner_last_name", "Units.unit_no", "Building.id as building_id", "Building.name as building_name", "Building.city as building_city", "users.*", "users.id as userId", "users.status as user_status", 'UserType.name as user_type_name');
            }
        }
    }

    public function getBaseQuery($request = null)
    {
        return new User();
    }

    public function paginate($request)
    {
        $perPage = $request->totalItems ?? 10;
        $currentPage = $request->currentPage;

        $this->query = $this->query->orderBy('users.id', 'desc');
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
