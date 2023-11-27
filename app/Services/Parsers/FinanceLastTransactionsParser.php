<?php

namespace App\Services\Parsers;


use App\Models\TransactionLog;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Auth;

class FinanceLastTransactionsParser
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
        $this->applyCartFilter($request);
        $this->applyDateFilter($request);
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

    public function applyCartFilter($request)
    {
        if (isset($request->cartRadio) && $request->cartRadio && $request->cartRadio != "") {
            if ($request->cartRadio == 4)
                $cartArray = [$request->cartRadio, 1];
            else
                $cartArray = [$request->cartRadio];
        }

        if (isset($request->cartRadio) && $request->cartRadio && $request->cartRadio != "") {
            $this->query = $this->query->whereIn('trans_source_id', $cartArray);
        }
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

    public function getBaseQuery($request = null)
    {
        return TransactionLog::with('reconciledPerson')->where(function ($query) use ($request) {
            $query->where('user_id', Auth::id())->orWhere('receiver_id', Auth::id());
        });
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
