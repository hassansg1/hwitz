<?php

namespace App\Services\Parsers;


use App\Models\SmsLogs;
use Illuminate\Database\Eloquent\Builder;

class SmsParser
{
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
        $this->applyUnitFilter($request);
        $this->applyDateFilter($request);
        $this->applySort($request);
        $data = $this->paginate($request);
        return $data;

    }

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
    public function applyUnitFilter($request)
    {
        if (isset($request->buildingId) && $request->buildingId != "") {
            $this->query->where('building_id', $request->buildingId);
        }
    }

    public function getBaseQuery($request = null)
    {
        return SmsLogs::select('*','sms_log.mobile as s_mobile','sms_log.type as s_type')
            ->join('users', 'sms_log.user_id', '=', 'users.id')
            ->join('units', 'units.id', '=', 'users.parent_unit_id')
            ->join('buildings', 'units.building_id', '=', 'buildings.id');
    }

    public function applySort($request)
    {
        if (isset($request->sortOrder) && isset($request->sortBy)) {
            $this->query->orderBy($request->sortBy, $request->sortOrder);
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
