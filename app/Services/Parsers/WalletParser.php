<?php

namespace App\Services\Parsers;


use App\Models\Building;
use App\Models\SystemLog;
use App\Models\Wallets;
use Illuminate\Database\Eloquent\Builder;

class WalletParser
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
        $this->applyUserIdByBuildingFilter($request);
        $this->applyUserIdFilter($request);
        $this->applyTypeFilter($request);
        $this->applySort($request);
        $data = $this->paginate($request);
        return $data;

    }

    /**
     * @param $request
     * @return void
     */
    public function applyTypeFilter($request)
    {
        if (isset($request->type)) {
            $this->query = $this->query->where('wallet_type', $request->type);
        }
    }

    /**
     * @param $request
     * @return void
     */
    public function applyUserIdFilter($request)
    {
        if ($request->userId && $request->userId != "") {
            $this->query = $this->query->where('user_id', $request->userId);
        }
    }

    /**
     * @param $request
     * @return void
     */
    public function applyUserIdByBuildingFilter($request)
    {
        if ($request->buildingId && $request->buildingId != "") {
            $buildings = $request->buildingId;
            $owner = Building::where('id', $buildings)->pluck('user_id')->toArray();
            $this->query = $this->query->whereIn('user_id', $owner);
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
                $query->orWhere('nick_name', 'LIKE', '%' . $var . '%');
                $query->orWhere('card_number', 'LIKE', '%' . $var . '%');
                $query->orWhere('card_expiry', 'LIKE', '%' . $var . '%');
            });
        }
    }

    public function getBaseQuery($request = null)
    {
        return Wallets::with('walletRelation', 'buildings');
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
