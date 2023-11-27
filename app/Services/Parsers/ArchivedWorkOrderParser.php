<?php

namespace App\Services\Parsers;


use App\Models\Building;
use App\Models\Tasks;
use App\Models\WorkOrder;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Auth;

class ArchivedWorkOrderParser
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
        // $this->applySearchKeywordFilter($request);
        $this->applyBuildingFilter($request);
        // $this->applyDateFilter($request);
        $this->applySort($request);
        $data = $this->paginate($request);
        return $data;

    }

    /**
     * @param $request
     * @return void
     */
    public function applyDateFilter($request)
    {
        $dates = parseDate($request);
        $from = $dates["from"];
        $to = $dates["to"];

        if ($from && $to) {
            $this->query = $this->query->where('workorders.created', '>=', $from);
            $this->query = $this->query->where('workorders.created', '<=', $to);
        }
    }

    /**
     * @param $request
     * @return void
     */
    public function applyBuildingFilter($request)
    {
        $buildings = $request->building_id;
        $this->query = $this->query->where('buildings.id', $buildings);
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
        } else {
            $this->query = $this->query->orderBy('id', 'desc');
        }
    }

    /**
     * @param $request
     * @return void
     */
    public function applySearchKeywordFilter($request)
    {
        if (isset($request->searchKeyword) && $request->searchKeyword != "") {
            $var = $request->searchKeyword;
            $this->query = $this->query->where(function ($query) use ($var) {
            });
        }
    }

    public function getBaseQuery($request = null)
    {
        $query =  WorkOrder::with(['maintainer','createdBy','watcher','comments.createdBy','log'])->select('workorders.*', 'buildings.name as building_name', 'buildings.id as building_id','units.unit_no')
            ->leftJoin('users AS User', function ($join) {
                $join->on('workorders.created_by', '=', 'User.id')
                    ->where('User.status', '=', 1);
            })
            ->leftJoin('units_users', 'workorders.resident_id', '=', 'units_users.user_id')
            ->leftJoin('units', 'units.id', '=', 'units_users.unit_id')
            ->leftJoin('buildings', 'buildings.id', '=', 'units.building_id')
            ->whereNotNull('workorders.submitted')
            ->where('workorders.status_id','=','Close');
            
            return $query;
    }

    public function paginate($request)
    {
        $perPage = $request->totalItems ?? getEntriesPerPage();
        $currentPage = $request->current_page;

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
