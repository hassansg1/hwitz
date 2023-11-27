<?php

namespace App\Services\Parsers;


use App\Models\Building;
use App\Models\SystemLog;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Auth;

class EntryControllerParser
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
        $this->applyAssetFilter($request);
        $this->applyDateFilter($request);
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
            $this->query = $this->query->where('triggered_at', '>=', $from);
            $this->query = $this->query->where('triggered_at', '<=', $to);
        }
    }

    /**
     * @param $request
     * @return void
     */
    public function applyBuildingFilter($request)
    {
        $buildings = $request->buildingId;
        $this->query = $this->query->where('system_logs.building_id', $buildings);
    }

    /**
     * @param $request
     * @return void
     */
    public function applyAssetFilter($request)
    {
        if (isset($request->assetId) && $request->assetId != "") {
            $this->query = $this->query->where('system_logs.entity_id', $request->assetId);
        }
    }

    /**
     * @param $request
     * @return void
     */
    public function applyUnitFilter($request)
    {
        if (isset($request->unitId) && $request->unitId != "") {
            $this->query = $this->query->where('system_logs.unit_id', $request->unitId);
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
        } else {
            $this->query = $this->query->orderBy('triggered_at', 'desc');
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
                $query->orWhere('address1', 'LIKE', '%' . $var . '%');
                $query->orWhere('city', 'LIKE', '%' . $var . '%');
                $query->orWhere('action_name', 'LIKE', '%' . $var . '%');
            });
        }
    }

    public function getBaseQuery($request = null)
    {
        return SystemLog::with('building', 'asset', 'triggeredBy', 'unit')->whereIn('entity_name', ['Building Entrance', 'Asset'])
            ->leftJoin('buildings', 'system_logs.building_id', '=', 'buildings.id')
            ->leftJoin('units', 'system_logs.unit_id', '=', 'units.id')
            ->leftJoin('assets', 'system_logs.entity_id', '=', 'assets.id');
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
