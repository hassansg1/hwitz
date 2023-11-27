<?php

namespace App\Services\Parsers;


use App\Models\Unit;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Auth;

class UnitsParser
{
    /**
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
        $this->applyOccupiedCheck($request);
        $this->applySort($request);
        $data = $this->paginate($request);
        return $data;

    }

    /**
     * @param $request
     * @return void
     */
    public function applyOccupiedCheck($request)
    {
        if ($request->occupied && $request->occupied) {
            $this->query = $this->query->whereHas('users');
        } elseif ($request->occupied && $request->occupied == 0) {
            $this->query = $this->query->whereDoesntHave('users');
        }
    }

    /**
     * @param $request
     * @return void
     */
    public function applyBuildingFilter($request)
    {
        $this->query = $this->query->where('building_id', $request->buildingId);
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
            $this->query = $this->query->orderBy('unit_no', 'asc');

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
                $query->orWhere('unit_no', 'LIKE', '%' . $var . '%');
            });
        }
    }

    public function getBaseQuery($request = null)
    {
        $query =   Unit::with('users.units.building', 'building', 'unitType', 'guarantor');
        if($request->unit_id) $query = $query->where('id',$request->unit_id);
        return $query;
    }

    public function paginate($request)
    {
        $perPage = $request->totalItems ?? getEntriesPerPage();
        $currentPage = $request->current_page ?? 0;

        $total = $this->query->count();
        $data = $this->query->skip($perPage * $currentPage)->take($perPage)->get();
        foreach ($data as $key => $item) {
            $tokens = [];
            foreach ($item->users as $user)
                foreach ($user->tokens as $token)
                    if ($token->mfob_status == 0 || $token->mfob_status == 3 || !$token->mfob_status)
                        $tokens[] = $token->card_id;
            $data[$key]->tokens = $tokens;
            $data[$key]->occupied = $request->occupied ?? null;
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
