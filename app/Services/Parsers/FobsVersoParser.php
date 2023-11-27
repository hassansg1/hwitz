<?php

namespace App\Services\Parsers;


use App\Models\Building;
use App\Models\DoorLog;
use Illuminate\Database\Eloquent\Builder;

class FobsVersoParser
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
        $this->applyDoorFilter($request);
        $this->applyEventFilter($request);
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
    public function applyDoorFilter($request)
    {
        if (isset($request->asset_id) && $request->asset_id != "") {
            $this->query = $this->query->where('door_id', $request->asset_id);
        }
    }

    /**
     * @param $request
     * @return void
     */
    public function applyEventFilter($request)
    {
        if (isset($request->event_name) && is_array($request->event_name)) {
            $eventTypes = $request->event_name;
            $eventTypes = array_filter($eventTypes);
            if (count($eventTypes) > 0)
                $this->query->where(function ($query) use ($eventTypes) {
                    foreach ($eventTypes as $eventType) {
                        $query->orWhere('event_name', 'LIKE', "%$eventType%");
                    }
                });
        }
    }


    public function getBaseQuery($request = null)
    {
        return DoorLog::with('door', 'entryLog', 'doorEventNames');
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
        foreach ($data as $key => $item) {
            $showName = '';
            if ($item->event_name) {
                $showName = $item->event_name;
                $item->show_name = $showName;
            } else {
                $nameItems = $item->doorEventNames->where('event_data1', $item->event_data1)->where('event_data2', $item->event_data2);
                $buildingDescription = $nameItems->where('building_id', $request->buildingId)->first();
                if ($buildingDescription) {
                    $showName = $buildingDescription->event_description;
                    $item->show_name = $showName;
                    break;
                }
                $buildingDescription = $nameItems->where('building_id', 0)->first();
                if ($buildingDescription) {
                    $showName = $buildingDescription->event_description;
                    $item->show_name = $showName;
                    break;
                }
                $showName = $item->event_type . " " . $item->event_data1 . " " . $item->event_data2;
                $item->show_name = $showName;
            }
            $data[$key] = $item;
        }

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
