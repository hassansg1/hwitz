<?php

namespace App\Services\Parsers;


use App\Models\AclLogs;
use App\Models\StaffSubRoles;
use App\Models\SubRoles;
use Illuminate\Database\Eloquent\Builder;

class StaffSubRolesParser
{
    /**
     */
    public $query;

    /**
     * @param $request
     * @return Builder[]|\Illuminate\Database\Eloquent\Collection
     */
    public function parse($request)
    {
        $this->query = $this->getBaseQuery($request);
        $this->applySort($request);
        $this->applySubRoleFilter($request);
        $this->applyBuildingFilter($request);
        $this->applyUserFilter($request);
        $data = $this->paginate($request);
        return $data;

    }

    /**
     * @param $request
     * @return void
     */
    public function applyBuildingFilter($request)
    {
        if (isset($request->buildingId) && $request->buildingId != "") {
            $this->query = $this->query->where('building_id', $request->buildingId);
        }
    }

    /**
     * @param $request
     * @return void
     */
    public function applySubRoleFilter($request)
    {
        if (isset($request->roleAlias) && $request->roleAlias != "") {
            $this->query = $this->query->where('sub_role', $request->roleAlias);
        }
    }

    /**
     * @param $request
     * @return void
     */
    public function applyUserFilter($request)
    {
        if (isset($request->userId) && $request->userId != "") {
            $this->query = $this->query->where('user_id', $request->userId);
        }
    }

    /**
     * @param $request
     * @return void
     */
    public function applySort($request)
    {
        if (isset($request->sortBy) && $request->sortBy != "") {
            $sortOrder = $request->sortOrder ?? 'desc';
            $this->query = $this->query->orderBy($request->sortBy, $sortOrder);
        } else {
            $this->query = $this->query->orderBy('id', 'desc');

        }
    }

    public function getBaseQuery($request = null)
    {
        $query = StaffSubRoles::with('createdBy', 'user', 'subRole', 'building');
        return $query;
    }

    public function paginate($request)
    {
        $perPage = $request->totalItems ?? getEntriesPerPage();
        $currentPage = $request->current_page ?? 0;

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
