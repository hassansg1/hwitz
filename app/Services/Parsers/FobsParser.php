<?php

namespace App\Services\Parsers;


use App\Models\Asset;
use App\Models\AssetAlert;
use App\Models\AssetLog;
use App\Models\Building;
use Illuminate\Database\Eloquent\Builder;
use function MongoDB\BSON\toJSON;

class FobsParser
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
        $this->events = AssetAlert::pluck('event_type')->toArray();
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
        $entryFilter = $request->entry_type;
        $buildingId = $request->buildingId;

        $assetAlert = new AssetAlert();
        if ($entryFilter == 'alarms') {
            $assetAlert = $assetAlert->whereIn('category', [1, 4]);
        }

        if (isset($request->no_alarm) && $request->no_alarm == 1) {
            $assetAlert = $assetAlert->where('category', '!=', 1);
            $assetAlert = $assetAlert->where('category', '!=', 4);
        }

        $event_types = $assetAlert->pluck('event_type')->toArray();

        if ($entryFilter == "all") {
            $assets = Asset::where('building_id', $buildingId)->pluck('id')->toArray();
        } else {
            $assets = [$entryFilter];
        }
        if ($entryFilter == 'uk-user-fobs') {
            $this->query = $this->query->where(function ($query) {
                $query->orWhere('token_id', 0);
                $query->orWhere('token_id', null);
            });
            $this->query = $this->query->where(function ($query) {
                $query->orWhere('user_id', 0);
                $query->orWhere('user_id', null);
            });
        }

        if (isset($request->mail_man) && $request->mail_man == 1) {
            $this->query = $this->query->where('event_type', 12031);
        } else {
            if ($entryFilter == 'uk-user-fobs' && $request->unit_id == "") {
                $this->query = $this->query->whereIn('event_type', $event_types);
            } elseif (($entryFilter == 'alarms') && ($request->no_alarm == '0' || $request->no_alarm == '' || !isset($request->no_alarm))) {
                $this->query = $this->query->whereIn('event_type', $event_types);
            } elseif ($entryFilter == 'alarms' && $request->no_alarm == '1') {
                $this->query = $this->query->where('event_type', '');
            } elseif ($entryFilter != 'grant') {
                $this->query = $this->query->whereIn('asset_id', $assets);
                $this->query = $this->query->whereIn('event_type', $event_types);
            }
        }
        if ($request->unit_id != 'all' && $request->unit_id != '' && $entryFilter != 'uk-user-fobs') {
            $this->query = $this->query->where('unit_id', $request->unit_id);
        } elseif ($request->unit_id != 'all' && $request->unit_id != '' && $entryFilter == 'uk-user-fobs') {
            $this->query = $this->query->where('unit_id', $request->unit_id);
        }
        if (isset($request->mail_man) && $request->mail_man != '') {
            $this->query = $this->query->whereRaw('assets_log.id IN ( SELECT assets_log_id FROM entry_log WHERE assets_log_id = assets_log.id AND mailman = 1)');
        }
        if (isset($request->unreadable) && $request->unreadable != '') {
            $this->query = $this->query->where('event_type', 2043)->whereNull('token_id');
        }
        if ($entryFilter == 'grant') {
            $this->query = $this->query->where('event_type', 12031);
        }
        if (isset($request->images_only) && $request->images_only == 1) {
            $this->query = $this->query->whereHas('entryLog');
        }

    }

    public function getBaseQuery($request = null)
    {
        return AssetLog::with('building', 'user', 'unit', 'asset', 'token', 'entryLog');
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
