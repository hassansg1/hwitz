<?php

namespace App\Services\Parsers;


use App\Models\Building;
use App\Models\LockerPackage;
use App\Models\Unit;
use Illuminate\Database\Eloquent\Builder;

class LockerPackagesParser
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
        $this->applyLockerFilter($request);
        $this->applyUserFilter($request);
        $this->applyProviderFilter($request);
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
            $this->query = $this->query->where('arrived_at', '>=', $from);
            $this->query = $this->query->where('arrived_at', '<=', $to);
        }
    }

    /**
     * @param $request
     * @return void
     */
    public function applyUnitFilter($request)
    {
        if (isset($request->unitId) && $request->unitId != "") {
            $unit = Unit::find($request->unitId);
            $user_id = $unit->users()->get()->pluck('id');
            $this->query = $this->query->whereIn('receiver_id', $user_id);
        }
    }

    /**
     * @param $request
     * @return void
     */
    public function applyLockerFilter($request)
    {
        if (isset($request->lockerId) && $request->lockerId != "") {
            $this->query = $this->query->where('locker_id', $request->lockerId);
        }
    }

    /**
     * @param $request
     * @return void
     */
    public function applyUserFilter($request)
    {
        if (isset($request->userId) && $request->userId != "") {
            $this->query = $this->query->where('received_by', $request->userId);
        }
    }

    /**
     * @param $request
     * @return void
     */
    public function applyProviderFilter($request)
    {
        if (isset($request->providerId) && $request->providerId != "") {
            $this->query = $this->query->where('provider_id', $request->providerId);
        }
    }

    /**
     * @param $request
     * @return void
     */
    public function applyBuildingFilter($request)
    {
        $buildings = $request->buildingId;
        $this->query = $this->query->where('building_id', $buildings);
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
        return LockerPackage::with('locker','provider','receiver','receivedBy')
            ->select('id','locker_id','building_id','provider_id','receiver_id','arrived_at','package_picture_type','is_received'
            ,'received_at','received_by','token_id','is_locked'
            );
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
