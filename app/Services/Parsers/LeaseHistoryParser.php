<?php

namespace App\Services\Parsers;


use App\Models\UnitHistory;
use Illuminate\Database\Eloquent\Builder;

class LeaseHistoryParser
{
    /**
     * @var Building
     */
    public $query;
    public $building;

    /**
     * @param $request
     * @return Builder[]|\Illuminate\Database\Eloquent\Collection
     */
    public function parse($request)
    {
        $this->query = $this->getBaseQuery($request);
        $this->applyUnitFilter($request);
        $this->applySort($request);
        $data = $this->paginate($request);
        return $data;

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

    public function applyUnitFilter($request)
    {
        $this->query = $this->query->where('unit_id', $request->unitId);
    }

    public function getBaseQuery($request = null)
    {
        return UnitHistory::with('user');
    }

    public function paginate($request)
    {
        $perPage = $request->totalItems ?? 100;
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
