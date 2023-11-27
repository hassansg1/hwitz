<?php

namespace App\Services\Parsers;


use App\Models\Building;
use App\Models\User;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Auth;

class UserAnalyticsParser
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
        $this->applyUserTypeFilter($request);
        $this->applyBuildingFilter($request);
        $this->applySort($request);
        $data = $this->paginate($request);
        return $data;

    }

    /**
     * @param $request
     * @return void
     */
    public function applyBuildingFilter($request)
    {
        $building = $request->buildingId;
        $owners = Building::where('id', $building)->pluck('user_id')->toArray();
        $this->query->where(function ($query) use ($building) {
            $query->where('building_id', $building);
            $query->orWhere('parent_unit_id', null);
        });
        $this->query->whereIn('owner_id', $owners);
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
            $this->query->orderBy("users.id", "asc");
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
                $query->orWhere('firstname', 'LIKE', '%' . $var . '%');
                $query->orWhere('middlename', 'LIKE', '%' . $var . '%');
                $query->orWhere('lastname', 'LIKE', '%' . $var . '%');
                $query->orWhere('mobile', 'LIKE', '%' . $var . '%');
            });
        }
    }

    /**
     * @param $request
     * @return void
     */
    public function applyUserTypeFilter($request)
    {
        if (isset($request->userTypeId) && $request->userTypeId != "") {
            $this->query = $this->query->where('usertype_id', $request->userTypeId);
        }
    }

    public function getBaseQuery($request = null)
    {
        return User::with('usertype')->leftJoin('units', 'users.parent_unit_id', '=', 'units.id')
            ->leftJoin('buildings', 'units.building_id', '=', 'buildings.id')
            ->select('users.id as u_id', 'users.*');
    }

    public function paginate($request)
    {
        $perPage = $request->totalItems ?? getEntriesPerPage();
        $currentPage = $request->current_page ?? 0;

        $total = $this->query->count();
        $data = $this->query->skip($perPage * $currentPage)->take($perPage)->get();

        foreach ($data as $key => $item) {
            $tokens = [];
            foreach ($item->tokens as $token)
                if ($token->mfob_status == 0 || $token->mfob_status == 3 || !$token->mfob_status)
                    $tokens[] = $token->card_id;
            $item->userTokens = $tokens;
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
