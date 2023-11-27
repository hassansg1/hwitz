<?php

namespace App\Services\Parsers;


use App\Models\TransactionLog;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Request;

class FinanceReceivablesParser
{
    public $query;

    /**
     * @param $request
     * @return Builder[]|\Illuminate\Database\Eloquent\Collection
     */
    public function parse($request)
    {
        $this->query = $this->getBaseQuery($request);
        $this->applySort($request);
        $this->applyBuildingFilter($request);
//        $this->applyDateFilter($request);
        $this->applyGroupByClause($request);
        $data = $this->paginate($request);
        return $data;

    }

    public function applyGroupByClause($request)
    {
        if (isset($request->applyGroupByDate) && $request->applyGroupByDate) {
            $this->query = $this->query->groupBy('timestamp')->select(DB::raw('DATE(timestamp) as timestamp'))->distinct('timestamp');
        }
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

    public function applyBuildingFilter($request)
    {
        if ($request->buildingId) {
            $buildings = [$request->buildingId];
        } else {
            $buildings = getBuildingsByOwner(Auth::id());
            $buildings = $buildings->pluck('id');
        }
        $this->query = $this->query->whereIn('building_id', $buildings);
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
            $this->query = $this->query->orderBy('timestamp', 'desc');

        }
    }

    public function getBaseQuery($request = null)
    {
        return TransactionLog::with('building', 'receiver', 'reconciledPerson')
            ->where('receiver_id', Auth::id())->whereNotNull('transactionid');
    }

    public function paginate($request)
    {
        $perPage = $request->totalItems ?? getEntriesPerPage();
        $currentPage = $request->current_page;

        $total = $this->query->count();
        $data = $this->query->skip($perPage * $currentPage)->take($perPage)->get();
        if (isset($request->applyGroupByDate) && $request->applyGroupByDate) {
            foreach ($data as $key => $item) {
                $timeStamp = date('m/d/Y 00:00:00', strtotime($item->timestamp));
                $timeStamp .= " - " . date('m/d/Y 23:59:00', strtotime($item->timestamp));
                $itemsRequest = new \Illuminate\Http\Request();
                $itemsRequest = $itemsRequest->replace([
                    "buildingId" => $request->buildingId,
                    "date_filter_radio" => 'date_range',
                    "date_filter_value" => $timeStamp,
                    "totalItems" => "1000000000",
                ]);
                $itemsData = $this->parse($itemsRequest);
                $itemsData = $itemsData["data"] ?? null;
                $item->items = $itemsData;
                if ($itemsData)
                    $item->itemsTotalAmount = $itemsData->sum('amount');
                else
                    $item->itemsTotalAmount = 0;
                $data[$key] = $item;
            }

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
