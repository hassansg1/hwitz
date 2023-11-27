<?php

namespace App\Services\Parsers;


use App\Models\AclLogs;
use App\Models\CronLogs;
use Illuminate\Database\Eloquent\Builder;

class CronLogsParser
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
        $this->applyBuildingFilter($request);
        $this->applyCategoryFilter($request);
        $this->applyUnitFilter($request);
        $this->applyStatusFilter($request);
        $this->applyUserFilter($request);
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
    public function applyCategoryFilter($request)
    {
        if (isset($request->category) && $request->category && $request->category != "")
            $this->query = $this->query->where('cron_category_id', $request->category);
    }

    /**
     * @param $request
     * @return void
     */
    public function applyStatusFilter($request)
    {
        if (isset($request->status) && $request->status && $request->status != "")
            $this->query = $this->query->where('status', $request->status);
    }

    /**
     * @param $request
     * @return void
     */
    public function applyUserFilter($request)
    {
        if (isset($request->userId) && $request->userId && $request->userId != "")
            $this->query = $this->query->where('user_id', $request->userId);
    }

    /**
     * @param $request
     * @return void
     */
    public function applyUnitFilter($request)
    {
        if (isset($request->unitId) && $request->unitId && $request->unitId != "")
            $this->query = $this->query->where('unit_id', $request->unitId);
    }

    /**
     * @param $request
     * @return void
     */
    public function applyBuildingFilter($request)
    {
        if (isset($request->buildingId) && $request->buildingId && $request->buildingId != "")
            $this->query = $this->query->where('building_id', $request->buildingId);
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

    /**
     * @param $request
     * @return void
     */
    public function applySearchKeywordFilter($request)
    {
        if (isset($request->searchKeyword) && $request->searchKeyword != "") {
            $var = $request->searchKeyword;
            $this->query = $this->query->where(function ($query) use ($var) {
                $query->orWhere('title', 'LIKE', '%' . $var . '%');
            });
        }
    }

    public function getBaseQuery($request = null)
    {
        $query = CronLogs::with('category', 'building', 'unit', 'user');
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
