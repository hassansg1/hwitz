<?php

namespace App\Services\Parsers;


use App\Models\Building;
use App\Models\TransactionLog;
use Illuminate\Database\Eloquent\Builder;

class LaundryParser
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
        $this->applyBuildingFilter($request);
        $this->applyDateFilter($request);
        $this->applyLaundryFilter($request);
        $this->applyUnitFilter($request);
        $this->applyUserFilter($request);
        $this->applyApplianceFilter($request);
        $this->applySort($request);
        $data = $this->paginate($request);
        return $data;

    }

    /**
     * @param $request
     * @return void
     */
    public function applyApplianceFilter($request)
    {
        if (isset($request->appliance_id) && $request->appliance_id != "") {
            $this->query = $this->query->where('laundrymachine_id', $request->appliance_id);
        }
    }


    /**
     * @param $request
     * @return void
     */
    public function applyUserFilter($request)
    {
        $this->query = $this->query->whereHas('user');
        if (isset($request->user_id) && $request->user_id != "") {
            $this->query = $this->query->where('user_id', $request->user_id);
        }
    }

    /**
     * @param $request
     * @return void
     */
    public function applyLaundryFilter($request)
    {
        $this->query = $this->query->whereNotNull('laundry_id');
        if (isset($request->laundry_id) && $request->laundry_id != "") {
            $this->query = $this->query->where('laundry_id', $request->laundry_id);
        }
    }


    /**
     * @param $request
     * @return void
     */
    public function applyBuildingFilter($request)
    {
        if (isset($request->building_id) && $request->building_id != "") {
            $this->query = $this->query->where('building_id', $request->building_id);
        }
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
    public function applyUnitFilter($request)
    {
        if (isset($request->unit_id) && $request->unit_id != "") {
            $this->query = $this->query->where('unit_id', $request->unit_id);
        }
    }

    public function getBaseQuery($request = null)
    {
        return TransactionLog::with(['building', 'transactionType','laundryMachine','laundry', 'user' => function ($query) {
            $query->with('token');
        }]);
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
        $perPage = $request->totalItems ??  getEntriesPerPage();
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
