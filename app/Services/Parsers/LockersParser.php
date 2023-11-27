<?php

namespace App\Services\Parsers;


use App\Models\Asset;
use App\Models\AssetAlert;
use App\Models\AssetLog;
use App\Models\Building;
use App\Models\Locker;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;

class LockersParser
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
        $this->applySearchKeywordFilter($request);
        $this->applyOccupiedFilter($request);
        $this->applyStatusFilter($request);
        $this->applySort($request);
        $data = $this->paginate($request);
        return $data;

    }

    public function applyOccupiedFilter($request)
    {
        if (isset($request->occupied) && $request->occupied == 1) {
            $this->query = $this->query->whereHas('package', function ($query) {
                $query = $query->where('is_received', 0);
            });
        }
        if (isset($request->occupied) && $request->occupied == 0) {
            $this->query = $this->query->whereDoesntHave('package', function ($query) {
                $query = $query->where('is_received', 0);
            });
        }
    }

    public function applyStatusFilter($request)
    {
        if (isset($request->lockerStatus) && $request->lockerStatus != '') {
            $status = $request->lockerStatus;
            if ($status == "vacant") {
                $this->query = $this->query->whereDoesntHave('package', function ($query) {
                    $query->where('is_received', 0);
                });
            }
            if ($status == "occupied") {
                $this->query = $this->query->whereHas('package', function ($query) {
                    $query->where('is_received', 0);
                    $query->where('arrived_at', '>', Carbon::now()->subMinutes(720)->toDateTimeString());
                });
            }
            if ($status == "abandoned") {
                $this->query = $this->query->whereHas('package', function ($query) {
                    $query->where('is_received', 0);
                    $query->where('arrived_at', '<=', Carbon::now()->subMinutes(720)->toDateTimeString());
                });
            }
        }
    }

    public function applySearchKeywordFilter($request)
    {
        if (isset($request->searchKeyword) && $request->searchKeyword != "") {
            $this->query = $this->query->where('label', 'LIKE', '%' . $request->searchKeyword . '%');
        }
    }

    public function getBaseQuery($request = null)
    {
        return Locker::with(['package' => function ($query) {
            $query->select('id', 'locker_id', 'building_id', 'provider_id', 'receiver_id', 'arrived_at', 'package_picture_type', 'is_received'
                , 'received_at', 'received_by', 'token_id', 'is_locked'
            );
            $query->where('is_received', 0);
        }, 'package.receiver', 'lockerScreen'])->where('building_id', $request->buildingId);
    }

    public function applySort($request)
    {
        if (isset($request->sortOrder) && isset($request->sortBy)) {
            $this->query = $this->query->orderBy($request->sortBy, $request->sortOrder);
        } else {
            $this->query = $this->query->orderBy('label', 'asc');
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
