<?php

namespace App\Services\Parsers;


use App\Models\Building;
use App\Models\LinkAbles;
use App\Models\Tasks;
use App\Models\WorkOrder;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Auth;

class PayablesParser
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
        $this->applyStatusFilter($request);
        $this->applySearchKeywordFilter($request);
        $this->applySort($request);
        $data = $this->paginate($request);
        return $data;

    }

    /**
     * @param $request
     * @return void
     */
    public function applyStatusFilter($request)
    {
        if (isset($request->status) && $request->status != "") {
            $this->query = $this->query->where('status', $request->status);
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

    /**
     * @param $request
     * @return void
     */
    public function applySearchKeywordFilter($request)
    {
        if (isset($request->searchKeyword) && $request->searchKeyword != "") {
            $var = $request->searchKeyword;
            $this->query = $this->query->where(function ($query) use ($var) {
                $query->orWhere('token', 'LIKE', '%' . $var . '%');
                $query->orWhere('amount', 'LIKE', '%' . $var . '%');
            });
        }
    }

    public function getBaseQuery($request = null)
    {
        return LinkAbles::with(['details', 'items'])
            ->where('linkable_id', Auth::id())
            ->whereIn('status', ['pending', 'accepted']);
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

        foreach ($data as $key => $d) {
            $data[$key]['pay_now'] = getPaymentLinkByToken($d['token']);
        }

        $returnObj['data'] = $data;

        return $returnObj;
    }
}
