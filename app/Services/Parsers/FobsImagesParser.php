<?php

namespace App\Services\Parsers;


use App\Models\Asset;
use App\Models\AssetAlert;
use App\Models\AssetLog;
use App\Models\Building;
use App\Models\EntryLog;
use Illuminate\Database\Eloquent\Builder;
use function MongoDB\BSON\toJSON;

class FobsImagesParser
{
    /**
     * @var Building
     */
    public $query;
    public $building;
    public $events;

    /**
     * @param $request
     * @return Builder[]|\Illuminate\Database\Eloquent\Collection
     */
    public function parse($request)
    {
        $this->query = $this->getBaseQuery($request);
        $this->applyBuildingFilter($request);
        $this->applySearchKeywordFilter($request);
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
            $this->query = $this->query->where('timestamp', '>=', $from);
            $this->query = $this->query->where('timestamp', '<=', $to);
        }
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
    public function applySearchKeywordFilter($request)
    {
        if ($request->asset_id == "all")
            $assets = Asset::where('building_id', $request->buildingId)
                ->where('has_camera', 1)
                ->pluck('id')->toArray();
        else{
            $assets = [$request->asset_id];
        }

        $this->query = $this->query->whereNotNull('assets_log_id');
        $this->query = $this->query->whereIn('device',$assets);
    }

    public function getBaseQuery($request = null)
    {
        return new EntryLog();
    }

    public function applySort($request)
    {
        if (isset($request->sortOrder) && isset($request->sortBy)) {
            $this->query = $this->query->orderBy($request->sortBy, $request->sortOrder);
        } else {
            $this->query = $this->query->orderBy('timestamp', 'desc');
        }
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
