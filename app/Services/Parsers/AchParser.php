<?php

namespace App\Services\Parsers;

use App\Models\AchBatch;
use App\Models\AchBatchItem;
use App\Models\Building;
use App\Models\LinkAbles;
use App\Models\Tasks;
use App\Models\WorkOrder;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Auth;

class AchParser
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

    public function getBaseQuery($request)
    {
        if (isset($request->buildingId) && $request->buildingId != "" && $request->buildingId) {
            $buildingId = [$request->buildingId];
        } else {
            $buildingId = getBuildingsByOwner(Auth::id());
            $buildingId = $buildingId->pluck('id')->toArray();
        }
        $query = AchBatch::with(['items' => function ($query) use ($buildingId) {
            $query->whereIn('building_id', $buildingId);
        }, 'items.wallet', 'items.receiver', 'items.building'])->whereHas('items', function ($query) use ($buildingId) {
            $query->whereIn('building_id', $buildingId);
        });
        return $query;
    }

    public function paginate($request)
    {
        $perPage = $request->totalItems ?? getEntriesPerPage();
        $currentPage = $request->current_page;

        $total = $this->query->count();
        $data = $this->query->skip($perPage * $currentPage)->take($perPage)->get();

        foreach ($data as $index => $element) {
            $sum = $element->items->sum('amount');
            $element->amount = round($sum,2);
            $data[$index] = $element;
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
