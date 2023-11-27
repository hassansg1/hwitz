<?php

namespace App\Services\Parsers;


use App\Models\Building;
use App\Models\LaundryMachineStateLog;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;

class ApplianceStateParser
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
        $this->applyDateFilter($request);
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
            $this->query = $this->query->where('laundrymachines.id', $request->appliance_id);
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

//        if ($from && $to) {
//            $this->query = $this->query->where('action_date', '>=', $from);
//            $this->query = $this->query->where('action_date', '<=', $to);
//        }
    }

    public function getBaseQuery($request = null)
    {
        $query = LaundryMachineStateLog::with(['laundryMachine.laundryName', 'user', 'laundryMachine.building', 'laundryMachineState'])
            ->join('laundrymachines', 'laundrymachines.id', '=', 'laundrymachines_state_log.machine_id')
            ->join('buildings', 'buildings.id', '=', 'laundrymachines.building_id')
            ->select('laundrymachines_state_log.*');
        if ($request->buildingId && $request->buildingId != "") {
            $query = $query->where('laundrymachines.building_id', $request->buildingId);
        }
        return $query;
    }


    public function applySort($request)
    {
        if (isset($request->sortOrder) && isset($request->sortBy)) {
            $this->query = $this->query->orderBy($request->sortBy, $request->sortOrder);
        } else {
            $this->query = $this->query->orderBy('action_date', 'desc');
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
